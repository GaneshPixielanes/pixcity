<?php

namespace App\Controller\B2B\Client;

use App\Constant\MissionStatus;
use App\Repository\MissionRepository;
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
    public function index(UserMissionRepository $missionRepo)
    {

        $missions['terminated'] = $missionRepo->findBy(['status' => MissionStatus::TERMINATED, 'client' => $this->getUser()],[]);

        return $this->render('b2b/client/transaction/index.html.twig',[
            'missions' => $missions
        ]);

    }
}
