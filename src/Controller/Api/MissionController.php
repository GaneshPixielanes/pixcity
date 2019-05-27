<?php

namespace App\Controller\Api;

use App\Entity\ClientMissionProposal;
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
     * @Route("/list", name="list")
     */
    public function index(UserMissionRepository $missionRepo, Request $request)
    {
        $page = is_null($request->get('page'))?1:$request->get('page');

        $limit = 5;
        if(is_null($this->getUser()))
        {
            return false;
        }
        $missions = $missionRepo->findMissionsWithLimit([],$this->getUser(), $page, $limit);

        if(empty($missions))
        {
            return new JsonResponse(['success' => false]);
        }

        $result =  $this->render('b2b/mission/_missions.html.twig', [
            'missions' => $missions,
        ])->getContent();

        return new JsonResponse(['success' => true, 'html' => $result]);
    }

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
}
