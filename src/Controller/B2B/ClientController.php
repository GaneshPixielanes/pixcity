<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Repository\UserMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Route("/client/", name="b2b_client_main_")
 * @Security("has_role('ROLE_USER')")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("",name="profile")
     */
    public function profile(UserMissionRepository $missionRepo)
    {
        $mission = $missionRepo->findMissionForClient($this->getUser(), MissionStatus::ONGOING);
    }
    /**
     * @Route("index", name="index")
     */
    public function index()
    {

        $notifications = $this->getUser()->getNotifications();

        return $this->render('b2b/client/index.html.twig',['notifications' => $notifications]);
//        return $this->redirect('/client/search');
    }



}
