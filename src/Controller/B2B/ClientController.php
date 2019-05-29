<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Form\B2B\ClientType;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/client/", name="b2b_client_main_")
 * @Security("has_role('ROLE_USER')")
 */
class ClientController extends AbstractController
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
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

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
    public function index(UserMissionRepository $missionRepo, NotificationsRepository $notificationRepo)
    {
        // Get client notifications
        $notifications = $notificationRepo->findBy(['client'=>$this->getUser(), 'unread' => 1],['id' => 'DESC']);
        //Get missions
        $missions = $missionRepo->findMissionForClient($this->getUser(), MissionStatus::ONGOING);

        return $this->render('b2b/client/index.html.twig',[
            'notifications' => $notifications,
            'missions' => $missions
            ]);
//        return $this->redirect('/client/search');
    }



}
