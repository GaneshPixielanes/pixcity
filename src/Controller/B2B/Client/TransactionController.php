<?php

namespace App\Controller\B2B\Client;

use App\Constant\MissionStatus;
use App\Entity\Option;
use App\Entity\Page;
use App\Repository\MissionRecurringPriceLogRepository;
use App\Repository\MissionRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/client/factures", name="client_transaction_")
 */
class TransactionController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(UserMissionRepository $missionRepo, NotificationsRepository $notificationsRepo,MissionRecurringPriceLogRepository $missionRecurringPriceLogRepository)
    {
        $missions = $missionRepo->findBy([
            'client' => $this->getUser(),
        ]);

        $missions_ongoing = $missionRepo->findBy([
            'client' => $this->getUser(),
            'status' => MissionStatus::ONGOING
        ]);

        $options = $this->getDoctrine()->getRepository(Option::class);

        $tax = $options->findOneBy(['slug' => 'tax']);$margin = $options->findOneBy(['slug' => 'margin']);

        #SEO
        $page = new Page();
        $page->setMetaTitle("Pix.city Services : liste des factures");
        $page->setMetaDescription("Retrouvez dans cet espace toutes les factures de vos missions avec vos city-makers");


        return $this->render('b2b/client/transaction/index.html.twig',[
            'missions' => $missions,
            'missions_ongoing' => $missions_ongoing,
            'notifications' => $notificationsRepo->findBy(['client' => $this->getUser(), 'unread' => 1]),
            'page' => $page,
            'tax' => $tax->getValue(),
            'margin' => $margin->getValue()
        ]);

    }
}
