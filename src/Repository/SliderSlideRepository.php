<?php

namespace App\Repository;

use App\Entity\SliderSlide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SliderSlide|null find($id, $lockMode = null, $lockVersion = null)
 * @method SliderSlide|null findOneBy(array $criteria, array $orderBy = null)
 * @method SliderSlide[]    findAll()
 * @method SliderSlide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SliderSlideRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SliderSlide::class);
    }

    public function findBySlider($sliderId)
    {
        return $this->createQueryBuilder('s')
            ->where('s.slider = :value')->setParameter('value', $sliderId)
            ->orderBy('s.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
