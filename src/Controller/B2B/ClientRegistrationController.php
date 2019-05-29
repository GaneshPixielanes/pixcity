<?php

namespace App\Controller\B2B;

use App\Entity\Client;
use App\Form\B2B\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder,\Swift_Mailer $mailer)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $client->setRoles(["ROLE_USER"]);

            // Encode the password
            if($client->getPassword()) {
                $password = $passwordEncoder->encodePassword($client, $client->getPassword());
                $client->setPassword($password);
            }
            $em->persist($client);
            $em->flush();

            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('noreply@pix.city')
                ->setTo('admin@pix.city')
                ->setBody(
                    $this->renderView(
                        'b2b/emails/cm_registration.html.twig',
                        ['user' => $user]
                    ),
                    'text/html'
                )
            ;

            $mailer->send($message);


            return $this->render('b2b/client/index.html.twig');
        }
        return $this->render('b2b/client_registration/index.html.twig', [
            'controller_name' => 'ClientRegistrationController',
            'form' => $form->createView()
        ]);
    }
}
