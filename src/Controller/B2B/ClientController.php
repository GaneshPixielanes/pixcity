<?php

namespace App\Controller\B2B;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/client/", name="b2b_client_main_")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("index", name="index")
     */
    public function index()
    {
        return $this->render('b2b/client/index.html.twig');
//        return $this->redirect('/client/search');
    }



}
