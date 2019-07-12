<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Faqs;
use App\Form\FaqsType;
use App\Repository\FaqsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/faqs", name="admin_faqs_")
 * @Security("has_role('ROLE_ADMIN')")
 */
class FaqsController extends AbstractController
{
    /**
     * @Route("", name="index")
     * @Method({"GET"})
     */
    public function index(FaqsRepository $faqsRepository): Response
    {
        return $this->render('admin/b2b/faqs/index.html.twig', [
            'faqs' => $faqsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $faq = new Faqs();
        $form = $this->createForm(FaqsType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($faq);
            $entityManager->flush();

            return $this->redirectToRoute('admin_faqs_index');
        }

        return $this->render('admin/b2b/faqs/new.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Faqs $faq): Response
    {
        return $this->render('admin/b2b/faqs/show.html.twig', [
            'faq' => $faq,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Faqs $faq): Response
    {
        $form = $this->createForm(FaqsType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_faqs_index', [
                'id' => $faq->getId(),
            ]);
        }

        return $this->render('admin/b2b/faqs/edit.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Faqs $faq): Response
    {
        if ($this->isCsrfTokenValid('delete'.$faq->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($faq);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_faqs_index');
    }
}
