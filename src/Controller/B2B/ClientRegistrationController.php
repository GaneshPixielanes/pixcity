<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Constant\SessionName;
use App\Entity\Client;
use App\Entity\Option;
use App\Entity\UserMission;
use App\Form\B2B\ClientType;
use App\Repository\ClientMissionProposalRepository;
use App\Repository\ClientRepository;
use App\Repository\NotificationsRepository;
use App\Repository\OptionRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\Mailer;
use App\Service\MangoPayService;
use MangoPay\UserNatural;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


/**
 * @Route("/client/", name="b2b_client_auth_")
 */
class ClientRegistrationController extends AbstractController
{
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("register", name="register")
     */
    public function index(Request $request,
                          ClientMissionProposalRepository $proposalRepo,
                          UserMissionRepository $missionRepo,
                          NotificationsRepository $notificationRepo,
                          UserPasswordEncoderInterface $passwordEncoder,
                          FileUploader $fileUploader,
                          Filesystem $filesystem,
                          OptionRepository $optionRepository,
                          Mailer $mailer,
                          MangoPayService $mangoPayService)
    {

        $session = $request->getSession();


        if($session->has('login_by')){
            return $this->redirect('/');
        }

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

            $file = $request->files->get('file-avatar');
            $fileUploader->upload($file,'clients', false);

            if(!is_null($file))
            {
                // Update client with the name of the profile pic
                $client->setProfilePhoto($file->getClientOriginalName());
            }
            $client->setDeleted(null);

         /*   // Create a mango pay user
            $mangoUser = new UserNatural();

            $mangoUser->PersonType = "NATURAL";
            $mangoUser->FirstName = $client->getFirstName();
            $mangoUser->LastName = $client->getLastName();
            $mangoUser->Birthday = 1409735187;
            $mangoUser->Nationality = "FR";
            $mangoUser->CountryOfResidence = "FR";
            $mangoUser->Email = $client->getEmail();
            $mangoUser = $mangoPayService->createUser($mangoUser);
            //Create a wallet
            $wallet = $mangoPayService->getWallet($mangoUser->Id);

            $client->getClientInfo()->setMangopayUserId($mangoUser->Id);
            $client->getClientInfo()->setMangopayWalletId($wallet->Id);
            $client->getClientInfo()->setMangopayCreatedAt(new \DateTime());
*/
            $em->persist($client);
            $em->flush();

//            $file->m
            // Set the user session
            $this->session->set('login_by',['type' => 'login_client','entity' => $client]);
            // Move profile photo to the right directory
            if($filesystem->exists('uploads/clients/'.$client->getProfilePhoto()) && $client->getProfilePhoto() != ''){
                $filesystem->copy('uploads/clients/'.$client->getProfilePhoto(),'uploads/clients/'.$client->getId().'/'.$client->getProfilePhoto());
            }

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
//
//            $mailer->send($client->getEmail(),'Bienvenue sur Pix.city Services !','emails/b2b/client-register.html.twig',[
//                'client' => $client
//            ]);

            $token = new UsernamePasswordToken($client, null, 'main', $client->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('_security_client_area', serialize($token));

            return $this->render('b2b/client_registration/thanks-you.html.twig');

        }

        $tax = $optionRepository->findBy(['slug' => 'tax']);

        if($this->getUser() == null){

            return $this->render('b2b/client_registration/index.html.twig', [
                'controller_name' => 'ClientRegistrationController',
                'form' => $form->createView(),
                'tax' => $tax[0]
            ]);

        }else{
            return $this->redirect('/client/index');
        }


    }

    /**
     * @Route("redirect-page",name="redirect_page")
     */
    public function redirectPage(){
        $session  = new Session();

        if($session->has('chosen_pack_url')){
          return $this->redirect($session->get('chosen_pack_url'));
        }else{
            return $this->redirectToRoute('b2b_client_main_index');
        }
    }


    /**
     * @Route("upload-logo",name="upload_logo")
     * @Method("POST")
     */
    public function uploadLogo(Request $request,FileUploader $fileUploader)
    {
        $fileName = null;
        foreach ($request->files as $uploadedFile) {
            if($uploadedFile->isValid()) {
                $fileName = $fileUploader->upload($uploadedFile, '/clients', true);
            }
        }

        return new JsonResponse(['file' => $fileName]);
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
