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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Route("/community-manager/mission/", name="b2b_mission_")
 * @Security("has_role('ROLE_PIXIE')")
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
        $missions['cancelled'] = $userMissionRepo->findBy(['status' => MissionStatus::CANCELLED, 'user' => $this->getUser()]);
        $missions['terminated'] = $userMissionRepo->findBy(['status' => MissionStatus::TERMINATED, 'user' => $this->getUser()]);

        return $this->render('b2b/mission/index.html.twig', [
            'missions' => $missions,
        ]);
    }

    /**
     * @Route("create", name="create")
     */
    public function create(Request $request, Filesystem $filesystem)
    {
        $mission = new UserMission();
        # Get the CM associated with the pack and regions thus associated
        $regions = $this->getUser()->getUserRegion();
//        $regions = $packRepo->find($pack)->get
//        $form = $this->createForm(MissionType::class, $mission,['region' => $this->getUser()->getUserRegion()]);
        $form = $this->createForm(MissionType::class, $mission,['region' => $regions, 'user' => $this->getUser()]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $price = $mission->getMissionBasePrice();
            $clientPrice = $price + ($price * 0.20);
            $tax = 10;
            $transactionFee = 0;
            $total =  $clientPrice + ($clientPrice * (1/$tax)) + $transactionFee;


            $mission->setUser($this->getUser());
            $mission->getUserMissionPayment()->setClientPrice($clientPrice); // Client's price
            $mission->getUserMissionPayment()->setUserBasePrice($price); // Base price
            $mission->getUserMissionPayment()->setTaxPercentage($tax); // Tax Percentage
            $mission->getUserMissionPayment()->setTransactionFee($transactionFee); // Trasnsaction Fee
            $mission->getUserMissionPayment()->setTaxValue($total - $clientPrice); // Tax charged
            $mission->getUserMissionPayment()->setTotal($total); // Total
            $mission->setStatus(MissionStatus::CREATED);

            $em = $this->getDoctrine()->getManager();

            $em->persist($mission);
            $em->flush();
            #Move banner and brief files
            if($filesystem->exists('uploads/'.UserMission::tempFolder().$mission->getBannerImage()) && $mission->getBannerImage() != '')
            {
                $filesystem->copy('uploads/'.UserMission::tempFolder().$mission->getBannerImage(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$mission->getBannerImage());
            }
            if($filesystem->exists('uploads/'.UserMission::tempFolder().$mission->getBriefFiles()) && $mission->getBriefFiles() != '')
            {
                $filesystem->copy('uploads/'.UserMission::tempFolder().$mission->getBriefFiles(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$mission->getBriefFiles());
            }
            #Move files to the upload folder from temp folder
            foreach($mission->getMissionMedia() as $media)
            {
                # If files are found in the temp folder, then move the files from temp folder.
                # Otherwise check the packs folder and move files from there (import images from packs)
                if($filesystem->exists('uploads/'.UserMission::tempFolder().$media->getName()))
                {
                    $filesystem->copy('uploads/'.UserMission::tempFolder().$media->getName(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$media->getName());
                }
                elseif ($filesystem->exists('uploads/packs/'.$mission->getReferencePack()->getId().'/'.$media->getName()))
                {
                    $filesystem->copy('uploads/packs/'.$mission->getReferencePack()->getId().'/'.$media->getName(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$media->getName());
                }
            }

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
    public function edit($id, Request $request,UserMissionRepository $userMissionRepo, Filesystem $filesystem)
    {
        $mission = $userMissionRepo->find($id);
        $regions = $mission->getReferencePack()->getUser()->getUserRegion();
        $form = $this->createForm(MissionType::class, $mission,['region' => $regions, 'user' => $this->getUser()]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $price = $mission->getMissionBasePrice();
            $clientPrice = $price + ($price * 0.20);
            $tax = 10;
            $transactionFee = 0;
            $total =  $clientPrice + ($clientPrice * (1/$tax)) + $transactionFee;


            $mission->setUser($this->getUser());
            $mission->getUserMissionPayment()->setClientPrice($clientPrice); // Client's price
            $mission->getUserMissionPayment()->setUserBasePrice($price); // Base price
            $mission->getUserMissionPayment()->setTaxPercentage($tax); // Tax Percentage
            $mission->getUserMissionPayment()->setTransactionFee($transactionFee); // Trasnsaction Fee
            $mission->getUserMissionPayment()->setTaxValue($total - $clientPrice); // Tax charged
            $mission->getUserMissionPayment()->setTotal($total); // Total
            $em->persist($mission);
            $em->flush();

            #Move banner and brief files
            if($filesystem->exists('uploads/'.UserMission::tempFolder().$mission->getBannerImage()))
            {
                $filesystem->copy('uploads/'.UserMission::tempFolder().$mission->getBannerImage(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$mission->getBannerImage());
            }
            if($filesystem->exists('uploads/'.UserMission::tempFolder().$mission->getBriefFiles()))
            {
                $filesystem->copy('uploads/'.UserMission::tempFolder().$mission->getBriefFiles(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$mission->getBriefFiles());
            }
            #Move files to the upload folder from temp folder
            foreach($mission->getMissionMedia() as $media)
            {
                # If files are found in the temp folder, then move the files from temp folder.
                # Otherwise check the packs folder and move files from there (import images from packs)
                if($filesystem->exists('uploads/'.UserMission::tempFolder().$media->getName()))
                {
                    $filesystem->copy('uploads/'.UserMission::tempFolder().$media->getName(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$media->getName());
                }
                elseif ($filesystem->exists('uploads/packs/'.$mission->getReferencePack()->getId().'/'.$media->getName()))
                {
                    $filesystem->copy('uploads/packs/'.$mission->getReferencePack()->getId().'/'.$media->getName(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$media->getName());
                }
            }

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
            case 'cancel': $mission->setStatus(MissionStatus::CANCEL_REQUEST_INITIATED);
                              break;
            case 'terminate': $mission->setStatus(MissionStatus::TERMINATE_REQUEST_INITIATED);
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

        $fileName = $fileUploader->upload($file, UserMission::tempFolder(), true);

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
