<?php

namespace App\Repository;

use App\Entity\InstagramTrends;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InstagramTrends|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstagramTrends|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstagramTrends[]    findAll()
 * @method InstagramTrends[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstagramTrendsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InstagramTrends::class);
    }

    // /**
    //  * @return InstagramTrends[] Returns an array of InstagramTrends objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InstagramTrends
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findTrendLoggedUserId()
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('i')
            ->select('u.id')
            ->join('i.user','u')
            ->andWhere('i.createdAt LIKE :date')
            ->setParameter('date', '%'.$date->format('Y-m-d').'%')
            ->getQuery()
            ->getResult();
    }
}
