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
        dd($this->getUser());
        return $this->render('b2_b/client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

}
