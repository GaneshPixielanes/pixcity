<?php

namespace App\Controller\Admin\Pages;

use App\Constant\ViewMode;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/client", name="admin_client_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class ClientController extends AbstractController
{
    /**
     * @Route("", name="index")
     * @Method({"GET"})
     */
    public function index(ClientRepository $clientRepository,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                return $this->render('admin/b2b/client/index.html.twig', [
                    'clients' => $clientRepository->findBy(['deleted'=>null]),
                ]);
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('admin_client_index');
        }

        return $this->render('admin/b2b/client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Client $client): Response
    {
        return $this->render('admin/b2b/client/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $client, UserPasswordEncoderInterface $passwordEncoder,AuthorizationCheckerInterface $authChecker): Response
    {

        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $form = $this->createForm(ClientType::class, $client);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    if($client->getPlainPassword()) {
                        $password = $passwordEncoder->encodePassword($client, $client->getPlainPassword());
                        $client->setPassword($password);
                    }
                    $uploadedFile = $form['profilePhoto']->getData();

                    $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                    if ($uploadedFile) {

                        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                        $uploadedFile->move(
                            $destination,
                            $newFilename
                        );


                        // instead of its contents
                        $client->setProfilePhoto($newFilename);
                    }
                    $this->getDoctrine()->getManager()->flush();

                    return $this->redirectToRoute('admin_client_index', [
                        'id' => $client->getId(),
                    ]);
                }

                return $this->render('admin/b2b/client/edit.html.twig', [
                    'client' => $client,
                    'form' => $form->createView(),
                ]);
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Client $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
           // $entityManager->remove($client);
            $clients = $entityManager->getRepository(Client::class)->find($client->getId());
            $clients->setDeleted(1);
            $clients->setDeletedAt(new \DateTime());
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_client_index');
    }
}
