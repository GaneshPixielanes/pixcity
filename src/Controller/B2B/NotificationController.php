<?php

namespace App\Controller\B2B;


use App\Repository\MissionLogRepository;
use App\Repository\MissionPaymentRepository;
use App\Repository\NotificationsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/notification", name="b2b_notification_")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/unread", name="unread")
     */
    public function unread(Request $request,NotificationsRepository $notificationsRepository)
    {

        $em = $this->getDoctrine()->getManager();

        $notification = $notificationsRepository->find($request->get('id'));
        $notification->setUnread(0);

        $em->persist($notification);
        $em->flush();


        return JsonResponse::create(['success' => true]);
    }

    /**
     * @Route("accept-edited-mission", name="accept_edited_mission")
     */
    public function acceptEditedMission(Request $request,NotificationsRepository $notificationsRepository,MissionLogRepository $missionLogRepository,MissionPaymentRepository $missionPaymentRepository){

        $em = $this->getDoctrine()->getManager();
        $notification = $notificationsRepository->find($request->get('id'));
        $notification->setUnread(1);

        $em->persist($notification);
        $em->flush();

        $missionlog = $missionLogRepository->find($notification->getNotifyBy());
        $missionlog->getIsActive(1);
        $em->persist($missionlog);
        $em->flush();

        $missionPayment = $missionPaymentRepository->find($missionlog->getMission()->getId());

        $cityMakerType = $missionPayment->getMission()->getUser()->getPixie()->getBilling()->getstatus();


        $result = $missionPaymentRepository->getPrices($missionlog->getUserBasePrice(),25,20,$cityMakerType);


        $missionPayment->setUserBasePrice($result['cm_price']);
        $missionPayment->setCmTax($result['cm_tax']);
        $missionPayment->setCmTotal($result['cm_total']);
        $missionPayment->setClientPrice($result['client_price']);
        $missionPayment->setClientTax($result['client_tax']);
        $missionPayment->setClientTotal($result['client_total']);
        $missionPayment->setPcsPrice($result['pcs_price']);
        $missionPayment->setPcsTax($result['pcs_tax']);
        $missionPayment->setPcsTotal($result['pcs_total']);

        $em->persist($missionPayment);
        $em->flush();

        return JsonResponse::create(['success' => false]);

    }
}
