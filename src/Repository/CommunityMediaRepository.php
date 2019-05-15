<?php

namespace App\Repository;

use App\Entity\CommunityMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommunityMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommunityMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommunityMedia[]    findAll()
 * @method CommunityMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunityMediaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommunityMedia::class);
    }

    // /**
    //  * @return CommunityMedia[] Returns an array of CommunityMedia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommunityMedia
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
