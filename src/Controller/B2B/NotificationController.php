<?php

namespace App\Controller\B2B;


use App\Repository\MissionLogRepository;
use App\Repository\MissionPaymentRepository;
use App\Repository\MissionRepository;
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
    public function acceptEditedMission(Request $request,NotificationsRepository $notificationsRepository,MissionLogRepository $missionLogRepository){

        $em = $this->getDoctrine()->getManager();
        $notification = $notificationsRepository->find($request->get('id'));
        $notification->setUnread(0);

        $em->persist($notification);


        $missionlog = $missionLogRepository->find($notification->getNotifyBy());

        $missions = $missionLogRepository->findBy(['mission' => $missionlog->getMission()]);

        foreach ($missions as $key => $mission){
            $mission->setIsActive(0);
            $em->persist($mission);
        }

        $missionlog->setIsActive(1);

        $adjustment = $missionlog->getUserBasePrice() - $missionlog->getMission()->getMissionBasePrice();

        $missionlog->getMission()->setLog($missionlog);
        $missionlog->getMission()->setMissionAgreedClient(1);
        $missionlog->getMission()->getUserMissionPayment()->setAdjustment($adjustment);

        $em->persist($missionlog);
        $em->flush();

        #Send notification
        $notificationsRepository->insert($missionlog->getMission()->getUser(),null,'mission_accepted_edit', $missionlog->getMission()->getClient()->getFirstName().' has accepted your new price of mission '.$missionlog->getMission()->getTitle().' of amount '.$missionlog->getUserBasePrice(),0);


        return JsonResponse::create(['success' => true]);

    }
}
