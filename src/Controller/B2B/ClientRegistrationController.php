<?php

namespace App\Controller\B2B;

use App\Entity\Client;
use App\Form\B2B\ClientType;
use App\Service\FileUploader;
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
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader)
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

            return $this->render('b2b/client/index.html.twig');
        }
        return $this->render('b2b/client_registration/index.html.twig', [
            'controller_name' => 'ClientRegistrationController',
            'form' => $form->createView()
        ]);
    }
}
