<?php

namespace App\Repository;

use App\Constant\CardStatus;
use App\Entity\PageCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PageCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageCategory[]    findAll()
 * @method PageCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PageCategory::class);
    }

    public function findAllActive($regions = [], $userEmail = null)
    {
        $qb = $this->createQueryBuilder("p")
            ->addSelect(["p", "r", "t", "bg", "COUNT(cards.id) as totalCards, r.coordinates as coordinates"])
                ->leftJoin("p.region", "r")
                ->leftJoin("p.thumb", "t")
                ->leftJoin("p.background", "bg")

            ->leftJoin("r.cards", "cards", Join::WITH, "cards.status = :status")->setParameter("status", CardStatus::VALIDATED)
            ->leftJoin("cards.pixie", "uid")
            ->where("p.hidden = false")

            ->groupBy('p.id');

            if(count($regions) > 0){
                $ids = [];
                foreach($regions as $region){
                    $ids[] = $region->getId();
                }
                $qb = $qb->andWhere("r IN (:regions)")->setParameter("regions", $ids);
            }
            /*if($userEmail != null){
                $qb = $qb->andWhere("uid.email IN ('ganesh@pix.city','bsingh@pix.cityy')");
            }*/
            if($userEmail != null){
                $qb = $qb->andWhere("uid.email NOT IN (".$userEmail.")");
            }

            return $qb->getQuery()
            ->getResult()
            ;
    }

    public function findOneBySlug($slug)
    {
        return $this->createQueryBuilder("p")
            ->select(["p", "bg", "r"])
            ->leftJoin("p.background", "bg")
            ->leftJoin("p.region", "r")
            ->where("p.slug = :slug OR r.slug = :slug")->setParameter("slug", $slug)
            ->getQuery()
            ->useResultCache(true, 0, "findOnePageCategory_".$slug)
            ->getOneOrNullResult()
            ;
    }
}
