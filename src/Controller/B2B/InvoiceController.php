<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Repository\MissionRepository;
use App\Repository\NotificationsRepository;
use App\Repository\OptionRepository;
use App\Repository\UserMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/community-manager/invoice/", name="b2b_community_manager_invoice_")
 */
class InvoiceController extends AbstractController
{
    /**
     * @Route("list", name="list")
     */
    public function index(UserMissionRepository $missionRepo, OptionRepository $optionsRepo, NotificationsRepository $notificationsRepo)
    {

        if($this->getUser()->getB2bCmApproval() != 1)
        {
            return $this->redirectToRoute('front_homepage_index');
        }

        $missions = $missionRepo->findBy([
           'user' => $this->getUser(),
           'status' => MissionStatus::TERMINATED
        ]);

        return $this->render('b2b/invoice/index.html.twig', [
            'missions' => $missions,
            'tax' => $optionsRepo->findOneBy(['slug' => 'margin']),
            'notifications' => $notificationsRepo->findBy([
                'unread' => 1,
                'user' => $this->getUser()
            ])
        ]);
    }

    /**
     * @Route("generate/{id}", name="generate")
     */
        public function generate($id, UserMissionRepository $missionRepo)
        {
            if($this->getUser()->getB2bCmApproval() != 1)
            {
                return $this->redirectToRoute('front_homepage_index');
            }

        $user = $this->getUser();

        $mission = $missionRepo->findBy([
            'user' => $user,
            'id' => $id,
            'status' => MissionStatus::TERMINATED
        ]);

        if(empty($mission))
        {
            return $this->redirectToRoute('b2b_community_manager_invoice_list');
        }


        return $this->render('b2b/invoice/preview.html.twig',[
            'mission' => $mission[0]
        ]);

    }

    /**
     * @Route("preview/{id}",name="preview")
     */
    public function preview($id, UserMissionRepository $missionRepo)
    {
        $user = $this->getUser();
        $mission = $missionRepo->findOneBy([
            'user' => $user,
            'id' => $id,
            'status' => MissionStatus::TERMINATED
        ]);
        $fileName = $missionRepo->createSlug($mission->getTitle())."-client.pdf";

        return new JsonResponse(['url' => '/invoices/'.$mission->getId().'/'.$fileName]);
//
//        if(empty($mission))
//        {
//            return $this->redirectToRoute('b2b_community_manager_invoice_list');
//        }
//
//        return $this->render('b2b/shared/_preview.html.twig',[
//            'mission' => $mission[0]
//        ]);
    }
}
