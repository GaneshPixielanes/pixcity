<?php

namespace App\Repository;

use App\Entity\Royalties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Royalties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Royalties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Royalties[]    findAll()
 * @method Royalties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoyaltiesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Royalties::class);
    }

    // /**
    //  * @return Royalties[] Returns an array of Royalties objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Royalties
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
