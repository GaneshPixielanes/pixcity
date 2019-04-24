<?php

namespace App\Repository;

use App\Entity\OutboundAnalytics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OutboundAnalytics|null find($id, $lockMode = null, $lockVersion = null)
 * @method OutboundAnalytics|null findOneBy(array $criteria, array $orderBy = null)
 * @method OutboundAnalytics[]    findAll()
 * @method OutboundAnalytics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutboundAnalyticsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OutboundAnalytics::class);
    }

    // /**
    //  * @return OutboundAnalytics[] Returns an array of OutboundAnalytics objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OutboundAnalytics
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
