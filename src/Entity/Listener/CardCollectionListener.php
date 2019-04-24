<?php

namespace App\Entity\Listener;

use App\Entity\CardCollection;
use App\Utils\Utils;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CardCollectionListener
{
    public function prePersist(CardCollection $collection, LifecycleEventArgs $event)
    {
        if("" === $collection->getName()){
            $collection->setName("Collection");
        }

        $collection->setUuid(Utils::uuid());
    }

}