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

class ClientListener
{
    private $mailer;
    private $em;

    public function __construct(Mailer $mailer, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->em = $em;
    }

    public function preFlush(Client $client, PreFlushEventArgs $event)
    {

        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        $clientBeforeUpdate = $uow->getOriginalEntityData($client);

        if(empty($clientBeforeUpdate))
        {
            // Send mail to the client who just registered
            $this->mailer->send($client->getEmail(), 'Bienvenue sur Pix.city Services !',
                'emails/b2b/client-register.html.twig', [
                    'client' => $client
                ]);
        }



//        // Send mail to the admin users
//        $adminRepo = $this->em->getRepository(AdminRepository::class)->getManager();
//
//        $admins = $adminRepo->findBy([]);
//
//        foreach($admins as $admin)
//        {
//            $this->mailer->send($admin->getUsername(), 'Registration Successful!', 'Welcome to Pix.City', [
//            ]);
//        }
    }
}