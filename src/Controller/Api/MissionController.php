<?php

namespace App\Controller\Api;

use App\Constant\MissionStatus;
use App\Entity\ClientMissionProposal;
use App\Entity\Option;
use App\Repository\ClientTransactionRepository;
use App\Repository\MissionPaymentRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserPacksRepository;
use App\Service\FileUploader;
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
     * @Route("/status",name="client_status")
     */
    public function statusUpdate(UserMissionRepository $missionRepo,
                                 Request $request,
                                 NotificationsRepository $notificationsRepository,
                                 Filesystem $filesystem,
                                 ClientTransactionRepository $clientTransactionRepository,
                                 MissionPaymentRepository $missionPaymentRepository,
                                 MangoPayService $mangoPayService)
    {
        $mission = $missionRepo->activePrices($request->get('id'));
        $status = '';

        if(is_null($mission) || $mission->getClient()->getId() != $this->getUser()->getId())
        {
            return new JsonResponse(['success' => false, 'message' => 'Please login to the right account']);
        }
        switch($request->get('status'))
        {

            case 'accept':
                    if($mission->getStatus() == MissionStatus::CREATED)
                    {
                        $status = MissionStatus::ONGOING;
                        $mission->setMissionAgreedClient(1);
                        $notificationsRepository->insert($mission->getUser(),null,'accept_mission','Mission "'.$mission->getTitle().'" has been accepted by '.$mission->getClient(),1);
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
                        $notificationsRepository->insert($mission->getUser(),null,'deny_mission','Mission "'.$mission->getTitle().'" has been denied by '.$mission->getClient(),1);
                    }
                    else{
                        return new JsonResponse(['success' => false, 'message' => 'Illegal operation']);
                    }
                    break;
            case 'cancel':
                    if($mission->getStatus() == MissionStatus::CANCEL_REQUEST_INITIATED)
                    {
                        $status = MissionStatus::CANCELLED;
                        $notificationsRepository->insert($mission->getUser(),null,'cancel_mission','Client '.$mission->getClient().' has accepted cancellation request of mission '.$mission->getTitle(),1);
                        break;
                    }
                    elseif ($mission->getStatus() == MissionStatus::CREATED || $mission->getStatus() == MissionStatus::ONGOING)
                    {
                        $status = MissionStatus::CANCEL_REQUEST_INITIATED_CLIENT;
                        $notificationsRepository->insert($mission->getUser(),null,'cancel_mission','Client '.$mission->getClient().' has requested for  cancellation of mission '.$mission->getTitle(),1);
                    }
                    else
                    {
                        return new JsonResponse(['success' => false, 'message' => 'Illegal Operation']);
                    }
                    break;
            case 'terminate':
                    if($mission->getStatus() == MissionStatus::TERMINATE_REQUEST_INITIATED || $mission->getStatus() == MissionStatus::ONGOING)
                    {

                        $status = MissionStatus::TERMINATED;

                        $notificationsRepository->insert($mission->getUser(),null,'terminate_mission','Client '.$mission->getClient().' has  requested for termination of mission '.$mission->getTitle(),1);

                        break;
                    }
                    elseif ($mission->getStatus() == MissionStatus::CREATED)
                    {
                        $status = MissionStatus::TERMINATE_REQUEST_INITIATED_CLIENT;
                        $notificationsRepository->insert($mission->getUser(),null,'terminate_mission','Client '.$mission->getClient().' has  requested for termination of mission '.$mission->getTitle(),1);

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
        $entityManager->flush();

        $options = $this->getDoctrine()->getRepository(Option::class);

        $tax = $options->findOneBy(['slug' => 'tax']);
        $margin = $options->findOneBy(['slug' => 'margin']);

        $cityMakerType = $mission->getUser()->getPixie()->getBilling()->getStatus();

        $first_result = $missionPaymentRepository->getPrices($mission->getUserMissionPayment()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $last_result = $missionPaymentRepository->getPrices($mission->getActiveLog()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $result = [];

        $result['price'] = $last_result['client_price'];
        $result['tax'] = $last_result['client_tax'];
        $result['total'] = $result['price'] + $result['tax'];
        $result['advance_payment'] = $first_result['client_total'];
        $result['need_to_pay'] = $result['total'] - $result['advance_payment'];
        $result['refund_amount'] = $result['advance_payment'] - $result['total'];


        $transaction = $clientTransactionRepository->findBy(['mission' => $mission->getId()]);

        if($result['need_to_pay'] != 0){
//                            $response = $mangoPayService->refundPayment($transaction,$first_result['client_total'],$result['refund_amount']);
        }

        $transaction[0]->getMission()->getUserMissionPayment()->setUserBasePrice($last_result['cm_price']);
        $transaction[0]->getMission()->getUserMissionPayment()->setCmTax($last_result['cm_tax']);
        $transaction[0]->getMission()->getUserMissionPayment()->setCmTotal($last_result['cm_total']);
        $transaction[0]->getMission()->getUserMissionPayment()->setClientPrice($last_result['client_price']);
        $transaction[0]->getMission()->getUserMissionPayment()->setClientTax($last_result['client_tax']);
        $transaction[0]->getMission()->getUserMissionPayment()->setClientTotal($last_result['client_total']);
        $transaction[0]->getMission()->getUserMissionPayment()->setPcsPrice($last_result['pcs_price']);
        $transaction[0]->getMission()->getUserMissionPayment()->setPcsTax($last_result['pcs_tax']);
        $transaction[0]->getMission()->getUserMissionPayment()->setPcsTotal($last_result['pcs_total']);
        $transaction[0]->getMission()->setMissionBasePrice($last_result['cm_price']);

        $notificationsRepository->insert($mission->getUser(),null,'terminate_mission','Client '.$mission->getClient().' has accepted the request for termination of mission '.$mission->getTitle(),0);

        $filesystem->mkdir('invoices/'.$mission->getId());

        $filename = $this->createSlug($mission->getTitle());

        $clientInvoicePath = "invoices/".$mission->getId().'/'.$filename."-client.pdf";

        $this->container->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView('b2b/invoice/client_invoice.html.twig',
                array(
                    'mission' => $mission
                )
            ), $clientInvoicePath
        );

        $cmInvoicePath = "invoices/".$mission->getId().'/'.$filename."-cm.pdf";

        $this->container->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView('b2b/invoice/cm_invoice.html.twig',
                array(
                    'mission' => $mission
                )
            ), $cmInvoicePath
        );

        $pcsInvoicePath = "invoices/".$mission->getId().'/'.$filename."-pcs.pdf";

        $this->container->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView('b2b/invoice/pcs_invoice.html.twig',
                array(
                    'mission' => $mission
                )
            ), $pcsInvoicePath
        );

        return new JsonResponse(['success' => true, 'message' => 'Status has been updated']);

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
