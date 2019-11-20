<?php

namespace App\Controller\B2B\Client;

use App\Constant\CompanyStatus;
use App\Constant\MissionStatus;
use App\Entity\ClientTransaction;
use App\Entity\MissionRecurringPriceLog;
use App\Entity\Option;
use App\Entity\Royalties;
use App\Repository\ClientInfoRepository;
use App\Repository\MissionPaymentRepository;
use App\Repository\MissionRecurringPriceLogRepository;
use App\Repository\MissionRecurringRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use App\Service\Mailer;
use App\Service\MangoPayService;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MissionRecurringController extends Controller
{


    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/b2b/client/mission/recurring/", name="b2b_client_mission_recurring")
     */
    public function index(MissionRecurringRepository $missionRecurringRepository,
                          MissionPaymentRepository $missionPayment,
                          ClientInfoRepository $clientInfoRepository,
                          MangoPayService $mangoPayService,
                          Filesystem $filesystem,
                          MissionRecurringPriceLogRepository $missionRecurringPriceLogRepository,
                          UserMissionRepository $userMissionRepository)
    {

        $em = $this->getDoctrine()->getManager();

        $serializer = $this->container->get('serializer');

        $today = date('d-m-Y');

        $options = $this->getDoctrine()->getRepository(Option::class);

        $taxOpt = $options->findOneBy(['slug' => 'tax']);$marginOpt = $options->findOneBy(['slug' => 'margin']);

        $executedMissionIds = [];

        $missions = $missionRecurringRepository->findAll();

        foreach ($missions as $mission){

            if($mission->getMission()->getStatus() == MissionStatus::ONGOING){

                $recurring_date = date('d-m-Y', strtotime('+1 month', strtotime($mission->getPaymentDate()->format('d-m-Y'))));

                if(strtotime($recurring_date) == strtotime($today)){

                    $cityMakerType = '';
                    if($mission->getMission()->getIsTvaApplicable() != NULL){
                        $cityMakerType = CompanyStatus::COMPANY;
                    }

                    $payment = $missionPayment->getPrices($mission->getMission()->getActiveLog()->getUserBasePrice(), $marginOpt->getValue(), $taxOpt->getValue(), $cityMakerType);

                    $margin = $payment['client_price'] - $payment['cm_price'];

                    $amount = $payment['client_total'];

                    if ($payment['client_tax'] != 0){

                        $tax_value = $taxOpt->getValue() / 100 * $margin;

                        $fee = $margin + $tax_value;

                    }else{

                         $fee = $margin;
                    }

                    //get the UserNatural id and walled id of MangoPay
                    $transaction = $clientInfoRepository->findOneBy(['client'=>$mission->getClient()]);

                    $mangoUser = $mangoPayService->getUser($transaction->getMangopayUserId());
                    $wallet = $mangoPayService->getWalletId($transaction->getMangopayWalletId());

                    //Create Transaction
                    $ClientTransaction = new ClientTransaction();

                    $ClientTransaction->setUser($mission->getMission()->getClient());
                    $ClientTransaction->setAmount($amount);
                    $ClientTransaction->setMangopayUserId($mangoUser->Id);
                    $ClientTransaction->setMangopayWalletId($wallet->Id);
                    $ClientTransaction->setPaymentStatus(false);
                    $ClientTransaction->setMission($mission->getMission());
                    $ClientTransaction->setTransactionType('PayIn-Recurring');
                    $ClientTransaction->setTotalAmount($amount);
                    $ClientTransaction->setFee($fee);

                    //each mission card details
                    $mission_card = $missionRecurringRepository->findOneBy(['mission' => $mission->getMission()]);

                    $card_array['card_type'] = $mission_card->getCardType();
                    $card_array['card_id'] = $mission_card->getCardId();

                    $transaction_id  = $mangoPayService->getPayIn($mangoUser, $wallet, $amount * 100, $transaction->getId(),$mission->getMission(),$fee * 100,$card_array);

                    //check the response of the MangoPay,after Pay in
                    $response = $mangoPayService->getResponse($transaction_id);

                    if ($response->Status == 'SUCCEEDED'){

                        $ClientTransaction->setMangopayTransactionId($response->Id);
                        $ClientTransaction->setPaymentStatus(true);
                        $ClientTransaction->getMission()->getUserMissionPayment()->setUserBasePrice($payment['cm_price']);
                        $ClientTransaction->getMission()->getUserMissionPayment()->setCmTax($payment['cm_tax']);
                        $ClientTransaction->getMission()->getUserMissionPayment()->setCmTotal($payment['cm_total']);
                        $ClientTransaction->getMission()->getUserMissionPayment()->setClientPrice($payment['client_price']);
                        $ClientTransaction->getMission()->getUserMissionPayment()->setClientTax($payment['client_tax']);
                        $ClientTransaction->getMission()->getUserMissionPayment()->setClientTotal($payment['client_total']);
                        $ClientTransaction->getMission()->getUserMissionPayment()->setPcsPrice($payment['pcs_price']);
                        $ClientTransaction->getMission()->getUserMissionPayment()->setPcsTax($payment['pcs_tax']);
                        $ClientTransaction->getMission()->getUserMissionPayment()->setPcsTotal($payment['pcs_total']);
                        $ClientTransaction->getMission()->setMissionBasePrice($payment['cm_price']);
                        $ClientTransaction->setMangopayResponse($serializer->serialize($response, 'json'));
                        $last_cycle = $missionRecurringPriceLogRepository->findLastRow($mission->getMission()->getId());

                        if($last_cycle != null){

                            $cycle = $last_cycle->getCycle() + 1;

                        }else{

                            $cycle = 2;

                        }

                        $mission_price_log = new MissionRecurringPriceLog();

                        $mission_price_log->setMission($mission->getMission());
                        $mission_price_log->setActivePrice($mission->getMission()->getActiveLog());
                        $mission_price_log->setCycle($cycle);
                        $mission_price_log->setMonth(date('F'));
                        $mission_price_log->setYear(date('Y'));
                        $mission_price_log->setCreatedAt(new \DateTime());
                        $mission_price_log->setUpdatedAt(new \DateTime());

                        $em->persist($mission_price_log);

                        $em->persist($ClientTransaction);

                        //update next month to execute the mission if still ongoing
                        $mission->setPaymentDate( new \DateTime());

                        $em->persist($mission);

                        $em->flush();

                        $executedMissionIds[] = $mission->getMission()->getId();

                    }elseif($response->Status == 'WAITING'){

                        $ClientTransaction->setMangopayTransactionId($transaction_id);
                        $ClientTransaction->setPaymentStatus(2);
                        $ClientTransaction->setMangopayResponse($serializer->serialize($response, 'json'));
                        $em->persist($ClientTransaction);

                        $em->flush();

                    }else{

                        $ClientTransaction->setMangopayTransactionId($transaction_id);
                        $ClientTransaction->setMangopayResponse($serializer->serialize($response, 'json'));
                        $em->persist($ClientTransaction);

                        $em->flush();

                    }

                }

            }

        }

        return new JsonResponse(['status' => true,'executed_ids' => $executedMissionIds]);
    }

    /**
     * @Route("/b2b/client/mission/invoices/", name="b2b_client_mission_invoices")
     */
    public function createInvoiceEmail(MissionRecurringRepository $missionRecurringRepository,
                                       MissionPaymentRepository $missionPayment,
                                       ClientInfoRepository $clientInfoRepository,
                                       MangoPayService $mangoPayService,
                                       Filesystem $filesystem,
                                       MissionRecurringPriceLogRepository $missionRecurringPriceLogRepository,
                                       UserMissionRepository $userMissionRepository,
                                       NotificationsRepository $notificationsRepository,
                                       Mailer $mailer){

        $em = $this->getDoctrine()->getManager();

        $today = date('d-m-Y');$executedMissionIds = [];

        $options = $this->getDoctrine()->getRepository(Option::class);

        $taxOpt = $options->findOneBy(['slug' => 'tax']);$marginOpt = $options->findOneBy(['slug' => 'margin']);

        $missions = $missionRecurringRepository->findAll();

        foreach ($missions as $mission){

                $recurring_date = date('d-m-Y', strtotime('+1 month', strtotime($mission->getInvoiceDate()->format('d-m-Y'))));

                if(strtotime($recurring_date) == strtotime($today)){

                    $last_row = $missionRecurringPriceLogRepository->findLastRow($mission->getMission()->getId());

                    $cycle = $last_row->getCycle();

                    $previous_payment = $mission->getMission()->getActiveLogById($last_row->getActivePrice());

                    $filesystem->mkdir('invoices/Recurring/'.$mission->getMission()->getId().'/'.$cycle,0777);

                    $client_filename = 'PX-'.$mission->getMission()->getId().'-'.$last_row->getActivePrice()->getId()."-client.pdf";

                    $clientInvoicePath = "invoices/Recurring/".$mission->getMission()->getId().'/'.$cycle.'/'.$client_filename;

                    $cityMakerType = '';

                    if($mission->getMission()->getIsTvaApplicable() != NULL){
                        $cityMakerType = CompanyStatus::COMPANY;
                    }

                    $payment = $missionPayment->getPrices($previous_payment->getUserBasePrice(), $marginOpt->getValue(), $taxOpt->getValue(), $cityMakerType);

                    if(!$filesystem->exists($clientInvoicePath)){
                        $this->container->get('knp_snappy.pdf')->generateFromHtml(
                            $this->renderView('b2b/invoice/client_invoice_recurring.html.twig',
                                array(
                                    'mission' => $mission->getMission(),
                                    'payment' => $payment,
                                    'tax' => $taxOpt->getValue(),
                                    'recurring_mission' => $mission
                                )
                            ), $clientInvoicePath
                        );
                    }


                    $cm_filename = 'PX-'.$mission->getMission()->getId().'-'.$last_row->getActivePrice()->getId()."-cm.pdf";

                    $cmInvoicePath = "invoices/Recurring/".$mission->getMission()->getId().'/'.$cycle.'/'.$cm_filename;

                    if(!$filesystem->exists($cmInvoicePath)){

                        $this->container->get('knp_snappy.pdf')->generateFromHtml(
                            $this->renderView('b2b/invoice/cm_invoice_recurring.html.twig',
                                array(
                                    'mission' => $mission->getMission(),
                                    'payment' => $payment,
                                    'tax' => $taxOpt->getValue(),
                                    'recurring_mission' => $mission
                                )
                            ), $cmInvoicePath
                        );
                    }



                    $pcs_filename = 'PX-'.$mission->getMission()->getId().'-'.$last_row->getActivePrice()->getId()."-pcs.pdf";

                    $pcsInvoicePath = "invoices/Recurring/".$mission->getMission()->getId().'/'.$cycle.'/'.$pcs_filename;

                    if(!$filesystem->exists($pcsInvoicePath)){

                        $this->container->get('knp_snappy.pdf')->generateFromHtml(
                            $this->renderView('b2b/invoice/pcs_invoice_recurring.html.twig',
                                array(
                                    'mission' => $mission->getMission(),
                                    'payment' => $payment,
                                    'tax' => $taxOpt->getValue(),
                                    'recurring_mission' => $mission
                                )
                            ), $pcsInvoicePath
                        );

                    }

                    $royalties = new Royalties();
                    $royalties->setMission($mission->getMission());
                    $royalties->setCm($mission->getMission()->getUser());
                    $royalties->setTax($taxOpt->getValue());
                    $royalties->setBasePrice($payment['cm_price']);
                    $royalties->setTaxValue($payment['cm_tax']);
                    $royalties->setTotalPrice($payment['cm_total']);
                    $royalties->setInvoicePath($cmInvoicePath);
                    $royalties->setStatus('pending');
                    $royalties->setCycle($cycle);
                    $royalties->setBankDetails('through generation cron');
                    $em->persist($royalties);

                    $mission->setInvoiceDate(new \DateTime());
                    $mission->setPaymentStatus('invoice-generated');
                    $em->persist($mission);




                    /* Mail sent to the CM */
                    $this->mailer->send($mission->getMission()->getUser()->getEmail(),
                    "FACTURE DISPONIBLE",
                    'emails/b2b/mission-client-invoice-available.html.twig',
                    [
                        'mission' => $mission->getMission()
                    ],null,'services@pix.city');

                    /* Mail sent to the Client */
                    $this->mailer->send($mission->getMission()->getClient()->getEmail(),
                    "FACTURE DISPONIBLE",
                    'emails/b2b/mission-cm-invoice-available.html.twig',
                    [
                        'mission' => $mission->getMission()
                    ],null,'services@pix.city');

                    /* Notification sent to client */
                    $message = 'Votre facture concernant la mission '.$mission->getMission()->getTitle().' est disponible dans votre espace client.';
                    $notificationsRepository->insert(null,$mission->getMission()->getClient(),'invoice_available_client', $message, $last_row->getActivePrice()->getId(),1);

                    /* Notification sent to CM */
                    $message = 'Votre facture concernant la mission '.$mission->getMission()->getTitle().' est disponible dans votre espace city-maker.';
                    $notificationsRepository->insert($mission->getMission()->getUser(),null,'invoice_available_cm', $message, $last_row->getActivePrice()->getId(),1);

                    $em->flush();

                    $executedMissionIds[] = $mission->getMission()->getId();

                }


        }

        return new JsonResponse(['status' => true,'executed_ids' => $executedMissionIds]);

    }
}
