<?php

namespace App\Controller\Api;

use App\Constant\CompanyStatus;
use App\Constant\MissionStatus;
use App\Entity\ClientMissionProposal;
use App\Entity\ClientTransaction;
use App\Entity\Option;
use App\Entity\Royalties;
use App\Repository\ClientTransactionRepository;
use App\Repository\MissionPaymentRepository;
use App\Repository\MissionRecurringPriceLogRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserPacksRepository;
use App\Service\FileUploader;
use App\Service\Mailer;
use App\Service\MangoPayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

/**
 * @Route("client/api/mission", name="api_mission_")
 */
class MissionController extends Controller
{


    /**
     * @Route("/send-proposal",name="send_proposal")
     */
    public function sendProposal( UserPacksRepository $packRepo, Request $request, FileUploader $fileUploader)
    {
        if(is_null($this->getUser()))
        {
            return new JsonResponse(['success' => false, 'message' => 'You are no longer logged in, please login and try again']);
        }
        $pack = $packRepo->find($request->get('pack'));

        if(is_null($pack))
        {
            return new JsonResponse(['successs' => false, 'message' => 'Pack does not exist']);
        }
        $file = $request->files->get('file');

        $fileName = $fileUploader->upload($file, ClientMissionProposal::UPLOAD_FOLDER.'/'.$this->getUser()->getId().'/', true);

        $query = $this->getDoctrine()->getManager();

        $proposal = new ClientMissionProposal();

        $proposal->setUser($pack->getUser());
        $proposal->setClient($this->getUser());
        $proposal->setDescription(trim($request->get('description')));
        $proposal->setBrief($fileName);
        $proposal->setPack($pack);

        $query->persist($proposal);
        $query->flush();

        return new JsonResponse(['success' => true, 'message' => 'Proposal has been sent!']);
    }

    /**
     * @Route("/status/{id}/{mstatus}",name="client_status")
     */
    public function statusUpdate($id,$mstatus,UserMissionRepository $missionRepo,
                                 Request $request,
                                 NotificationsRepository $notificationsRepository,
                                 Filesystem $filesystem,
                                 ClientTransactionRepository $clientTransactionRepository,
                                 MissionPaymentRepository $missionPaymentRepository,
                                 MangoPayService $mangoPayService,
                                 MissionRecurringPriceLogRepository $missionRecurringPriceLogRepository,
                                 Mailer $mailer)
    {

        $mission = $missionRepo->activePrices($id);

        $status = '';

        $clientTransaction = new ClientTransaction();


        $transaction = $clientTransactionRepository->findLastRow($mission->getId());

        $options = $this->getDoctrine()->getRepository(Option::class);

        $tax = $options->findOneBy(['slug' => 'tax']);

        $margin = $options->findOneBy(['slug' => 'margin']);

        $cityMakerType = "";

        if($mission->getIsTvaApplicable() != NULL)
        {
            $cityMakerType = CompanyStatus::COMPANY;
        }

        $first_result = $missionPaymentRepository->getPrices($mission->getUserMissionPayment()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $last_result  = $missionPaymentRepository->getPrices($mission->getActiveLog()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $result = [];

        $result['price'] = $last_result['client_price'];
        $result['tax'] = $last_result['client_tax'];
        $result['total'] = $result['price'] + $result['tax'];
        $result['advance_payment'] = $first_result['client_total'];
        $result['need_to_pay'] = $result['total'] - $result['advance_payment'];
        $result['refund_amount'] = $last_result['pcs_total'];
        $result['persent_price'] = $first_result['cm_price'] - $last_result['cm_price'];
        $result['display_price'] = $last_result['client_price'] - $last_result['cm_price'];
        $result['margin'] =  $result['display_price'] - $result['persent_price'];
        $result['fess'] = $result['margin'] + (0.2 * $result['margin']);


        if(is_null($mission) || $mission->getClient()->getId() != $this->getUser()->getId())
        {
            return new JsonResponse(['success' => false, 'message' => 'Please login to the right account']);
        }

        switch($mstatus)
        {

            case 'accept':
                if($mission->getStatus() == MissionStatus::CREATED)
                {
                    $status = MissionStatus::ONGOING;
                    $mission->setMissionAgreedClient(1);
//                        $notificationsRepository->insert($mission->getUser(),null,'accept_mission','Mission "'.$mission->getTitle().'" has been accepted by '.$mission->getClient(),$mission->getId());
                }
                else
                {
                    return new JsonResponse(['success' => false, 'message' => 'Illegal operation']);
                }
                break;
            case 'deny':
                if($mission->getStatus() == MissionStatus::CREATED)
                {
                    $status = MissionStatus::CLIENT_DECLINED;
                    $mission->setMissionAgreedClient(0);
//                        $notificationsRepository->insert($mission->getUser(),null,'deny_mission','Mission "'.$mission->getTitle().'" has been denied by '.$mission->getClient(),$mission->getId());
                }
                else{
                    return new JsonResponse(['success' => false, 'message' => 'Illegal operation']);
                }
                break;
            case 'cancel':

                if($mission->getStatus() == MissionStatus::CANCEL_REQUEST_INITIATED)
                {

                    $status = MissionStatus::CANCELLED;

                    $margin_value = $first_result['client_price'] - $first_result['cm_price'];

                    if($first_result['client_tax'] != 0){

                        $tax_value = $tax->getValue() / 100 * $margin_value;

                        $fee = $margin_value + $tax_value;

                    }else{

                        $fee = $margin_value;
                    }



                    $client_paid = $first_result['client_total'];

                    $fee_percentage = (2 / 100) * $client_paid;

                    $fees_creadited = $fee - $fee_percentage;

                    $refund_amount = $client_paid - $fee;

                    if($fees_creadited < 0){
                        $fees_creadited = 0;
                        $refund_amount = $client_paid;
                    }

                    $mission->getUserMissionPayment()->setAdjustment($client_paid);
                    $clientTransaction->setTransactionType('Refund-Full');
                    $clientTransaction->setAmount($result['price']);
                    $clientTransaction->setTotalAmount($first_result['client_total']);
                    $clientTransaction->setFee($fees_creadited);

                    $response = $mangoPayService->refundPayment($transaction,$refund_amount,$fees_creadited);

                    //City-maker-notification
                    $notificationsRepository->insert($mission->getUser(),null,'cancel_mission_accept','Vous avez demandé une annulation de la mission. Une action est requise côté client pour l\'annulation définitive de la mission '.$mission->getTitle().'. L\'annulation entraine la restitution des fonds pré-payés au client en misison one shot et en mission récurrente (48h après l\'annulation).',$mission->getId());

                    //Client-notification
                    $notificationsRepository->insert(null,$mission->getClient(),'cancel_mission_client','L\'annulation de la mission '.$mission->getTitle().' est confirmée. En cas de mission one-shot, le pré-paiement que vous avez réalisé lors de l\'acceptation du devis va vous être restitué par notre partenaire Mango Pay sous 4 jours. En cas de mission récurrente, l\'annulation vaut pour le mois en cours et le pré-paiement vous sera restitué par notre partenaire Mango Pay sous 4 jours. Les mois précèdents ne sont pas impactés par cette annulation.',$mission->getId());

                    break;
                }
                elseif ($mission->getStatus() == MissionStatus::CREATED || $mission->getStatus() == MissionStatus::ONGOING)
                {
                    $status = MissionStatus::CANCEL_REQUEST_INITIATED_CLIENT;
//                        $notificationsRepository->insert($mission->getUser(),null,'cancel_mission','Client '.$mission->getClient().' has requested for  cancellation of mission '.$mission->getTitle(),$mission->getId());
                }
                else
                {
                    return new JsonResponse(['success' => false, 'message' => 'Illegal Operation']);
                }
                break;
            case 'terminate':
                if($mission->getStatus() == MissionStatus::TERMINATE_REQUEST_INITIATED || $mission->getStatus() == MissionStatus::ONGOING)
                {

                    $new_result = $missionPaymentRepository->getPrices($result['persent_price'], $margin->getValue(), $tax->getValue(), $cityMakerType);

                    $clientTransaction->setAmount($new_result['client_total']);
                    $clientTransaction->setTotalAmount($new_result['client_total']);
                    $clientTransaction->setFee($new_result['pcs_total']);

                    $status = MissionStatus::TERMINATED;

                    if($result['need_to_pay'] < 0){

                        $clientTransaction->setTransactionType('Refund Partial');

                        $response = $mangoPayService->refundPaymentWithFee($transaction,$new_result['client_total'] - $new_result['pcs_total'],$new_result['pcs_total']);
                        $clientTransaction->setMangopayTransactionId($response);

                    }


                    $notificationsRepository->insert($mission->getUser(),null,'terminate_mission_accept',$mission->getClient()."vient de confirmer la fin de la mission. En cas de mission one-shot, vous recevrez votre paiement sous 48h via notre partenaire Mango Pay. PS : Pensez à créer une mission récurrente pour la prochaine fois si la mission s'est bien passée ! En cas de mission récurrente, votre mission est définitivement terminée, vous recevrez votre dernier paiement à la date anniversaire mensuelle de la signature du devis initial. ",$mission->getId());
                    $notificationsRepository->insert(null,$mission->getClient(),'terminate_mission_client','Vous avez déclaré que la mission était terminée. En cas de mission one-shot, le paiement de votre city-maker sera donc déclenché sous 48H via notre partenaire Mango Pay. En cas de mission récurrente, la mission est réputée terminée à la date anniversaire mensuelle de signature du devis initial. Le city-maker sera payé 48h après cette date anniversaire. ',$mission->getId());

                    break;
                }
                elseif ($mission->getStatus() == MissionStatus::CREATED)
                {
                    $status = MissionStatus::TERMINATE_REQUEST_INITIATED_CLIENT;
//                        $notificationsRepository->insert($mission->getUser(),null,'terminate_mission','Client '.$mission->getClient().' has  requested for termination of mission '.$mission->getTitle(),$mission->getId());

                }
                else
                {
                    return new JsonResponse(['success' => false, 'message' => 'Illegal Operation']);
                }
                break;

        }



        $entityManager = $this->getDoctrine()->getManager();
        $mission->setStatus($status);

        $entityManager->persist($mission);
//        $entityManager->flush();


        $transaction->getMission()->getUserMissionPayment()->setUserBasePrice($last_result['cm_price']);
        $transaction->getMission()->getUserMissionPayment()->setCmTax($last_result['cm_tax']);
        $transaction->getMission()->getUserMissionPayment()->setCmTotal($last_result['cm_total']);
        $transaction->getMission()->getUserMissionPayment()->setClientPrice($last_result['client_price']);
        $transaction->getMission()->getUserMissionPayment()->setClientTax($last_result['client_tax']);
        $transaction->getMission()->getUserMissionPayment()->setClientTotal($last_result['client_total']);
        $transaction->getMission()->getUserMissionPayment()->setPcsPrice($last_result['pcs_price']);
        $transaction->getMission()->getUserMissionPayment()->setPcsTax($last_result['pcs_tax']);
        $transaction->getMission()->getUserMissionPayment()->setPcsTotal($last_result['pcs_total']);
        $transaction->getMission()->setMissionBasePrice($last_result['cm_price']);

        $clientTransaction->setUser($mission->getClient());
        $clientTransaction->setMangopayUserId($transaction->getMangopayUserId());
        $clientTransaction->setMangopayWalletId($transaction->getMangopayWalletId());
        $clientTransaction->setPaymentStatus(true);
        $clientTransaction->setMission($mission);


        $entityManager->persist($transaction);
        $entityManager->persist($clientTransaction);
//        $entityManager->flush();

//        $notificationsRepository->insert($mission->getUser(),null,'terminate_mission','Client '.$mission->getClient().' has accepted the request for termination of mission '.$mission->getTitle(),0);


            if($mission->getStatus() == 'terminated'){

                $filesystem->mkdir('invoices/'.$mission->getId(),0777);

                $client_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-client.pdf";

                $clientInvoicePath = "invoices/".$mission->getId().'/'.$client_filename;

                if(!$filesystem->exists($clientInvoicePath)){
                    $this->container->get('knp_snappy.pdf')->generateFromHtml(
                        $this->renderView('b2b/invoice/client_invoice.html.twig',
                            array(
                                'mission' => $mission,
                                'tax' => $tax->getValue()
                            )
                        ), $clientInvoicePath
                    );
                }


                $cm_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-cm.pdf";

                $cmInvoicePath = "invoices/".$mission->getId().'/'.$cm_filename;

                if(!$filesystem->exists($cmInvoicePath)){
                    $this->container->get('knp_snappy.pdf')->generateFromHtml(
                        $this->renderView('b2b/invoice/cm_invoice.html.twig',
                            array(
                                'mission' => $mission,
                                'tax' => $tax->getValue()
                            )
                        ), $cmInvoicePath
                    );
                }


                $pcs_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-pcs.pdf";

                $pcsInvoicePath = "invoices/".$mission->getId().'/'.$pcs_filename;

                if(!$filesystem->exists($pcsInvoicePath)){

                    $this->container->get('knp_snappy.pdf')->generateFromHtml(
                        $this->renderView('b2b/invoice/pcs_invoice.html.twig',
                            array(
                                'mission' => $mission
                            )
                        ), $pcsInvoicePath
                    );

                }


                $last_row = $missionRecurringPriceLogRepository->findLastRow($mission->getId());

                $cycle = 0;

                if($last_row != null){
                    $cycle = $last_row->getCycle() - 1;
                }


                $royalties = new Royalties();
                $royalties->setMission($mission);
                $royalties->setCm($mission->getUser());
                $royalties->setTax($tax->getValue());
                $royalties->setBasePrice($mission->getUserMissionPayment()->getUserBasePrice());
                $royalties->setTaxValue($mission->getUserMissionPayment()->getCmTax());
                $royalties->setTotalPrice($mission->getUserMissionPayment()->getCmTotal());
                $royalties->setInvoicePath($cmInvoicePath);
                $royalties->setPaymentType('mango_pay');
                $royalties->setStatus('pending');
                $royalties->setCycle($cycle);
                $royalties->setBankDetails(json_encode('no_response'));

                $entityManager->persist($royalties);

        }

        $entityManager->flush();


        if($mission->getStatus() == 'terminated'){
            $this->addFlash('mission_change_setting', 'Toutes nos félicitations! La mission est terminée');
        }elseif($mission->getStatus() == 'cancelled'){
            $this->addFlash('mission_change_setting', 'Félicitations! La mission a bien été annulée');
        }

        return $this->redirect('/client/mission');

    }

    /**
     * Function used to create a slug associated to an "ugly" string.
     *
     * @param string $string the string to transform.
     *
     * @return string the resulting slug.
     */
    public function createSlug($string) {

        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-'
        );

        // -- Remove duplicated spaces
        $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);

        // -- Returns the slug
        return strtolower(strtr($string, $table));


    }
}
