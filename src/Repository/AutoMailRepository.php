<?php

namespace App\Repository;

use App\Entity\AutoMail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AutoMail|null find($id, $lockMode = null, $lockVersion = null)
 * @method AutoMail|null findOneBy(array $criteria, array $orderBy = null)
 * @method AutoMail[]    findAll()
 * @method AutoMail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoMailRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AutoMail::class);
    }

    // /**
    //  * @return AutoMail[] Returns an array of AutoMail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AutoMail
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
