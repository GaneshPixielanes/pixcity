<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }


    public function findOneBySlug($slug)
    {
        return $this->createQueryBuilder("p")
            ->select(["p", "s", "slides", "bg", "image"])
                ->leftJoin("p.slider", "s")
                    ->leftJoin("s.slides", "slides")
                        ->leftJoin("slides.background", "bg")
                        ->leftJoin("slides.image", "image")
            ->where("p.slug = :slug")->setParameter("slug", $slug)
            ->getQuery()
            ->useResultCache(true, 0, "findOnePage_".$slug)
            ->getOneOrNullResult()
        ;
    }

}
