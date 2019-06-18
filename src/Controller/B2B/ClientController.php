<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Form\B2B\ClientType;
use App\Repository\ClientMissionProposalRepository;
use App\Repository\MissionRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/client/", name="b2b_client_main_")
 * @Security("has_role('ROLE_USER')")
 */
class ClientController extends Controller
{
    /**
     * @Route("profile",name="profile")
     */
    public function profile(Request $request, FileUploader $fileUploader, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();

        $form = $this->createForm(ClientType::class,$user,['type' => 'edit']);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $file = $request->files->get('files');

            if(!is_null($file))
            {
                $fileName = $fileUploader->upload($file, 'clients'.'/'.$user->getId().'/', true);
                $user->setProfilePhoto($fileName);
            }
            if(trim($user->getPlainPassword()) != '')
            {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }


            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect('/client/profile');
        }

        return $this->render('b2b/client/profile.html.twig',[
           'user' => $user,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("index", name="index")
     */
    public function index(UserMissionRepository $missionRepo, NotificationsRepository $notificationRepo, ClientMissionProposalRepository $proposalRepo)
    {
        // Get client notifications
        $notifications = $notificationRepo->findBy(['client'=>$this->getUser(), 'unread' => 1],['id' => 'DESC']);

        //Get missions
        $missions = $missionRepo->findMissionForClient($this->getUser(), MissionStatus::ONGOING);
        //Get proposals
        $proposals = $proposalRepo->findBy(['client' => $this->getUser()],['id'=>'DESC'],8);

        $mymissions['ongoing'] = $missionRepo->findOngoingMissions($this->getUser(),'client');
        $mymissions['cancelled'] = $missionRepo->findBy(['status' => MissionStatus::CANCELLED, 'client' => $this->getUser()],[]);
        $mymissions['terminated'] = $missionRepo->findBy(['status' => MissionStatus::TERMINATED, 'client' => $this->getUser()],[]);

        $missions_notification = $missionRepo->findBy(['missionAgreedClient' => null, 'client' => $this->getUser()]);

        return $this->render('b2b/client/index.html.twig',[
            'notifications' => $notifications,
            'missions' => $missions,
            'proposals' => $proposals,
            'mymissions' => $mymissions,
            'missions_notification' => $missions_notification
        ]);

    }


    /**
     * @Route("preview-mission", name="preview_mission")
     */
    public function previewMission(Request $request,UserMissionRepository $missionRepository){


        $mission = $missionRepository->find($request->get('id'));

        return $this->render('b2b/client/mission/load-mission-preview.html.twig',[
            'mission' => $mission
        ]);

    }

    /**
     * @Route("preview-payment", name="preview_payment")
     */
    public function previewPayment(Request $request,UserMissionRepository $missionRepository){

//        $mission = $missionRepository->find($request->get('id'));
        $mission = $missionRepository->findBy(['missionAgreedClient' => null, 'id' => $request->get('id')]);

        return $this->render('b2b/client/mission/load-payment-preview.html.twig',[
            'mission' => $mission[0]
        ]);

    }



}
