<?php

namespace App\Repository;

use App\Entity\ClientInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClientInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientInfo[]    findAll()
 * @method ClientInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientInfoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClientInfo::class);
    }

    // /**
    //  * @return ClientInfo[] Returns an array of ClientInfo objects
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
    public function findOneBySomeField($value): ?ClientInfo
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
