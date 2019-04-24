<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * @return Menu
     */
    public function findOneBySlug($slug)
    {
        return $this->createQueryBuilder('m')
            ->select(["m", "i", "p"])
                ->join("m.items", "i")
                    ->join("i.page", "p")
            ->where("m.slug = :slug")->setParameter('slug', $slug)
            ->getQuery()
            ->useResultCache(true, 0, "findOneMenu_".$slug)
            ->getSingleResult()
            ;
    }
}
