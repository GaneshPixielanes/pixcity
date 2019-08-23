<?php

namespace App\Controller\Admin\Pages;

use App\Entity\BlogCategory;
use App\Form\BlogCategoryType;
use App\Repository\BlogCategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/blog/category", name="admin_blog_")
 * @Security("has_role('ROLE_ADMIN')")
 */
class BlogCategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(BlogCategoryRepository $blogCategoryRepository): Response
    {
        return $this->render('admin/b2b/blog_category/index.html.twig', [
            'blog_categories' => $blogCategoryRepository->findBy(['deleted'=>null],['id'=>'DESC']),
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $blogCategory = new BlogCategory();
        $form = $this->createForm(BlogCategoryType::class, $blogCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $blogCategory->setDefinedBy($this->getUser());
            $entityManager->persist($blogCategory);
            $entityManager->flush();

            return $this->redirectToRoute('admin_blog_category_index');
        }

        return $this->render('admin/b2b/blog_category/new.html.twig', [
            'blog_category' => $blogCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(BlogCategory $blogCategory): Response
    {
        return $this->render('admin/b2b/blog_category/show.html.twig', [
            'blog_category' => $blogCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BlogCategory $blogCategory): Response
    {
        $form = $this->createForm(BlogCategoryType::class, $blogCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogCategory->setDefinedBy($this->getUser());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_blog_category_index');
        }

        return $this->render('admin/b2b/blog_category/edit.html.twig', [
            'blog_category' => $blogCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BlogCategory $blogCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->remove($blogCategory);
            $blogCategory->setDeleted(1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_blog_category_index');
    }
}
