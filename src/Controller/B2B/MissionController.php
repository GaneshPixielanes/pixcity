<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Entity\UserMission;
use App\Form\B2B\MissionType;
use App\Repository\PackRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserPacksRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/client/mission/", name="b2b_mission_")
 */
class MissionController extends AbstractController
{
    /**
     * @Route("list", name="list")
     */
    public function index(UserMissionRepository $userMissionRepo)
    {

//        $missions = $this->getUser()->getUserMission();
        $missions['ongoing'] = $userMissionRepo->findOngoingMissions($this->getUser());
        $missions['cancelled'] = $userMissionRepo->findBy(['status' => MissionStatus::CANCELLED]);
        $missions['terminated'] = $userMissionRepo->findBy(['status' => MissionStatus::TERMINATED]);

        return $this->render('b2b/mission/index.html.twig', [
            'missions' => $missions,
        ]);
    }

    /**
     * @Route("create/{pack}", name="create")
     */
    public function create($pack, Request $request, UserPacksRepository $packRepo, FileUploader $fileUploader)
    {
        $mission = new UserMission();
        # Get the CM associated with the pack and regions thus associated
        $regions = $packRepo->find($pack)->getUser()->getUserRegion();
//        $regions = $packRepo->find($pack)->get
//        $form = $this->createForm(MissionType::class, $mission,['region' => $this->getUser()->getUserRegion()]);
        $form = $this->createForm(MissionType::class, $mission,['region' => $regions]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $mission->setClient($this->getUser());
            $mission->setUser($packRepo->find($pack)->getUser());
            $mission->setReferencePack($packRepo->find($pack));

            $mission->setStatus(MissionStatus::CREATED);

            $em = $this->getDoctrine()->getManager();

            $em->persist($mission);
            $em->flush();

            return $this->redirectToRoute('b2b_mission_list');
        }

        return $this->render('b2b/mission/form.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("edit/{id}",name="edit")
     */
    public function edit($id, Request $request,UserMissionRepository $userMissionRepo)
    {
        $mission = $userMissionRepo->find($id);
        $regions = $mission->getReferencePack()->getUser()->getUserRegion();
        $form = $this->createForm(MissionType::class, $mission,['region' => $regions]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($mission);
            $em->flush();


            return $this->redirectToRoute('b2b_mission_list');
        }
        return $this->render('b2b/mission/form.html.twig',
            [
                'form' => $form->createView(),
                'mission' => $mission
            ]);
    }

    /**
     * @Route("view/{id}",name="view")
     */
    public function view($id, UserMissionRepository $userMissionRepo)
    {
        $mission = $userMissionRepo->find($id);

        return $this->render('b2b/mission/view.html.twig',[
            'mission' => $mission
        ]);
    }

    /**
     * @Route("status",name="status")
     */
    public function status(Request $request, UserMissionRepository $userMissionRepo)
    {
        $mission = $userMissionRepo->find($request->get('id'));
        $em = $this->getDoctrine()->getManager();

        switch($request->get('status'))
        {
            case 'cancel': $mission->setStatus(MissionStatus::CANCELLED);
                              break;
            case 'terminate': $mission->setStatus('terminated');
                               break;
        }

        $em->persist($mission);
        $em->flush();

        return JsonResponse::create(['success' => true]);
    }

    /**
     * @Route("upload", name="_upload")
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $file = $request->files->get('file');

        $fileName = $fileUploader->upload($file, UserMission::uploadFolder(), true);

        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
    }//End of upload


    /**
     * @Route("download/{id}",name="download")
     */
    public function download($id, UserMissionRepository $userMissionRepo)
    {
        $mission = $userMissionRepo->find($id);
        $date = new \DateTime();
        $response = new BinaryFileResponse($mission->getBriefUrl());
        $ext = pathinfo($mission->getBriefUrl(),PATHINFO_EXTENSION);

        $response->headers->set('Content-Type','text/plain');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $mission->getTitle().'_'.$date->format('Ymd').'.'.$ext
        );

        return $response;

    }
}
