<?php

namespace App\Controller\B2B\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/client/pack", name="b2b_front_pack_")
 */
class PackController extends AbstractController
{
    /**
     * @Route("/{id}", name="view")
     */
    public function index($id)
    {
        return $this->render('b2_b/front/pack/index.html.twig', [
            'controller_name' => 'PackController',
        ]);
    }
}
