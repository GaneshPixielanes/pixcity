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
 */
class TransactionController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(UserMissionRepository $missionRepo, NotificationsRepository $notificationsRepo)
    {

        $missions['terminated'] = $missionRepo->findBy(['status' => MissionStatus::TERMINATED, 'client' => $this->getUser()],[]);

        return $this->render('b2b/client/transaction/index.html.twig',[
            'missions' => $missions,
            'notifications' => $notificationsRepo->findBy(['client' => $this->getUser(), 'unread' => 1])
        ]);

    }
}
