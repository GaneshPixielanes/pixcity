<?php

namespace App\Repository;

use App\Entity\UserMission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserMission|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMission|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMission[]    findAll()
 * @method UserMission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMissionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserMission::class);
    }

    // /**
    //  * @return UserMission[] Returns an array of UserMission objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserMission
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
