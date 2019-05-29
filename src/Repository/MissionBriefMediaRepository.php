<?php

namespace App\Repository;

use App\Entity\MissionBriefMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MissionBriefMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissionBriefMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissionBriefMedia[]    findAll()
 * @method MissionBriefMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionBriefMediaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MissionBriefMedia::class);
    }

    // /**
    //  * @return MissionBriefMedia[] Returns an array of MissionBriefMedia objects
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
    public function findOneBySomeField($value): ?MissionBriefMedia
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
