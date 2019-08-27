<?php

namespace App\Controller\Front;

use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog", name="front_blog_")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     */
    public function index(BlogPostRepository $blogPostRepository,Request $request)
    {
        $limit = 12;
        $page = is_null($request->get('page'))?1:(int)$request->get('page');

        $filters = [];
        $filters = ['page' => $page];
        $blogPosts = $blogPostRepository->blogPaginations($limit, $page);
        $filters['cm_count'] = $blogPostRepository->blogCount($limit, $page);
        $filters['total_pages'] = ceil($filters['cm_count']/$limit);

        return $this->render('front/blog/index.html.twig', [
            'blogHeaderByPosition' => $blogPostRepository->bannerHeader(),
            'blogAll' => $blogPostRepository->findBy(array('postStatus'=>1,'position'=>0),['id'=>'DESC'],4),
            'blogPosts' => $blogPosts,
            'filters' => $filters
        ]);
    }
    /**
     * @Route("/{slug}/{id}", name="single")
     */
    public function single(BlogPostRepository $blogPostRepository,Request $request)
    {

        $blogSingle = $blogPostRepository->findBy(['id'=>$request->attributes->get("id")]);

        return $this->render('front/blog/blogPost.html.twig', [
            'blogSingle' => $blogSingle,
        ]);
    }
}
