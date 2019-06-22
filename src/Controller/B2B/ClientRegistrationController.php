<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Entity\Client;
use App\Form\B2B\ClientType;
use App\Repository\ClientMissionProposalRepository;
use App\Repository\ClientRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/client/", name="b2b_client_auth_")
 */
class ClientRegistrationController extends AbstractController
{
    /**
     * @Route("register", name="register")
     */
    public function index(Request $request,ClientMissionProposalRepository $proposalRepo,UserMissionRepository $missionRepo,NotificationsRepository $notificationRepo,UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $client->setRoles(["ROLE_USER"]);

            // Encode the password

            if($client->getPlainPassword()) {
                $password = $passwordEncoder->encodePassword($client, $client->getPlainPassword());
                $client->setPassword($password);
            }

            $em->persist($client);
            $em->flush();

            // Move profile photo to the right directory
            $file = $request->files->get('files');
            if(!is_null($file))
            {
                $fileName = $fileUploader->upload($file, 'clients'.'/'.$client->getId().'/', true);
                // Update client with the name of the profile pic
                $client->setProfilePhoto($fileName);
            }
            $em->persist($client);
            $em->flush();

            // Get client notifications
            $notifications = $notificationRepo->findBy(['client'=>$this->getUser(), 'unread' => 1],['id' => 'DESC']);

            //Get missions
            $missions = $missionRepo->findMissionForClient($this->getUser(), MissionStatus::ONGOING);

            //Get proposals
            $proposal_unique = [];

            $proposals = $proposalRepo->findBy(['client' => $this->getUser()],['id'=>'DESC'],8);

            foreach ($proposals as $proposal) {
                if(!in_array($proposal->getUser()->getId(),$proposal_unique)){
                    $proposal_unique [$proposal->getId()] = $proposal->getUser()->getId();
                }
            }

            $mymissions['ongoing'] = $missionRepo->findOngoingMissions($this->getUser(),'client');
            $mymissions['cancelled'] = $missionRepo->findBy(['status' => MissionStatus::CANCELLED, 'client' => $this->getUser()],[]);
            $mymissions['terminated'] = $missionRepo->findBy(['status' => MissionStatus::TERMINATED, 'client' => $this->getUser()],[]);

            $missions_notification = $missionRepo->findBy(['client' => $this->getUser()]);

            return $this->render('b2b/client/index.html.twig',[
                'notifications' => $notifications,
                'missions' => $missions,
                'proposals' => $proposals,
                'mymissions' => $mymissions,
                'missions_notification' => $missions_notification,
                'proposal_unique' => $proposal_unique
            ]);
        }
        return $this->render('b2b/client_registration/index.html.twig', [
            'controller_name' => 'ClientRegistrationController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("check-email",name="check_email")
     */
    public function checkEmail(ClientRepository $clientRepository, UserRepository $userRepository, Request $request)
    {
        $email = $request->get('client')['email'];
        $user = $userRepository->findBy(['email' => $email]);
        $client = $clientRepository->findBy(['email' => $email]);

        if(!empty($user) || !empty($client))
        {
            return new JsonResponse(false);
        }

        return new JsonResponse(true);
    }
}
