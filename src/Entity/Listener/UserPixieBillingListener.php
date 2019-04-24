<?php

namespace App\Entity\Listener;

use App\Entity\UserPixieBilling;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class UserPixieBillingListener
{

    public function preUpdate(UserPixieBilling $billing, PreUpdateEventArgs $event)
    {
        $uow = $event->getEntityManager()->getUnitOfWork();
        $changes = $uow->getEntityChangeSet($billing);
        if($uow->isEntityScheduled($billing) && !isset($changes["needRevolutUpdate"])){
            // Billing info changed
            $billing->setNeedRevolutUpdate(true);
        }
    }

}