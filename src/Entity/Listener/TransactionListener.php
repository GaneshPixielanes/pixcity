<?php

namespace App\Entity\Listener;

use App\Entity\Transaction;
use App\Entity\TransactionHistory;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TransactionListener
{

    public function prePersist(Transaction $transaction, LifecycleEventArgs $event)
    {
        // Automatically add the status to the history
        $newStatusHistory = new TransactionHistory();
        $newStatusHistory->setStatus($transaction->getStatus());
        $transaction->addHistory($newStatusHistory);

        $event->getEntityManager()->persist($newStatusHistory);

    }

    public function postUpdate(Transaction $transaction, LifecycleEventArgs $event)
    {
        //$uow = $event->getEntityManager()->getUnitOfWork();
        //$changes = $uow->getEntityChangeSet($transaction);

        // Automatically add the status to the history
        $newStatusHistory = new TransactionHistory();
        $newStatusHistory->setStatus($transaction->getStatus());
        $transaction->addHistory($newStatusHistory);

        $event->getEntityManager()->persist($transaction);
        $event->getEntityManager()->persist($newStatusHistory);
        $event->getEntityManager()->flush();


    }

}