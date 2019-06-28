<?php

namespace App\Repository;

use App\Entity\MissionLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MissionLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissionLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissionLog[]    findAll()
 * @method MissionLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MissionLog::class);
    }

    // /**
    //  * @return MissionLog[] Returns an array of MissionLog objects
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
    public function findOneBySomeField($value): ?MissionLog
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
