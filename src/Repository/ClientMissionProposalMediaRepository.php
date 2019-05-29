<?php

namespace App\Repository;

use App\Entity\ClientMissionProposalMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClientMissionProposalMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientMissionProposalMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientMissionProposalMedia[]    findAll()
 * @method ClientMissionProposalMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientMissionProposalMediaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClientMissionProposalMedia::class);
    }

    // /**
    //  * @return ClientMissionProposalMedia[] Returns an array of ClientMissionProposalMedia objects
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
    public function findOneBySomeField($value): ?ClientMissionProposalMedia
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
