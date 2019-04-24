<?php

namespace App\Repository;

use App\Entity\UserRegistrationCheck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserRegistrationCheck|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRegistrationCheck|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRegistrationCheck[]    findAll()
 * @method UserRegistrationCheck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRegistrationCheckRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserRegistrationCheck::class);
    }

    // /**
    //  * @return UserRegistrationCheck[] Returns an array of UserRegistrationCheck objects
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
    public function findOneBySomeField($value): ?UserRegistrationCheck
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
