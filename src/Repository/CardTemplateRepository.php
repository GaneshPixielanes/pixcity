<?php

namespace App\Repository;

use App\Entity\CardTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CardTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardTemplate[]    findAll()
 * @method CardTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardTemplateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardTemplate::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.something = :value')->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
