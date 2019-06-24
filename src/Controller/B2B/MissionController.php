<?php

namespace App\Controller\B2B;

use App\Constant\CompanyStatus;
use App\Constant\MissionStatus;
use App\Entity\ClientMissionProposal;
use App\Entity\Option;
use App\Entity\UserMission;
use App\Form\B2B\MissionType;
use App\Repository\ClientMissionProposalMediaRepository;
use App\Repository\ClientMissionProposalRepository;
use App\Repository\MissionDocumentRepository;
use App\Repository\MissionMediaRepository;
use App\Repository\NotificationsRepository;
use App\Repository\OptionRepository;
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
 * @Security("has_role('ROLE_CM')")
 */
class MissionController extends AbstractController
{
    /**
     * @Route("list", name="list")
     */
    public function index(UserMissionRepository $userMissionRepo, OptionRepository $optionsRepo)
    {
        $missions['ongoing'] = $userMissionRepo->findOngoingMissions($this->getUser());
        $missions['cancelled'] = $userMissionRepo->findBy(['status' => MissionStatus::CANCELLED, 'user' => $this->getUser()],[],['id' => 'DESC']);
        $missions['terminated'] = $userMissionRepo->findBy(['status' => MissionStatus::TERMINATED, 'user' => $this->getUser()],[],['id' => 'DESC']);
        $missions['drafts'] = $userMissionRepo->findBy(['status' => 'created', 'user' => $this->getUser()]);

        return $this->render('b2b/mission/index.html.twig', [
            'missions' => $missions,
            'tax' => $optionsRepo->findOneBy(['slug' => 'margin'])
        ]);
    }

    /**
     * @Route("create", name="create")
     */
    public function create(Request $request, Filesystem $filesystem, ClientMissionProposalRepository $clientMissionProposalRepo,NotificationsRepository $notificationsRepository)
    {

        $mission = new UserMission();
        # Get the CM associated with the pack and regions thus associated
        $regions = $this->getUser()->getUserRegion();
        $proposals = $clientMissionProposalRepo->findBy(['user' => $this->getUser()]);
        if(count($proposals) == 0)
        {
            return $this->redirect('/community-manager/mission/list');
        }

        $options = $this->getDoctrine()->getRepository(Option::class);
        $tax = $options->findOneBy(['slug' => 'tax']);
        $margin = $options->findOneBy(['slug' => 'margin']);
//        $regions = $packRepo->find($pack)->get
//        $form = $this->createForm(MissionType::class, $mission,['region' => $this->getUser()->getUserRegion()]);
        $form = $this->createForm(MissionType::class, $mission,[
            'region' => $regions,
            'user' => $this->getUser(),
            'proposals' => $proposals
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $cityMakerType = $this->getUser()->getPixie()->getBilling()->getStatus();
            $price = $mission->getMissionBasePrice();

            $margin = $margin->getValue();
            $tax = $tax->getValue();
            if($cityMakerType != CompanyStatus::COMPANY)
            {
                #Calculate client price; cp = (margin * baseprice)/100

                /* Get CM price details */
                $basePrice = $price;
                $cmTax = 0;
                $cmTotal = $price;

                /* Get client price details*/
                $clientPrice = (100 * $price)/(100 - $margin);
                $clientTax = 0;
                $clientTotal = $clientPrice;



                /* Get Pix City Services details */

//                $pcsPrice = $clientPrice - $price;
//                $pcsTax = $pcsPrice * ($margin/100);
//                $pcsTotal = $pcsPrice - $pcsTax;
                $pcsPrice = (($clientPrice - $price)/100)*(100-16.66667);
                $pcsTax = (($clientPrice - $price)/100)* 16.66667;
                $pcsTotal = $pcsPrice + $pcsTax;


            }
            else
            {
                /* Get CM price details */
//                $basePrice = $price - ($price * ($tax/100));
//                $basePrice =  $price/(100 -  $margin) * 100;
                $basePrice =  $price;
                $cmTotal = $price + ($basePrice * ($tax/100));
                $cmTax = $cmTotal - $basePrice;


                /* Get client price details*/
                $clientPrice = $price/(100 - $margin) * 100;
                $clientTax = $clientPrice * $tax/100;
                $clientTotal = $clientPrice + $clientTax;
//                $clientTotal = (100 * $price)/(100 - $margin);
//                $clientPrice = $clientTotal - ($clientTotal * ($tax/100));

//                $clientTax = $clientTotal - $clientPrice;

                /* Get Pix City Services details */
                $pcsPrice = $clientPrice - $price;
                $pcsTax = $pcsPrice * ($tax/100);
                $pcsTotal = $pcsPrice + $pcsTax;


            }
//            $transactionFee = 0;
//            $total =  $clientPrice + ($clientPrice * ($tax/100)) + $transactionFee;


            $mission->setUser($this->getUser());
            $mission->getUserMissionPayment()->setClientPrice($clientPrice); // Client's price
            $mission->getUserMissionPayment()->setClientTax($clientTax);
            $mission->getUserMissionPayment()->setClientTotal($clientTotal);

            $mission->getUserMissionPayment()->setUserBasePrice($basePrice); // Base price
            $mission->getUserMissionPayment()->setCmTax($cmTax);
            $mission->getUserMissionPayment()->setCmTotal($cmTotal);

            $mission->getUserMissionPayment()->setPcsPrice($pcsPrice); //PCS price
            $mission->getUserMissionPayment()->setPcsTax($pcsTax);
            $mission->getUserMissionPayment()->setPcsTotal($pcsTotal);

//            $mission->getUserMissionPayment()->setTransactionFee($transactionFee); // Trasnsaction Fee
//            $mission->getUserMissionPayment()->setTaxValue($total - $clientPrice); // Tax charged
//            $mission->getUserMissionPayment()->setTotal($total); // Total
            $mission->setStatus(MissionStatus::CREATED);
//            $mission->setCreatedAt(new \DateTime('Y-m-d H:i:s'));

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
                elseif ($filesystem->exists('uploads/pack/'.$mission->getReferencePack()->getId().'/'.$media->getName()))
                {
                    $filesystem->copy('uploads/pack/'.$mission->getReferencePack()->getId().'/'.$media->getName(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$media->getName());
                }
            }

            $notificationsRepository->insert(null,$mission->getClient(),'create_mission', 'A mission <strong>'.$mission->getTitle().'</strong> has been created by <strong>'.$this->getUser().'</strong> on pack <strong>'.$mission->getReferencePack()->getTitle().'</strong>', $mission->getId());

            return $this->redirectToRoute('b2b_mission_list');

        }

        return $this->render('b2b/mission/form.html.twig',
            [
                'form' => $form->createView(),
                'margin' => $margin->getValue()
            ]);
    }

    /**
     * @Route("edit/{id}",name="edit")
     */
    public function edit($id, Request $request,UserMissionRepository $userMissionRepo, Filesystem $filesystem, ClientMissionProposalRepository $clientMissionProposalRepo)
    {
        $mission = $userMissionRepo->findBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        if(empty($mission))
        {
            return $this->redirect('/community-manager/mission/list');
        }
        else
        {
            $mission = $mission[0];
        }
        $options = $this->getDoctrine()->getRepository(Option::class);
        $tax = $options->findOneBy(['slug' => 'tax']);
        $margin = $options->findOneBy(['slug' => 'margin']);
        $regions = $mission->getReferencePack()->getUser()->getUserRegion();
        $form = $this->createForm(MissionType::class, $mission,['region' => $regions,
            'user' => $this->getUser(),
            'proposals' => $clientMissionProposalRepo->findBy(['user' => $this->getUser()])
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();

            $cityMakerType = $this->getUser()->getPixie()->getBilling()->getStatus();
            $price = $mission->getMissionBasePrice();

            $margin = $margin->getValue();
            $tax = $tax->getValue();
            if($cityMakerType != CompanyStatus::COMPANY)
            {
                #Calculate client price; cp = (margin * baseprice)/100

                /* Get CM price details */
                $basePrice = $price;
                $cmTax = 0;
                $cmTotal = $price;

                /* Get client price details*/
                $clientPrice = (100 * $price)/(100 - $margin);
                $clientTax = 0;
                $clientTotal = $clientPrice;



                /* Get Pix City Services details */

//                $pcsPrice = $clientPrice - $price;
//                $pcsTax = $pcsPrice * ($margin/100);
//                $pcsTotal = $pcsPrice - $pcsTax;
                $pcsPrice = (($clientPrice - $price)/100)*(100-16.66667);
                $pcsTax = (($clientPrice - $price)/100)* 16.66667;
                $pcsTotal = $pcsPrice + $pcsTax;


            }
            else
            {
                /* Get CM price details */
//                $basePrice = $price - ($price * ($tax/100));
//                $basePrice =  $price/(100 -  $margin) * 100;
                $basePrice =  $price;
                $cmTotal = $price + ($basePrice * ($tax/100));
                $cmTax = $cmTotal - $basePrice;


                /* Get client price details*/
                $clientPrice = $price/(100 - $margin) * 100;
                $clientTax = $clientPrice * $tax/100;
                $clientTotal = $clientPrice + $clientTax;
//                $clientTotal = (100 * $price)/(100 - $margin);
//                $clientPrice = $clientTotal - ($clientTotal * ($tax/100));

//                $clientTax = $clientTotal - $clientPrice;

                /* Get Pix City Services details */
                $pcsPrice = $clientPrice - $price;
                $pcsTax = $pcsPrice * ($tax/100);
                $pcsTotal = $pcsPrice + $pcsTax;


            }

            $mission->setUser($this->getUser());
            $mission->setReferencePack($mission->getReferencePack());

            $mission->getUserMissionPayment()->setClientPrice($clientPrice); // Client's price
            $mission->getUserMissionPayment()->setClientTax($clientTax);
            $mission->getUserMissionPayment()->setClientTotal($clientTotal);

            $mission->getUserMissionPayment()->setUserBasePrice($basePrice); // Base price
            $mission->getUserMissionPayment()->setCmTax($cmTax);
            $mission->getUserMissionPayment()->setCmTotal($cmTotal);

            $mission->getUserMissionPayment()->setPcsPrice($pcsPrice); //PCS price
            $mission->getUserMissionPayment()->setPcsTax($pcsTax);
            $mission->getUserMissionPayment()->setPcsTotal($pcsTotal);

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
                elseif ($filesystem->exists('uploads/mission/'.$mission->getReferencePack()->getId().'/'.$media->getName()))
                {
                    $filesystem->copy('uploads/mission/'.$mission->getReferencePack()->getId().'/'.$media->getName(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$media->getName());
                }
            }

            return $this->redirectToRoute('b2b_mission_list');
        }
        return $this->render('b2b/mission/edit-form.html.twig',
        [
            'form' => $form->createView(),
            'mission' => $mission,
            'margin' => $margin->getValue()
        ]);
    }

    /**
     * @Route("view/{id}",name="view")
     */
    public function view($id, UserMissionRepository $userMissionRepo)
    {
        $mission = $userMissionRepo->findBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        if(empty($mission))
        {
            return $this->redirect('/community-manager/mission/list');
        }
        else
        {
            $mission = $mission[0];
        }

        return $this->render('b2b/mission/view.html.twig',[
            'mission' => $mission
        ]);
    }

    /**
     * @Route("status",name="status")
     */
    public function status(Request $request, UserMissionRepository $userMissionRepo,NotificationsRepository $notificationsRepository)
    {
        $mission = $userMissionRepo->findBy([
            'id' => $request->get('id'),
            'user' => $this->getUser()
        ]);
        if(empty($mission))
        {
            return JsonResponse::create(['success' => false]);
        }
        else
        {
            $mission = $mission[0];
        }
        $em = $this->getDoctrine()->getManager();
        switch($request->get('status'))
        {
            case 'cancel': if($mission->getStatus() == MissionStatus::CREATED|| $mission->getStatus() == MissionStatus::ONGOING)
                            {
                                $mission->setStatus(MissionStatus::CANCEL_REQUEST_INITIATED);
                                $mission->setCancelledBy($request->get('cancelledBy'));
                                $mission->setCancelReason($request->get('reason'));
                                $notificationsRepository->insert(null,$mission->getClient(),'cancel_mission',$this->getUser().' has requested for the cancellation of mission '.$mission->getStatus(),$mission->getId());
                                break;
                            }
                            elseif($mission->getStatus() == MissionStatus::CANCEL_REQUEST_INITIATED_CLIENT)
                            {
                                $mission->setStatus(MissionStatus::CANCELLED);
                                $notificationsRepository->insert(null,$mission->getClient(),'cancel_mission',$this->getUser().' has accepted your request for the cancellation of mission '.$mission->getStatus(),$mission->getId());
                                break;
                            }

            case 'terminate':
                if($mission->getStatus() == MissionStatus::CREATED|| $mission->getStatus() == MissionStatus::ONGOING)
                {
                    $mission->setStatus(MissionStatus::TERMINATE_REQUEST_INITIATED);
                    $notificationsRepository->insert(null,$mission->getClient(),'terminate_mission', $this->getUser().' has requested for termination of mission '.$mission->getTitle(),$mission->getId());
                    break;
                }
                elseif($mission->getStatus() == MissionStatus::TERMINATE_REQUEST_INITIATED_CLIENT || $mission->getStatus() == MissionStatus::TERMINATE_REQUEST_INITIATED)
                {
                    $mission->setStatus(MissionStatus::TERMINATED);
                    $notificationsRepository->insert(null,$mission->getClient(),'terminate_mission', $this->getUser().' has accepted your request for termination of mission '.$mission->getTitle(),$mission->getId());
                    break;
                }

        }

        $em->persist($mission);
        $em->flush();

        return JsonResponse::create(['success' => true]);
    }

    /**
     * @Route("proposals", name="proposals")
     */
    public function proposals(ClientMissionProposalRepository $proposalRepo)
    {
        // Get the proposals
        $proposals = $proposalRepo->findBy(['user' => $this->getUser()],['createdAt'=>'DESC']);

        return $this->render('b2b/mission/proposals.html.twig',[
           'proposals' => $proposals
        ]);


    }

    /**
     * @Route("view-proposal/{id}",name="view_proposal")
     */
    public function viewProposal($id, ClientMissionProposalRepository $clientMissionProposalRepo)
    {
        $proposal = $clientMissionProposalRepo->find($id);

        if($this->getUser()->getId() != $proposal->getUser()->getId())
        {
            return $this->redirect('/community-manager/mission/proposals');
        }

        return $this->render('b2b/mission/proposal-details.html.twig',[
            'proposal' => $proposal
        ]);
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
    public function download($id, MissionDocumentRepository $documentRepo)
    {
        $document = $documentRepo->find($id);
        $mission = $document->getMission();
        $date = new \DateTime();
        $response = new BinaryFileResponse('uploads/missions/temp/'.$document->getName());
        $ext = pathinfo('uploads/missions/temp/'.$document->getName(),PATHINFO_EXTENSION);

        $response->headers->set('Content-Type','text/plain');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $mission->getTitle().'_'.$date->format('Ymd').'.'.$ext
        );

        return $response;

    }

    /**
     * @Route("proposal-document-download/{id}",name="proposal_document_download")
     */
    public function downloadProposalMedia($id, ClientMissionProposalMediaRepository $proposalMediaRepo)
    {
        $media = $proposalMediaRepo->find($id);
        $date = new \DateTime();
        $response = new BinaryFileResponse('uploads/proposals/'.$media->getProposal()->getId().'/'.$media->getName());
        $ext = pathinfo('uploads/proposals/'.$media->getProposal()->getId().'/'.$media->getName(),PATHINFO_EXTENSION);

        $response->headers->set('Content-Type','text/plain');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $media->getName()
        );

        return $response;

    }

    /**
     * @Route("load", name="load")
     */
    public function loadMissions(UserMissionRepository $missionRepo, Request $request)
    {
        $page = is_null($request->get('page'))?1:$request->get('page');

        $limit = 5;
        if(is_null($this->getUser()))
        {
            return new JsonResponse(['success' => false]);
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
     * @Route("document-delete/{id}",name="delete_document")
     */
    public function deleteDocument($id, MissionDocumentRepository $documentRepo)
    {
        $document = $documentRepo->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($document);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);

    }

    /**
     * @Route("/image-delete",name="delete_image")
     */
    public function deleteImages(Request $request, MissionMediaRepository $mediaRepo){

        $em = $this->getDoctrine()->getEntityManager();

        $media = $mediaRepo->findOneBy(['name' => $request->get('name')]);
        if(is_null($media))
        {
            return new JsonResponse(['success' => false]);
        }
        $em->remove($media);

        $em->flush();
        return new JsonResponse(['success' => true]);
//        unlink('uploads/mission/'.$mission->getId().'/'.$request->get('name'));


    }

    /**
     * @Route("/image-display/{id}",name="display_image")
     */
    public function showImages($id,Request $request,UserMissionRepository $userMissionRepository){

        $user = $this->getUser();

        $mission = $userMissionRepository->find($id);

        $result = [];

        if(count($mission->getMissionMedia())){

            foreach($mission->getMissionMedia() as $media)
            {
                $obj['name'] = $media->getName();
                $obj['size'] = '1024';
                $obj['path'] = '/uploads/missions/'.$mission->getid().'/'.$media->getName();
                $obj['id'] = $user->getId().'/'.$mission->getid();
                $result[] = $obj;
            }

        }

        return new JsonResponse($result);



    }

    /**
     * @Route("pack-image-display/{id}",name="display_pack_image")
     */
    public function showPackImages($id,Request $request,UserPacksRepository $packRepo){

        $user = $this->getUser();

        $pack = $packRepo->find($id);

        $result = [];

        if(count($pack->getUserPackMedia())){

            foreach($pack->getUserPackMedia() as $media)
            {
                $obj['name'] = $media->getName();
                $obj['size'] = '1024';
                $obj['path'] = '/uploads/pack/'.$pack->getid().'/'.$media->getName();
                $obj['id'] = $user->getId().'/'.$pack->getid();
                $result[] = $obj;
            }

        }

        return new JsonResponse($result);



    }
}
