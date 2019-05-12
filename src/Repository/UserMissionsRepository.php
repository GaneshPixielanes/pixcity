<?php

namespace App\Repository;

use App\Entity\UserMissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserMissions|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMissions|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMissions[]    findAll()
 * @method UserMissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMissionsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserMissions::class);
    }

    // /**
    //  * @return UserMissions[] Returns an array of UserMissions objects
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
    public function findOneBySomeField($value): ?UserMissions
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
