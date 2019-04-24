<?php

namespace App\Entity\Listener;

use App\Constant\CardProjectStatus;
use App\Constant\PaymentStatus;
use App\Entity\CardProject;
use App\Service\Mailer;
use Doctrine\ORM\Event\PreFlushEventArgs;

class CardProjectListener
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function preFlush(CardProject $project, PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        $projectBeforeUpdate = $uow->getOriginalEntityData($project);

        //---------------------------------------
        // Automatically set some status

        // By default assign the `created` status and `pending` payement
        if(!$project->getStatus()){
            $project->setStatus(CardProjectStatus::CREATED);
        }

        // If there is not pixie selected, set status to `created`
        if(
            !$project->getPixie() &&
            CardProjectStatus::TEMPLATE !== $project->getStatus()
        ){
            $project->setStatus(CardProjectStatus::CREATED);
        }

        // If a pixie is selected, set the status `assigned`
        if(CardProjectStatus::CREATED === $project->getStatus()){
           if($project->getPixie()){
               $project->setStatus(CardProjectStatus::ASSIGNED);
           }
        }

        if($project->getPixie()){

            //-------------------------------------------
            // Pixie assignation changed

            if((!empty($projectBeforeUpdate) && $projectBeforeUpdate["pixie"] !== $project->getPixie()) || empty($projectBeforeUpdate)){

                $this->mailer->send($project->getPixie()->getEmail(), 'Tu as une nouvelle demande de card', 'emails/pixie-card-new.html.twig', [
                    'firstname' => $project->getPixie()->getFirstname()
                ]);

            }

        }


    }

}