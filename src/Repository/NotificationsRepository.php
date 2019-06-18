<?php

namespace App\Repository;

use App\Entity\Notifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Notifications|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notifications|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notifications[]    findAll()
 * @method Notifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationsRepository extends ServiceEntityRepository
{

    protected  $em;

    public function __construct(RegistryInterface $registry,EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($registry, Notifications::class);
    }

    public function insert($user,$client,$type, $message = 'content will come here',$notifyBy = null){

        $notification = new Notifications();
        $notification->setUser($user);
        $notification->setClient($client);
        $notification->setType($type);
        $notification->setUnread(1);
        $notification->setMessage($message);
        $notification->setNotifyBy($notifyBy);
        $this->em->persist($notification);
        $this->em->flush();

        return $notification;

    }

    // /**
    //  * @return Notifications[] Returns an array of Notifications objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Notifications
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
