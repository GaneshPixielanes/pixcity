<?php

namespace App\Controller\Front\Pages;

use App\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/p", name="front_pages_")
 */

class PagesController extends Controller
{

    /**
     * TO REMOVE - FOR wkhtmltopdf test only
     * @Route("/pdf-test", name="pdf_test")
     */
    /*public function pdf_test(Request $request)
    {
        $html = "test";

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'file.pdf'
        );
    }*/

    /**
     * @Route("/{slug}", name="index")
     */
    public function index(
        Request $request,
        PageRepository $pagesRepo
    ){
        $slug = $request->attributes->get('slug');
        // dd(strpos($slug,'pixie')); 
        if(strpos($slug,'pixie'))
        {
            $slug = str_replace('pixie','city-maker',$request->attributes->get('slug'));     
            return $this->redirectToRoute('front_pages_index',['slug' => $slug], 301);       
        }
        $page = $pagesRepo->findOneBySlug($slug);

        if(!$page) throw new NotFoundHttpException('error.not_found');

        return $this->render('front/pages/index.html.twig', [
            'page' => $page,
        ]);
    }

}