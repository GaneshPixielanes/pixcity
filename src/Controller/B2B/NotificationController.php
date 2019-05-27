<?php

namespace App\Controller\B2B;


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
     * @Route("/unred", name="unread")
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
}
