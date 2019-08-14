<?php

namespace App\Repository;

use App\Entity\UserInstagramDetailsApi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserInstagramDetailsApi|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInstagramDetailsApi|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInstagramDetailsApi[]    findAll()
 * @method UserInstagramDetailsApi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInstagramDetailsApiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserInstagramDetailsApi::class);
    }

    // /**
    //  * @return UserInstagramDetailsApi[] Returns an array of UserInstagramDetailsApi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserInstagramDetailsApi
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
