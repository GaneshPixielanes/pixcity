<?php

namespace App\Entity\Listener;

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

    public function __construct(TokenStorage $tokenStorage, Mailer $mailer)
    {
        $this->tokenStorage = $tokenStorage;
        $this->mailer = $mailer;
    }

    public function preFlush(UserMission $mission, PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        $missionBeforeUpdate = $uow->getOriginalEntityData($mission);

        if(!empty($mission) && (!empty($missionBeforeUpdate) && $missionBeforeUpdate['status'] !== $mission->getStatus()))
        {
            $message = "Le ".$mission->getClient()." a été prévenu de votre modification de mission et a été sollicité pour effectuer le pré-paiement de la mission ".$mission->getId()." auprès de notre partenaire Mango Pay. Dès que le pré-paiement sera fait, vous serez prévenu(e) par notification vous pourrez commencer la mission";

            $this->mailer->send($mission->getUser()->getEmail(),
                'emails/b2b/mission-create.html.twig',
                'PRE-PAIEMENT',
                [
                    'message' => $message,
                    'firstname' => $mission
                ]);
        }
//        $request = Request::createFromGlobals();
//
//        if(count($request->request->all())){
//            $request = $request->request->get('mission');
//            $missionLog = new MissionLog();
//            $documents = [];
//
//            if(!empty($request['documents'])){
//                foreach($request['documents'] as $document)
//                {
//                    $documents[] = $document;
//                }
//            }
//
//            $missionLog->setMission($mission);
//            $missionLog->setUserBasePrice($request['missionBasePrice']);
//            $missionLog->setBriefFiles(json_encode($documents));
//            $missionLog->setIsActive(0);
//            $missionLog->setCreatedAt(new \DateTime());
//            $missionLog->setCreatedBy($mission->getUser()->getId());
//
//            $mission->addMissionLog($missionLog);
//        }


    }

    public function postFlush(UserMission $mission, LifecycleEventArgs $args)
    {


    }

}
