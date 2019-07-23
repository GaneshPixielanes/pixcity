<?php

namespace App\Controller\B2B\Client;

use App\Constant\MissionStatus;
use App\Repository\MissionRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/client/transaction", name="client_transaction_")
 * @Security("has_role('ROLE_USER')")
 */
class TransactionController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(UserMissionRepository $missionRepo, NotificationsRepository $notificationsRepo)
    {

        $missions_index['terminated'] = $missionRepo->findBy(['status' => MissionStatus::TERMINATED, 'client' => $this->getUser()],[]);
        $missions = $missionRepo->findOngoingMissions($this->getUser(), 'client');
        return $this->render('b2b/client/transaction/index.html.twig',[
            'missions' => $missions,
            'missions_index' => $missions_index,
            'notifications' => $notificationsRepo->findBy(['client' => $this->getUser(), 'unread' => 1])
        ]);

    }
}
