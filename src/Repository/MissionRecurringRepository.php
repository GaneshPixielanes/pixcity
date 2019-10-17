<?php

namespace App\Repository;

use App\Entity\MissionRecurring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MissionRecurring|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissionRecurring|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissionRecurring[]    findAll()
 * @method MissionRecurring[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionRecurringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MissionRecurring::class);
    }

    // /**
    //  * @return MissionRecurring[] Returns an array of MissionRecurring objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MissionRecurring
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
