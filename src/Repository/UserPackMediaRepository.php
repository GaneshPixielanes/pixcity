<?php

namespace App\Repository;

use App\Entity\UserPackMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserPackMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPackMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPackMedia[]    findAll()
 * @method UserPackMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPackMediaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserPackMedia::class);
    }

    // /**
    //  * @return UserPackMedia[] Returns an array of UserPackMedia objects
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
    public function findOneBySomeField($value): ?UserPackMedia
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
