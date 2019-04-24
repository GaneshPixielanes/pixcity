<?php

namespace App\Repository;

use App\Entity\CardWall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CardWall|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardWall|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardWall[]    findAll()
 * @method CardWall[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardWallRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardWall::class);
    }


    public function findOneBySlug($slug)
    {
        return $this->createQueryBuilder("p")
            ->select(["p", "r", "category"])
            ->leftJoin("p.region", "r")
            ->leftJoin("p.categories", "category")
            ->where("p.slug = :slug OR r.slug = :slug")->setParameter("slug", $slug)
            ->getQuery()
            ->useResultCache(true, 0, "findOnePageWall_".$slug)
            ->getOneOrNullResult()
            ;
    }

    public function findOneByFilters($filters)
    {
        $qb = $this->createQueryBuilder("p")
            ->select(["p", "r", "category"])
            ->leftJoin("p.region", "r")
            ->leftJoin("p.categories", "category")
            ;

        if($filters) {
            if (isset($filters["regions"])) {
                foreach($filters["regions"] as $regionSlug){
                    $qb = $qb->andWhere("r = :region OR r.slug = :region")->setParameter("region", $regionSlug);
                }
            }
            else{
                $qb = $qb->andWhere("r.id IS NULL");
            }

            if (isset($filters["categories"])) {
                foreach($filters["categories"] as $categorySlug){
                    $qb = $qb->andWhere("category.id = :cat OR category.slug = :cat")->setParameter("cat", $categorySlug);
                }
            }
            else{
                $qb = $qb->andWhere("category.id IS NULL");
            }
        }

        return $qb
            ->getQuery()
            ->getResult();
    }

}
