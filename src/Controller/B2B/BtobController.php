<?php

namespace App\Controller\B2B;

use App\Repository\NotificationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/community-manager/", name="b2b_community_manager_")
 * @Security("has_role('ROLE_CM')")
 */
class BtobController extends AbstractController{

    /**
     * @Route("profile", name="index")
     */
    public function index(NotificationsRepository $notificationsRepository)
    {
        $notifications = $notificationsRepository->findBy(['user'=>$this->getUser(), 'unread' => 1],['id' => 'DESC']);

        return $this->render('b2b/index.html.twig',[
            'notifications' => $notifications
        ]);
    }

}