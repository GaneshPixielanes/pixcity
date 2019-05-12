<?php

namespace App\Repository;

use App\Entity\UserClientActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserClientActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserClientActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserClientActivity[]    findAll()
 * @method UserClientActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserClientActivityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserClientActivity::class);
    }

    // /**
    //  * @return UserClientActivity[] Returns an array of UserClientActivity objects
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
    public function findOneBySomeField($value): ?UserClientActivity
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
