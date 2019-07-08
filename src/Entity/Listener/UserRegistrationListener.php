<?php

namespace App\Entity\Listener;

use App\Entity\User;
use App\Entity\UserInstagramDetailsApi;
use App\Repository\NotificationsRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;

class UserRegistrationListener
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    public function  preFlush(User $user, PreFlushEventArgs $event, NotificationsRepository $notificationsRepo)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        $userBeforeUpdate = $uow->getOriginalEntityData($user);

        if(!empty($user) && !empty($userBeforeUpdate) && $user->getB2bCmApproval() != $userBeforeUpdate['b2b_cm_approval'])
        {
            // User has been approved by Admin to become B2B
            if($user['b2b_cm_approval'] == 1)
            {
//                $message = '';
//                $notificationsRepo->insert($user,null,'b2b_cm_approved',$message,$user->getId());
                $this->mailer->send($user->getEmail(),
                    'Votre inscription est validée!',
                    'emails/b2b/b2b-cm-approved.html.twig',
                    [
                        'user'=> $user
                    ]);

            }

            // User has been denied by Admin to become B2B
            if($user['b2b_cm_approval'] == 0)
            {
                $this->mailer->send($user->getEmail(),
                    'Votre inscription est validée!',
                    'emails/b2b/b2b-cm-declined.html.twig',
                    [
                        'user'=> $user
                    ]);
            }
        }
    }

}