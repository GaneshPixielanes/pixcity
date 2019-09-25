<?php

namespace App\Entity\Listener;

use App\Constant\CardProjectStatus;
use App\Constant\CardStatus;
use App\Entity\Admin;
use App\Entity\Card;
use App\Service\Mailer;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CardListener
{

    private $mailer;
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage, Mailer $mailer)
    {
        $this->tokenStorage = $tokenStorage;
        $this->mailer = $mailer;
    }

    public function preFlush(Card $card, PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        $projectBeforeUpdate = $uow->getOriginalEntityData($card);

        //---------------------------------------
        // Automatically set some status

        if(!$card->getStatus()) $card->setStatus(CardStatus::CREATED);

        if(CardStatus::VALIDATED === $card->getStatus()){
            $project = $card->getProject();
            if($project){
                $project->setStatus(CardProjectStatus::VALIDATED);
            }

            if(!empty($project) && (!empty($projectBeforeUpdate) && $projectBeforeUpdate["status"] !== $card->getStatus())  && $projectBeforeUpdate['level']== $card->getLevel()){

                //--------------------------------
                // Send email on card validation

                $this->mailer->send($project->getPixie()->getEmail(), 'Bravo ta card a été mise en ligne!', 'emails/pixie-card-validated-success.html.twig', [
                    'firstName' => $project->getPixie()->getFirstname(),
                    'cardName' => $project->getName(),
                    'regionName' => str_replace([' ','-'],'',$project->getRegion()->getName()),
                    'slug' => $card->getSlug(),
                    'card' => $card,
                    'cityName' => str_replace([' ','-'],'',$card->getAddress()->getCity()),
                    'bannerUrl' =>$card->getMasterhead()->getUrl(),
                    'thumbUrl' => $card->getThumb()->getUrl()
                ], [
                    $card->getMasterhead()->getUrl(),
                    $card->getThumb()->getUrl()
                ]);
            }
        }

        if(CardStatus::REFUSED === $card->getStatus()){
            $project = $card->getProject();
            if($project){
                $project->setStatus(CardProjectStatus::REFUSED);
            }

            if(!empty($project) && (!empty($projectBeforeUpdate) && $projectBeforeUpdate["status"] !== $card->getStatus())){

                //--------------------------------
                // Send email on card refused

                $this->mailer->send($project->getPixie()->getEmail(), 'Card refusée', 'emails/pixie-card-refused-mail.html.twig', [
                    'firstName' => $project->getPixie()->getFirstname(),
                    'card' => $card
                ]);
            }
        }

        //---------------------------------------
        // Automatically blame the admin who did the last modification

        $user = $this->tokenStorage->getToken()->getUser();
        if($user instanceof Admin) {
            $card->setUpdatedBy($user);
        }

    }

}