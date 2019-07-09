<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/front/faq", name="front_faq_")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     */
    public function index()
    {
        return $this->render('front/faq/index.html.twig');
    }
}
