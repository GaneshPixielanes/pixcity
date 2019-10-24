<?php

namespace App\Controller\Front;

use App\Entity\Page;
use App\Repository\BlogPostRepository;
use App\Repository\OptionRepository;
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
        #SEO
        $pages = new Page();
        $pages->setMetaTitle("Le blog de Pix.City Services");
        $pages->setMetaDescription("Tout savoir sur la visibilité locale sur internet : conseils, case studies, bonnes pratiques, interviews Focus sur Instagram, Facebook et Google My Business.");

        $limit = 6;
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
            'filters' => $filters,
            'pages'=>$pages
        ]);
    }
    /**
     * @Route("/{slug}/{id}", name="single")
     */
    public function single(BlogPostRepository $blogPostRepository,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postId = $request->attributes->get("id");

        $blogSingle = $blogPostRepository->findBy(['id'=>$request->attributes->get("id"),'postStatus'=>1]);

        $queryPrev = $em->createQuery('SELECT g
                                FROM App:BlogPost AS g
                                WHERE g.postStatus = 1 AND g.deleted IS NULL AND g.id  < ' . $postId . '
                                ORDER BY g.id DESC ');
        $queryPrev->setMaxResults(1);
        $blogSinglePrev = $queryPrev->getResult();

        if(count($blogSinglePrev)){
            $prevNextType['prev'] = "prev";
            $prevNextType['previd'] = $blogSinglePrev;
        }

        $queryNxt = $em->createQuery('SELECT g
                                FROM App:BlogPost AS g
                                WHERE g.postStatus = 1 AND g.deleted IS NULL AND g.id  > ' . $postId . '
                                ORDER BY g.id ASC ');
        $queryNxt->setMaxResults(1);
        $blogSingleNxt = $queryNxt->getResult();

        if(count($blogSingleNxt)){
            $prevNextType['next'] = "next";
            $prevNextType['nextid'] = $blogSingleNxt;
        }

        return $this->render('front/blog/blogPost.html.twig', [
            'blogSingle' => $blogSingle,
            'prevNextType' => $prevNextType,
        ]);
    }
    /**
     * @Route("/blogredirect", name="blogredirect")
     */
    public function blogChange(OptionRepository $optionRepository)
    {
        $blogUrl = $optionRepository->findOneBy(['slug'=>'cm-winning-blog-url']);
        return $this->redirect($blogUrl->getValue());
    }
}
