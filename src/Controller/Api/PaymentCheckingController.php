<?php

namespace App\Controller\Api;

use App\Constant\MissionStatus;
use App\Entity\ClientTransaction;
use App\Entity\MissionRecurringPriceLog;
use App\Entity\Option;
use App\Repository\ClientTransactionRepository;
use App\Repository\MissionPaymentRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use App\Service\MangoPayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class PaymentCheckingController extends AbstractController
{
    /**
     * @Route("/api/payment/checking", name="api_payment_checking")
     */
    public function index(ClientTransactionRepository $clientTransactionRepository,MangoPayService $mangoPayService,
                         UserMissionRepository $userMissionRepository,MissionPaymentRepository $missionPaymentRepository,
                         NotificationsRepository $notificationsRepository)
    {

        $em = $this->getDoctrine()->getManager();$executedMissionIds = [];

        $serializer = $this->container->get('serializer');

        $payments = $clientTransactionRepository->findBy(['paymentStatus' => '2']);

        foreach ($payments as $payment){

            $pay_in_Id = $payment->getMangopayTransactionId();

            if($pay_in_Id != null){

                $result = $mangoPayService->getResponse($pay_in_Id);

                if($result->Status = "SUCCEEDED"){

                    $payment->setPaymentStatus(1);
                    $payment->getMission()->setMissionAgreedClient(1);
                    $payment->setMangopayResponse($serializer->serialize($result, 'json'));

                    $mission = $userMissionRepository->activePrices($payment->getMission());

                    $options = $this->getDoctrine()->getRepository(Option::class);

                    $tax = $options->findOneBy(['slug' => 'tax']);

                    $margin = $options->findOneBy(['slug' => 'margin']);

                    $cityMakerType = $mission->getUser()->getPixie()->getBilling()->getStatus();

                    $last_result = $missionPaymentRepository->getPrices($mission->getActiveLog()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);
//                    dd($last_result);
                    $payment->getMission()->getUserMissionPayment()->setUserBasePrice($last_result['cm_price']);
                    $payment->getMission()->getUserMissionPayment()->setCmTax($last_result['cm_tax']);
                    $payment->getMission()->getUserMissionPayment()->setCmTotal($last_result['cm_total']);
                    $payment->getMission()->getUserMissionPayment()->setClientPrice($last_result['client_price']);
                    $payment->getMission()->getUserMissionPayment()->setClientTax($last_result['client_tax']);
                    $payment->getMission()->getUserMissionPayment()->setClientTotal($last_result['client_total']);
                    $payment->getMission()->getUserMissionPayment()->setPcsPrice($last_result['pcs_price']);
                    $payment->getMission()->getUserMissionPayment()->setPcsTax($last_result['pcs_tax']);
                    $payment->getMission()->getUserMissionPayment()->setPcsTotal($last_result['pcs_total']);
                    $payment->getMission()->setMissionBasePrice($last_result['cm_price']);


                    if ($mission->getStatus() == MissionStatus::CREATED) {

                        $payment->getMission()->setStatus(MissionStatus::ONGOING);

                        $message = $mission->getClient() . ' a accepté votre devis et a effectué son pré-paiement, la mission (qu\'elle soit récurrente ou one shot) peut démarrer.';
                        $notificationsRepository->insert($mission->getUser(), null, 'mission_client_paid', $message, $mission->getId());

                        $message = 'Notre partenaire a bien reçu votre pré-paiement. Que vous soyez dans le cas d\'une mission one-shot ou récurrente, le city-maker va être averti du cantonnement de cette somme et il pourra démarrer la mission.';
                        $notificationsRepository->insert(null, $mission->getClient(), 'mission_cliet_paid_complete', $message, $mission->getId());

                        if ($mission->getMissionType() == 'recurring') {

                            $mission_price_log = new MissionRecurringPriceLog();
                            $mission_price_log->setMission($mission);
                            $mission_price_log->setActivePrice($mission->getActiveLog());
                            $mission_price_log->setCycle(1);
                            $mission_price_log->setMonth(date('F'));
                            $mission_price_log->setYear(date('Y'));
                            $mission_price_log->setCreatedAt(new \DateTime());
                            $mission_price_log->setUpdatedAt(new \DateTime());

                            $em->persist($mission_price_log);

                        }


                        $em->persist($payment);

                        $em->flush();

                        $executedMissionIds[] = $mission->getId();

                    }

                }

            }

        }

        return new JsonResponse(['status' => true,'executed_ids' => $executedMissionIds]);

    }


}
