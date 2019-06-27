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

    public function preFlush(UserMission $mission, PreFlushEventArgs $event)
    {
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
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        $mission = $uow->getOriginalEntityData($mission);
        {
            $missionLog = new MissionLog();

            $missionLog->setMission($mission);
            $missionLog->setUserBasePrice($mission->getUserBasePrice);
            $missionLog->setIsActive(1);
            $missionLog->setCreatedAt(new \DateTime());
            $missionLog->setCreatedBy($this->getUser()->getId());

            $mission->addMissionLog($missionLog);
            $em->persist($mission);
            $em->flush();

        }

    }

}
