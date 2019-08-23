<?php

namespace App\Controller\Admin\Pages;

use App\Constant\ViewMode;
use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/admin/blog/post", name="admin_blog_")
 * @Security("has_role('ROLE_ADMIN')")
 */
class BlogPostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(BlogPostRepository $blogPostRepository,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                return $this->render('admin/b2b/blog_post/index.html.twig', [
                    'blog_posts' => $blogPostRepository->findBy(['deleted'=>null],['id'=>'DESC']),
                ]);
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $blogPost = new BlogPost();
                $form = $this->createForm(BlogPostType::class, $blogPost);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $slugStrChange = str_replace(' ','-',$blogPost->getSlug());
                    $blogPost->setSlug($slugStrChange);
                    $blogPost->setCreatedBy($this->getUser());
                    $uploadedFile = $form['bannerImage']->getData();
                    if ($uploadedFile) {
                        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/blog_image';
                        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                        $uploadedFile->move(
                            $destination,
                            $newFilename
                        );
                        $blogPost->setBannerImage($newFilename);
                    }
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($blogPost);
                    $entityManager->flush();

                    return $this->redirectToRoute('admin_blog_post_index');
                }

                return $this->render('admin/b2b/blog_post/new.html.twig', [
                    'blog_post' => $blogPost,
                    'form' => $form->createView(),
                ]);
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(BlogPost $blogPost,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {

                return $this->render('admin/b2b/blog_post/show.html.twig', [
                    'blog_post' => $blogPost,
                ]);
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BlogPost $blogPost,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {

                $form = $this->createForm(BlogPostType::class, $blogPost);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $slugStrChange = str_replace(' ','-',$blogPost->getSlug());
                    $blogPost->setSlug($slugStrChange);
                    $blogPost->setCreatedBy($this->getUser());
                    $uploadedFile = $form['bannerImage']->getData();
                    if ($uploadedFile) {
                        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/blog_image';
                        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                        $uploadedFile->move(
                            $destination,
                            $newFilename
                        );
                        $blogPost->setBannerImage($newFilename);
                    }
                    $this->getDoctrine()->getManager()->flush();

                    return $this->redirectToRoute('admin_blog_post_index');
                }

                return $this->render('admin/b2b/blog_post/edit.html.twig', [
                    'blog_post' => $blogPost,
                    'form' => $form->createView(),
                ]);
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BlogPost $blogPost,AuthorizationCheckerInterface $authChecker): Response
    {

        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {

                if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
                    $entityManager = $this->getDoctrine()->getManager();
                    //$entityManager->remove($blogPost);
                    $blogPost->setDeleted(1);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('admin_blog_post_index');
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }
}
