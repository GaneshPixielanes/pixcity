<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Repository\NotificationsRepository;
use App\Repository\OptionRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserPacksRepository;
use App\Repository\UserRepository;
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
    public function index(NotificationsRepository $notificationsRepository,
                          UserPacksRepository $packRepo,
                          UserMissionRepository $missionRepo,
                          OptionRepository $optionRepo,
                            UserRepository $userRepo)
    {

        #Logged in User
        $user = $this->getUser();



        #Unread Notifications of the user
        $notifications = $notificationsRepository->findBy(['user'=>$this->getUser(), 'unread' => 1],['id' => 'DESC']);

        #Packs listed by the user
        $packs = $packRepo->findByUser($user);

        #Missions listed by the user
        $missions = $missionRepo->findBy(['user' => $this->getUser()],['id' => 'DESC']);

        return $this->render('b2b/index.html.twig',[
            'notifications' => $notifications,
            'packs' => $packs,
            'missions' => $missions,
            'tax' =>  $optionRepo->findBy(['slug' => 'margin'])[0]
        ]);
    }

}