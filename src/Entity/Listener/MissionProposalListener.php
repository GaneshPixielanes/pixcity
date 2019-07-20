<?php

namespace App\Entity\Listener;

use App\Entity\ClientMissionProposal;
use App\Service\Mailer;
use Doctrine\ORM\Event\PreFlushEventArgs;

class MissionProposalListener{

    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function preFlush(ClientMissionProposal $proposal, PreFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        $proposalBeforeUpdate = $uow->getOriginalEntityData($proposal);

        if(empty($proposalBeforeUpdate))
        {
            /* Mail sent to CM */
            $this->mailer->send($proposal->getPack()->getUser()->getEmail(),
                'Question sur le pack "'.$proposal->getPack()->getTitle().'"',
                'emails/b2b/mission-proposal-cm.html.twig',
                [
                    'proposal' => $proposal
                ]);
        }

    }
}