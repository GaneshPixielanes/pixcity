<?php

namespace App\Controller\B2B\Client;

use App\Constant\MissionStatus;
use App\Repository\UserMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/client/invoice/", name="b2b_client_invoice_")
 */
class InvoiceController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function index(UserMissionRepository $missionRepo)
    {
        $missions = $missionRepo->findBy([
            'client' => $this->getUser(),
            'status' => MissionStatus::TERMINATED
        ]);

        return $this->render('b2b/client/invoice/index.html.twig', [
            'missions' => $missions,
        ]);
    }

    /**
     * @Route("preview/{id}",name="preview")
     */
    public function preview($id, UserMissionRepository $missionRepo)
    {
        $user = $this->getUser();

        $mission = $missionRepo->findBy([
            'client' => $user,
            'id' => $id,
            'status' => MissionStatus::TERMINATED
        ]);

        if(empty($mission))
        {
            return $this->redirectToRoute('b2b_client_invoice_list');
        }

        return $this->render('b2b/shared/_preview.html.twig',[
            'mission' => $mission[0]
        ]);
    }
}
