<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserPacksRepository;
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
    public function index(NotificationsRepository $notificationsRepository, UserPacksRepository $packRepo, UserMissionRepository $missionRepo)
    {

        #Logged in User
        $user = $this->getUser();

        #Unread Notifications of the user
        $notifications = $notificationsRepository->findBy(['user'=>$this->getUser(), 'unread' => 1],['id' => 'DESC']);

        #Packs listed by the user
        $packs = $packRepo->findByUser($user);

        #Missions listed by the user
        $missions['ongoing'] = $missionRepo->findOngoingMissions($this->getUser());
        $missions['cancelled'] = $missionRepo->findBy(['status' => MissionStatus::CANCELLED, 'user' => $this->getUser()],[],['id' => 'DESC']);
        $missions['terminated'] = $missionRepo->findBy(['status' => MissionStatus::TERMINATED, 'user' => $this->getUser()],[],['id' => 'DESC']);

        return $this->render('b2b/index.html.twig',[
            'notifications' => $notifications,
            'packs' => $packs,
            'missions' => $missions
        ]);
    }

}