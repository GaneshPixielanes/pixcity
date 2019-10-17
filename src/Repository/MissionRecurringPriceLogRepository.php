<?php

namespace App\Repository;

use App\Entity\MissionRecurringPriceLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MissionRecurringPriceLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissionRecurringPriceLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissionRecurringPriceLog[]    findAll()
 * @method MissionRecurringPriceLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionRecurringPriceLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MissionRecurringPriceLog::class);
    }

    // /**
    //  * @return MissionRecurringPriceLog[] Returns an array of MissionRecurringPriceLog objects
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
    public function findOneBySomeField($value): ?MissionRecurringPriceLog
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findLastRow($value)
    {

        return $this->createQueryBuilder('m')
            ->andWhere('m.mission  =:val')
            ->setParameter('val', $value)
            ->orderBy('m.id','DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    public function findPreviousRow($logId,$missionID)
    {

        return $this->createQueryBuilder('m')
            ->andWhere('m.mission  =:mission')
            ->setParameter('mission', $missionID)
            ->andWhere('m.id < :logId')
            ->setParameter('logId', $logId)
            ->orderBy('m.id','DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

}
