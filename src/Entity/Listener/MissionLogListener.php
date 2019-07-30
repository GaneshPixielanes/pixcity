<?php

namespace App\Entity\Listener;

use App\Constant\MissionStatus;
use App\Entity\MissionLog;
use App\Entity\UserMission;
use App\Service\Mailer;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\Events;


class MissionLogListener{

    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function preFlush(MissionLog $missionLog, PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        $missionLogBeforeUpdate = $uow->getOriginalEntityData($missionLog);

        if($missionLog->getIsActive() == 0 && empty($missionLogBeforeUpdate))
        {
            /* A new mission log has been added/mission is edited */

            /* Mail sent to CM */
            $this->mailer->send($missionLog->getMission()->getUser()->getEmail(),
                'MISSION MODIFIEE',
                'emails/b2b/mission-edit-request-cm.html.twig',
                [
                    'mission' => $missionLog->getMission()
                ],null,'services@pix.city');

            /* Mail sent to Client */
            $this->mailer->send($missionLog->getMission()->getClient()->getEmail(),
                'VALIDATION D\'UNE MISSION MODIFIEE',
                'emails/b2b/mission-edit-request-client.html.twig',
                [
                    'mission' => $missionLog->getMission()
                ],null,'services@pix.city');

        }
        /* CM approved the change */
        if(!empty($missionLogBeforeUpdate) && $missionLog->getIsActive() == 1)
        {
            /* Mail sent to CM */
            $this->mailer->send($missionLog->getMission()->getUser()->getEmail(),
                'EDITION DE MISSION VALIDEE',
                'emails/b2b/mission-edit-accept-cm.html.twig',
                [
                    'mission' => $missionLog->getMission()
                ],null,'services@pix.city');

            /* Mail sent to Client */
            $this->mailer->send($missionLog->getMission()->getClient()->getEmail(),
                'MISSION MODIFIEE',
                'emails/b2b/mission-edit-accept-client.html.twig',
                [
                    'mission' => $missionLog->getMission()
                ],null,'services@pix.city');

        }


    }
}