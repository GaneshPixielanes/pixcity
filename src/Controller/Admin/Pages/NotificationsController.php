<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Notifications;
use App\Form\Type\NotificationType;
use App\Repository\NotificationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/notification", name="admin_notification_")
 */
class NotificationsController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(NotificationsRepository $notifications)
    {
        $list = $notifications->findBy([],['createdAt'=>'DESC']);

        return $this->render('admin/pages/notifications/index.html.twig', [
            'controller_name' => 'NotificationsController',
            'list' => $list
        ]);
    }

    /**
     * @Route("/send",name="send")
     */
    public function sendNotification(Request $request,NotificationType $form)
    {
        $notification = new Notifications();
        $form = $this->createForm(NotificationType::class,$notification);
        $user = $this->getUser();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            $entityManager = $this->getDoctrine()->getManager();

            $notification->setSentFrom($user);
            $notification->setCreatedAt(new \DateTime());

            $entityManager->persist($notification);
            $entityManager->flush();

            return $this->redirectToRoute('admin_notification_list');
        }
        return $this->render('admin/pages/notifications/form.html.twig',[
           'form' => $form->createView()
        ]);
    }
}
