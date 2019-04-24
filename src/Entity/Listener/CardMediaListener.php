<?php

namespace App\Entity\Listener;

use App\Entity\CardMedia;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CardMediaListener
{
    private $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function postRemove(CardMedia $cardMedia, LifecycleEventArgs $event)
    {
        $mediaPath = $cardMedia->getRelativeUrl();
        if (!empty($cardMedia->getName()) && file_exists($mediaPath)) {
            unlink($mediaPath);
        }
    }

}