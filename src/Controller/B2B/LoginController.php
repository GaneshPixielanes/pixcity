<?php

namespace App\Controller\B2B;

use App\Entity\Client;
use App\Form\B2B\ClientType;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/client/login", name="b2b_client_login")
     */
    public function login(AuthenticationUtils $authenticationUtils,Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('b2b/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'login_type' => 'client']);
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
     * @Route("/connect/linkedin/action", name="connect_linkedin_check")
     */
    public function  connectLinkedinAction(Request $request, ClientRegistry $clientRegistry)
    {
        $client = $clientRegistry->getClient('linkedin');

        try{
            $user = $client->fetchUser();

            $email = $user->getEmail();
            $id = $user->getId();
            $data = $user->toArray();

            $client = new Client();

            $client->setEmail($email);
            $client->setFirstName($data['localizedFirstName']);
            $client->setLastName($data['localizedFirstName']);
            $client->setLinkedinId($id);

            $em = $this->getDoctrine()->getManager();

            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('b2b_client_main_profile');
        }catch (IdentityProviderException $e)
        {
            dd($e);
        }
    }
}
