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


class MissionListener{

    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function preFlush(UserMission $mission, PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        $missionBeforeUpdate = $uow->getOriginalEntityData($mission);
        if(!empty($mission) && (!empty($missionBeforeUpdate) && $missionBeforeUpdate['status'] !== $mission->getStatus()))
        {
            if($mission->getStatus() == MissionStatus::ONGOING)
            {

                $this->mailer->send($mission->getUser()->getEmail(),
                    'PRE-PAIEMENT RECU ET DEVIS ACCEPTE',
                    'emails/b2b/mission-create-accept.html.twig',
                    [
                        'mission' => $mission
                    ]);
            }

            if($mission->getStatus() == MissionStatus::CANCEL_REQUEST_INITIATED)
            {
                /* Mail sent to the CM */
                $this->mailer->send($mission->getUser()->getEmail(),
                    "DEMANDE D'ANNULATION MISSION",
                    'emails/b2b/mission-cancel-request-cm.html.twig',
                    [
                        'mission' => $mission
                    ]);

                /* Mail sent to the Client */
                $this->mailer->send($mission->getClient()->getEmail(),
                    "VALIDATION ANNULATION MISSION",
                    'emails/b2b/mission-cancel-request-client.html.twig',
                    [
                        'mission' => $mission
                    ]);
            }

            /* Client has accepted cancellation */
            if($mission->getStatus() == MissionStatus::CANCELLED)
            {
                /* Mail sent to the CM */
                $this->mailer->send($mission->getUser()->getEmail(),
                    "MISSION ANNULEE",
                    'emails/b2b/mission-cancel-accept-cm.html.twig',
                    [
                        'mission' => $mission
                    ]);

                /* Mail sent to the Client */
                $this->mailer->send($mission->getClient()->getEmail(),
                    "MISSION ANNULEE",
                    'emails/b2b/mission-cancel-accept-client.html.twig',
                    [
                        'mission' => $mission
                    ]);
            }

            /* CM has requested for termination */
            if($mission->getStatus() == MissionStatus::TERMINATE_REQUEST_INITIATED)
            {
                /* Mail sent to the CM */
                $this->mailer->send($mission->getUser()->getEmail(),
                    "MISSION TERMINEE",
                    'emails/b2b/mission-terminated-request-cm.html.twig',
                    [
                        'mission' => $mission
                    ]);

                /* Mail sent to the Client */
                $this->mailer->send($mission->getClient()->getEmail(),
                    "MISSION ANNULEE",
                    'emails/b2b/mission-terminated-request-client.html.twig',
                    [
                        'mission' => $mission
                    ]);
            }

            /* Client has accepted termination */
            if($mission->getStatus() == MissionStatus::TERMINATED)
            {
                /* Mail sent to the CM */
                $this->mailer->send($mission->getUser()->getEmail(),
                    "MISSION TERMINEE",
                    'emails/b2b/mission-terminated-accept-cm.html.twig',
                    [
                        'mission' => $mission
                    ]);

                /* Mail sent to the Client */
                $this->mailer->send($mission->getClient()->getEmail(),
                    "MISSION TERMINEE COTE CLIENT",
                    'emails/b2b/mission-terminated-accept-client.html.twig',
                    [
                        'mission' => $mission
                    ]);
            }
        }

        if(empty($missionBeforeUpdate))
        {
            $message = "Le ".$mission->getClient()." a été prévenu de votre modification de mission et a été sollicité pour effectuer le pré-paiement de la mission ".$mission->getId()." auprès de notre partenaire Mango Pay. Dès que le pré-paiement sera fait, vous serez prévenu(e) par notification vous pourrez commencer la mission";

            $this->mailer->send($mission->getUser()->getEmail(),
                'PRE-PAIEMENT',
                'emails/b2b/mission-create.html.twig',
                [
                    'message' => $message,
                    'mission' => $mission
                ]);
        }


    }

    public function postFlush(UserMission $mission, LifecycleEventArgs $args)
    {


    }

}
