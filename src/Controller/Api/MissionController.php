<?php

namespace App\Controller\Api;

use App\Constant\MissionStatus;
use App\Entity\ClientMissionProposal;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserPacksRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("client/api/mission", name="api_mission_")
 */
class MissionController extends AbstractController
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
    public function statusUpdate(UserMissionRepository $missionRepo, Request $request, NotificationsRepository $notificationsRepository)
    {
        $mission = $missionRepo->find($request->get('id'));
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
                        $notificationsRepository->insert($mission->getUser(),null,'accept_mission','Mission "'.$mission->getTitle().'" has been accepted by '.$mission->getClient());
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
                        $notificationsRepository->insert($mission->getUser(),null,'deny_mission','Mission "'.$mission->getTitle().'" has been denied by '.$mission->getClient());
                    }
                    else{
                        return new JsonResponse(['success' => false, 'message' => 'Illegal operation']);
                    }
                    break;
            case 'cancel':
                    if($mission->getStatus() == MissionStatus::CANCEL_REQUEST_INITIATED)
                    {
                        $status = MissionStatus::CANCELLED;
                        $notificationsRepository->insert($mission->getUser(),null,'cancel_mission','Client '.$mission->getClient().' has accepted cancellation request of mission '.$mission->getTitle());
                        break;
                    }
                    elseif ($mission->getStatus() == MissionStatus::CREATED || $mission->getStatus() == MissionStatus::ONGOING)
                    {
                        $status = MissionStatus::CANCEL_REQUEST_INITIATED_CLIENT;
                        $notificationsRepository->insert($mission->getUser(),null,'cancel_mission','Client '.$mission->getClient().' has requested for  cancellation of mission '.$mission->getTitle());
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
                        $notificationsRepository->insert($mission->getUser(),null,'terminate_mission','Client '.$mission->getClient().' has accepted the request for termination of mission '.$mission->getTitle());

                        break;
                    }
                    elseif ($mission->getStatus() == MissionStatus::CREATED)
                    {
                        $status = MissionStatus::TERMINATE_REQUEST_INITIATED_CLIENT;
                        $notificationsRepository->insert($mission->getUser(),null,'terminate_mission','Client '.$mission->getClient().' has  requested for termination of mission '.$mission->getTitle());

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

        return new JsonResponse(['success' => true, 'message' => 'Status has been updated']);

    }
}
