<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Page;
use App\Form\Admin\PageType;
use App\Form\Admin\StaticPagesType;
use App\Repository\PageRepository;
use App\Repository\StaticPagesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/pages", name="admin_pages_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class PagesController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(PageRepository $pages)
    {
        $list = $pages->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/pages/index.html.twig', ['list' => $list]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if($page->getSlug() === "") $page->setSlug(null);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_pages_list');
        }


        return $this->render('admin/pages/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Page $page)
    {
        $form = $this->createForm(PageType::class, $page, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($page->getSlug() === "") $page->setSlug(null);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');
            return $this->redirectToRoute('admin_pages_list');
        }

        return $this->render('admin/pages/form.html.twig', [
            'item' => $page,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/delete", name="delete")
     * @Method("POST")
     */
    public function delete(Request $request, Page $page)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_pages_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($page);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_pages_list');
    }

    /**
     * @Route("/voyager",name="voyager")
     * @Method("GET")
     */
    public function voyager(StaticPagesRepository $staticPagesRepository)
    {
        $list = $staticPagesRepository->findAll();

        return $this->render('admin/pages/static/index.html.twig', ['list' => $list]);
    }

    /**
     * @Route("/static-page/edit/{id}",name="static_page_edit")
     */
    public function staticPagesEdit(Request $request, StaticPagesRepository $staticPagesRepository)
    {
        $page = $staticPagesRepository->find($request->get('id'));
        $attributes = json_decode($page->getAttributes(),true);

        $form = $this->createForm(StaticPagesType::class, $page);

        $form->handleRequest($request);
//        dd($form->isValid());
        if($form->isSubmitted())
        {
//            dd(json_encode($request->get('attributes')));
            $entityManager = $this->getDoctrine()->getManager();

            $page->setAttributes(json_encode($request->get('attributes'),true));
            $entityManager->persist($page);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_pages_voyager');
        }
        return $this->render('admin/pages/static/form.html.twig',['form' => $form->createView(), 'page'=>$page, 'attributes' =>     $attributes]);
    }
}