<?php

namespace App\Controller\B2B;

use App\Constant\SessionName;
use App\Entity\Client;
use App\Form\B2B\ClientType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use GuzzleHttp\ClientInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Validator\Constraints\Json;

class LoginController extends Controller
{

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/client/login",name="b2b_client_login", methods={"POST"})
     */
    public function clientlogin(Request $request)
    {
        $user = $this->getUser();

        return $this->json([
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ]
        );

    }
    /**
     * @Route("/client/login-old", name="b2b_client_login_old")
     */
    public function loginOld(AuthenticationUtils $authenticationUtils,Request $request, ClientRepository $clientRepository)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('b2b/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'login_type' => 'client']);
    }
    /**
     * @Route("/client/login/process", name="b2b_client_login_process")
     */
    public function processLogin(Request $request, ClientRepository $clientRepository)
    {
        if($request->isMethod('POST'))
        {
            $client = $clientRepository->findOneBy([
                'username' => $request->get('_username'),
                'password' => $request->get('_password')
            ]);

            if(is_null($client))
            {
                return new JsonResponse(['success' =>false,'message'=>'Identifiants invalides.']);
            }
            $this->loginClient($request, $client);
            return new JsonResponse(['success'=> true,'redirectTo' => $this->generateUrl('b2b_client_main_profile')]);
        }

        return new JsonReponse(['success' => false, 'message' => 'Invalid format']);
    }


    /**
     * @Route("client/signout", name="b2b_client_signout")
     */
    public function manualLougout()
    {
        $this->session->remove('login_by');

        return $this->redirect('/client/logout');
    }

    /**
     * @Route("/connect/linkedin",name="connect_linkedin_start")
     */
    public function connectLinkedinCheck(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('linkedin')
            ->redirect(['r_liteprofile','r_emailaddress']);
    }

    /**
     * @Route("/connect/facebook",name="connect_facebook_start")
     */
    public function connectFacebookCheck(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('facebook_client')
            ->redirect(['email','public_profile']);
    }


    /**
     * @Route("/connect/google",name="connect_google_start")
     */
    public function connectGoogleCheck(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('google_client')->redirect(['profile','email']);

    }

    /**
     * @Route("/connect/google/action", name="connect_google_action")
     */
    public function connectGoogleAction(Request $request,
                                        ClientRegistry $clientRegistry,
                                        ClientRepository $clientRepository,
                                        UserRepository $userRepository
    )
    {
        $client = $clientRegistry->getClient('google_client');

        try{
            $user = $client->fetchUser();

            $id = $user->getId();
            $email = $user->getEmail();
            $tempUser = $userRepository->findOneBy(['email' => $email]);

            if(!is_null($tempUser))
            {
                $this->loginUser($request, $tempUser);
            }
            $data = $user->toArray();
            $data['localizedFirstName'] = $data['name']['givenName'];
            $data['localizedLastName'] = $data['name']['familyName'];
            $data['email'] = $data['emails'];
            if(is_null($clientRepository->findOneBy(['email'=>$email])))
            {
                $form = $this->addClient($email, $id, $data, $type = 'google');
                return $this->render('b2b/client_registration/index.html.twig',
                    [
                        'form' => $form->createView(),
                        'type' => 'social',
                        'socialId' => $id
                    ]);
            }

            $client = $clientRepository->findOneBy(['googleId'=>$id]);
            $this->loginClient($request,$client);

            return $this->redirect('/');

        }catch (IdentityProviderException $e)
        {
            dd($e);
        }
    }

    /**
     * @Route("/connect/facebook/action", name="connect_facebook_action")
     */
    public function connectFacebookAction(Request $request, ClientRegistry $clientRegistry, ClientRepository $clientRepository,UserRepository $userRepository)
    {
        $client = $clientRegistry->getClient('facebook_client');

        try{
            $user = $client->fetchUser();

            $id = $user->getId();
            $email = $user->getEmail();
            $data = $user->toArray();
            $tempUser = $userRepository->findOneBy(['email' => $email]);

            if(!is_null($tempUser))
            {
                $this->loginUser($request, $tempUser);
            }
            $data['localizedFirstName'] = $data['first_name'];
            $data['localizedLastName'] = $data['last_name'];
            $data['email'] = $email;
            if(is_null($clientRepository->findOneBy(['email'=>$email])))
            {
                $form = $this->addClient($email, $id, $data, $type = 'facebook');
                return $this->render('b2b/client_registration/index.html.twig',
                    [
                        'form' => $form->createView(),
                        'type' => 'social',
                        'socialId' => $id
                    ]);
            }

            $client = $clientRepository->findOneBy(['facebookId'=>$id]);
            $this->loginClient($request,$client);

            return $this->redirect('/');

        }catch (IdentityProviderException $e)
        {
            dd($e);
        }
    }

    /**
     * @Route("/connect/linkedin/action", name="connect_linkedin_check")
     */
    public function  connectLinkedinAction(Request $request, ClientRegistry $clientRegistry, ClientRepository $clientRepository,UserRepository $userRepository)
    {

        $client = $clientRegistry->getClient('linkedin');

        try{
            $user = $client->fetchUser();

            $email = $user->getEmail();
            $tempUser = $userRepository->findOneBy(['email' => $email]);

            if(!is_null($tempUser))
            {
                $this->loginUser($request, $tempUser);
            }
            $id = $user->getId();
            $data = $user->toArray();
            if(is_null($clientRepository->findOneBy(['email'=>$email])))
            {
                $form = $this->addClient($email, $id, $data);
                return $this->render('b2b/client_registration/index.html.twig',
                    [
                        'form' => $form->createView(),
                        'type' => 'social',
                        'socialId' => $id
                    ]);
            }
            else
            {
                $client = $clientRepository->findOneBy(['linkedinId'=>$id]);
                $this->loginClient($request, $client);

                return $this->redirect('/');
            }


        }catch (IdentityProviderException $e)
        {
            dd($e);
        }
    }

    public function loginClient(Request $request,$client)
    {

        $this->session->set('login_by',['type' => 'login_client','entity' => $client]);

        $token = new UsernamePasswordToken($client, null, 'main', $client->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
        $this->container->get('session')->set('_security_client_area', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
    }

    public function addClient($email, $id, $data, $type = 'linkedin')
    {
        $client = new Client();

        switch($type)
        {
            case 'facebook': $client->setFacebookId($id); break;
            case 'google': $client->setGoogleId($id); break;
            case 'linkedin': $client->setLinkedinId($id); break;
        }
        $client->setEmail($email);
        $client->setFirstName($data['localizedFirstName']);
        $client->setLastName($data['localizedLastName']);
        $client->setPlainPassword(md5(random_bytes(8)));

        $form = $this->createForm(ClientType::class, $client);
        return $form;
    }

    private function loginUser(Request $request, $user)
    {
        $roles = $user->getRoles();

        $this->session->set('login_by',['type' => 'login_client','entity' => $user]);

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
        $this->container->get('session')->set('_security_main_area', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        if(in_array('ROLE_PIXIE',$roles))
        {
            return $this->redirectToRoute('front_pixie_account_homepage');
        }
        else
        {
            return $this->redirectToRoute('front_homepage_index');
        }
    }
}
