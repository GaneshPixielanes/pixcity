<?php

namespace App\Controller\B2B;

use App\Constant\CompanyStatus;
use App\Constant\MissionStatus;
use App\Entity\ClientMissionProposal;
use App\Entity\MissionLog;
use App\Entity\Option;
use App\Entity\UserMission;
use App\Form\B2B\MissionLogType;
use App\Form\B2B\MissionType;
use App\Repository\ClientMissionProposalMediaRepository;
use App\Repository\ClientMissionProposalRepository;
use App\Repository\MissionDocumentRepository;
use App\Repository\MissionLogRepository;
use App\Repository\MissionMediaRepository;
use App\Repository\MissionPaymentRepository;
use App\Repository\MissionRepository;
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
    public function create(Request $request,
                           Filesystem $filesystem,
                           ClientMissionProposalRepository $clientMissionProposalRepo,
                           NotificationsRepository $notificationsRepository,
                           MissionPaymentRepository $missionPaymentRepository
    )
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
            'proposals' => $proposals,
            'type' => 'create'
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $cityMakerType = $this->getUser()->getPixie()->getBilling()->getStatus();
            $price = $mission->getMissionBasePrice();

            $margin = $margin->getValue();
            $tax = $tax->getValue();

//            $transactionFee = 0;
//            $total =  $clientPrice + ($clientPrice * ($tax/100)) + $transactionFee;

            $result = $missionPaymentRepository->getPrices($price, $margin, $tax, $cityMakerType);

            $mission->setUser($this->getUser());
            $mission->getUserMissionPayment()->setClientPrice($result['client_price']); // Client's price
            $mission->getUserMissionPayment()->setClientTax($result['client_tax']);
            $mission->getUserMissionPayment()->setClientTotal($result['client_total']);

            $mission->getUserMissionPayment()->setUserBasePrice($result['cm_price']); // Base price
            $mission->getUserMissionPayment()->setCmTax($result['cm_tax']);
            $mission->getUserMissionPayment()->setCmTotal($result['cm_total']);

            $mission->getUserMissionPayment()->setPcsPrice($result['pcs_price']); //PCS price
            $mission->getUserMissionPayment()->setPcsTax($result['pcs_tax']);
            $mission->getUserMissionPayment()->setPcsTotal($result['pcs_total']);

//            $mission->getUserMissionPayment()->setTransactionFee($transactionFee); // Trasnsaction Fee
//            $mission->getUserMissionPayment()->setTaxValue($total - $clientPrice); // Tax charged
//            $mission->getUserMissionPayment()->setTotal($total); // Total
            $mission->setStatus(MissionStatus::CREATED);
//            $mission->setCreatedAt(new \DateTime('Y-m-d H:i:s'));
            $documents = [];
            foreach($mission->getDocuments() as $document)
            {
                $documents[] = $document->getName();
            }
            $missionLog = new MissionLog();
            $missionLog->setUserBasePrice($mission->getMissionBasePrice());
            $missionLog->setCreatedAt(new \DateTime());
            $missionLog->setCreatedBy($mission->getUser()->getId());
            $missionLog->setMission($mission);
            $missionLog->setIsActive(1);
            $missionLog->setBriefFiles(json_encode($documents));

            $mission->addMissionLog($missionLog);

            $em = $this->getDoctrine()->getManager();

            $mission->setLog($missionLog);
            $em->persist($mission);
            $em->persist($missionLog);
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
            if($filesystem->exists('uploads/pack/banner/'.$mission->getBannerImage()) && $mission->getBannerImage() != '')
            {
                $filesystem->copy('uploads/pack/banner/'.$mission->getBannerImage(),'uploads/'.UserMission::uploadFolder().'/'.$mission->getId().'/'.$mission->getBannerImage());
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
    public function edit($id,
                         Request $request,
                         UserMissionRepository $missionRepo,
                        OptionRepository $optionRepo,
                        NotificationsRepository $notificationsRepository
                        )
    {
        $mission = $missionRepo->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
            ]);
        if(is_null($mission))
        {
            return $this->redirectToRoute('b2b_community_manager_index');
        }

        $missionLog = new MissionLog();
        $margin = $optionRepo->findOneBy([
            'slug' => 'margin'
        ])->getValue();

        $form = $this->createForm(MissionLogType::class, $missionLog);

        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();

            $missionLog->setBriefFiles(json_encode($request->get('document')));
            $missionLog->setIsActive(0);
            $missionLog->setMission($mission);
            $missionLog->setCreatedAt(new \DateTime());
            $missionLog->setCreatedBy($this->getUser()->getId());


            $mission->setMissionAgreedClient(0);
            $em->persist($missionLog);
            $em->persist($mission);
            $em->flush();

            $notificationsRepository->insert(null,$mission->getClient(),'edit_mission', 'A mission <strong>'.$mission->getTitle().'</strong> has been edited by <strong>'.$this->getUser().'</strong> on pack <strong>'.$mission->getReferencePack()->getTitle().'</strong>', $missionLog->getId());

            return new JsonResponse(['success' => true]);
        }
        return $this->render('b2b/mission/edit-form.html.twig',
        [
            'form' => $form->createView(),
            'mission' => $mission,
            'missionLog' => $mission,
            'margin' => $margin
        ]);
    }

    private function _resetClientPermission($id)
    {

        $mission = $this->getDoctrine()->getRepository(UserMission::class)->find($id);

        $em = $this->getDoctrine()->getManager();


        $mission->setMissionAgreedClient(1);

        $em->flush();

        return true;
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
                elseif($mission->getStatus() == MissionStatus::TERMINATE_REQUEST_INITIATED_CLIENT)
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

    /**
     * @Route("edit-ajax/{id}",name="edit_ajax")
     */
    public function editAjax($id, Request $request, NotificationsRepository $notificationsRepo)
    {
        $mission = $this->getDoctrine()->getRepository(UserMission::class)->find($id);

        $em = $this->getDoctrine()->getManager();

        $mission->setMissionAgreedClient(1);
        /* Add the logs */
        $missionLog = new MissionLog();
        $missionLog->setUserBasePrice($request->get('price'));
        $missionLog->setCreatedAt(new \DateTime());
        $missionLog->setCreatedBy($mission->getUser()->getId());
        $missionLog->setMission($mission);
        $missionLog->setIsActive(0);
        $missionLog->setBriefFiles($request->get('document'));

        $mission->addMissionLog($missionLog);
        $em->flush();
        $notificationsRepo->insert(null,$mission->getClient(),'edit_mission', 'Mission '.$mission->getId().' has beefed and needs your approval', $missionLog->getId());

        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("download-mission-log-document/{name}",name="download_log_document")
     */
    public function downloadLogDocument($name)
    {
        $response = new BinaryFileResponse('uploads/missions/temp/'.$name);
//        $ext = pathinfo('uploads/missions/temp/'.$name,PATHINFO_EXTENSION);

        $response->headers->set('Content-Type','text/plain');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $name
        );

        return $response;
    }
}
