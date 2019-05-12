<?php

namespace App\Repository;

use App\Entity\ClientMissionProposal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClientMissionProposal|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientMissionProposal|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientMissionProposal[]    findAll()
 * @method ClientMissionProposal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientMissionProposalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClientMissionProposal::class);
    }

    // /**
    //  * @return ClientMissionProposal[] Returns an array of ClientMissionProposal objects
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
    public function findOneBySomeField($value): ?ClientMissionProposal
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
