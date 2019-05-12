<?php

namespace App\Entity\Listener;

use App\Entity\CardCollection;
use App\Entity\Client;
use App\Repository\AdminRepository;
use App\Service\Mailer;
use App\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;

class ClientRegistrationListener
{
    private $mailer;
    private $em;

    public function __construct(Mailer $mailer, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->em = $em;
    }

    public function preFlush(Client $client, PreFlushEventArgs $eventArgs)
    {
        // Send mail to the client who just registered
        $this->mailer->send($client->getEmail(), 'Registration Successful!', 'Welcome to Pix.City', [
        ]);

        // Send mail to the admin users
        $adminRepo = $this->em->getRepository(AdminRepository::class)->getManager();

        $admins = $adminRepo->findBy([]);

        foreach($admins as $admin)
        {
            $this->mailer->send($admin->getUsername(), 'Registration Successful!', 'Welcome to Pix.City', [
            ]);
        }
    }
}