<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="front_campaign")
 */
class CampaignController extends AbstractController
{
    /**
     * @Route("/front/campaign", name="front_campaign")
     */
    public function index()
    {
        return $this->render('front/campaign/index.html.twig', [
            'controller_name' => 'CampaignController',
        ]);
    }

    /**
     * @Route("/devenez-city-maker",name="pixie_campaign")
     */
    public function pixie()
    {
        return $this->render('campaign/pixie/index.html.twig');
    }
}
