<?php

namespace App\Repository;

use App\Entity\ContentDraft;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContentDraft|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentDraft|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentDraft[]    findAll()
 * @method ContentDraft[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentDraftRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContentDraft::class);
    }

    // /**
    //  * @return ContentDraft[] Returns an array of ContentDraft objects
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
    public function findOneBySomeField($value): ?ContentDraft
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
