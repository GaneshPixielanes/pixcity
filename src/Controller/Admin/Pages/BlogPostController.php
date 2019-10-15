<?php

namespace App\Controller\Admin\Pages;

use App\Constant\ViewMode;
use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use App\Service\FileUploader;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function new(Request $request, AuthorizationCheckerInterface $authChecker, BlogPostRepository $blogPostRepository): Response
    {

        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $entityManager = $this->getDoctrine()->getManager();
                $blogPost = new BlogPost();
                $form = $this->createForm(BlogPostType::class, $blogPost);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $slugStrChange = str_replace(' ','-',$blogPost->getSlug());
                    $blogPost->setSlug($slugStrChange);
                    $blogPost->setCreatedBy($this->getUser());
                    if($blogPost->getPosition() == 1){
                        $blogPostSearch = $blogPostRepository->findBy(array('position'=>1));
                        foreach ($blogPostSearch as $blog){
                            $blog->setPosition(0);
                            $entityManager->persist($blog);
                        }
                    }


                    $entityManager->persist($blogPost);
                    $entityManager->flush();

                    $uploadedFile = $blogPost->getBannerImage();

                    if ($uploadedFile) {
                        $srcPath = 'uploads/blog_images/'.$uploadedFile;
                        $path = 'uploads/blog_images/'.$blogPost->getId().'/';
                        if (!file_exists($path)) {
                            mkdir($path, 0700);
                        }

                        rename($srcPath, 'uploads/blog_images/'.$blogPost->getId().'/' . pathinfo($uploadedFile, PATHINFO_BASENAME));
                    }
                    $headFile = $blogPost->getHeadImage();
                    if ($headFile) {
                        $srcPath = 'uploads/blog_images/'.$headFile;
                        $path = 'uploads/blog_images/'.$blogPost->getId().'/';
                        if (!file_exists($path)) {
                            mkdir($path, 0700);
                        }
                        rename($srcPath, 'uploads/blog_images/'.$blogPost->getId().'/' . pathinfo($headFile, PATHINFO_BASENAME));
                    }

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
    public function edit(Request $request, BlogPost $blogPost,AuthorizationCheckerInterface $authChecker, BlogPostRepository $blogPostRepository): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $entityManager = $this->getDoctrine()->getManager();
                $form = $this->createForm(BlogPostType::class, $blogPost);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $slugStrChange = str_replace(' ','-',$blogPost->getSlug());
                    $blogPost->setSlug($slugStrChange);
                    $blogPost->setCreatedBy($this->getUser());

                    if($blogPost->getPosition() == 1){
                        $blogPostSearch = $blogPostRepository->findBy(array('position'=>1));
                        foreach ($blogPostSearch as $blog){
                            $blog->setPosition(0);
                            $entityManager->persist($blog);
                        }
                    }

                    $uploadedFile = $blogPost->getBannerImage();

                    if ($uploadedFile) {
                        $srcPathBann = 'uploads/blog_images/'.$uploadedFile;
                        $paths = 'uploads/blog_images/'.$blogPost->getId().'/';
                        if (!file_exists($paths)) {
                            mkdir($paths, 0700);
                        }
                        if (file_exists($srcPathBann)) {
                            rename($srcPathBann, 'uploads/blog_images/' . $blogPost->getId() . '/' . pathinfo($uploadedFile, PATHINFO_BASENAME));
                        }
                    }
                    $headFile = $blogPost->getHeadImage();
                    if ($headFile) {
                        $srcPath = 'uploads/blog_images/'.$headFile;
                        $path = 'uploads/blog_images/'.$blogPost->getId().'/';
                        if (!file_exists($path)) {
                            mkdir($path, 0700);
                        }
                        if (file_exists($srcPath)) {
                            rename($srcPath, 'uploads/blog_images/' . $blogPost->getId() . '/' . pathinfo($headFile, PATHINFO_BASENAME));
                        }
                    }
                    $this->getDoctrine()->getManager()->flush();


                    $entityManager->flush();

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
    /**
     * @Route("/upload", name="ajax_upload")
     * @Method({"POST"})
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        //$file = $request->files->get('file');
        //$fileName = $fileUploader->upload($file, 'blog_images/'.$request->get('id'), true);
        $fileName = '';
        foreach ($request->files as $uploadedFile) {
            if($uploadedFile->isValid()) {
                $fileName = $fileUploader->upload($uploadedFile, 'blog_images/', true);
            }
        }


        if($fileName){
            $response = new JsonResponse(['success' => true, 'name' =>$fileName, 'url'=>'/uploads/blog_images/'.$fileName]);
        }
        else{
            $response = new JsonResponse(['error' => true], 400);
        }

        return $response;
    }
}
