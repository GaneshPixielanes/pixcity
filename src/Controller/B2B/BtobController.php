<?php

namespace App\Controller\B2B;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/community-manager/", name="b2b_community_manager_")
 */
class BtobController extends AbstractController{

    /**
     * @Route("profile", name="index")
     */
    public function index()
    {
        return $this->render('b2b/index.html.twig');
    }
}