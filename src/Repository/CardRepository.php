<?php

namespace App\Repository;

use App\Constant\CardStatus;
use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Card::class);
    }

    private function _buildQuery(){
        return $this->createQueryBuilder('c')
            ->select("c, p, r, t, address, category, pixie, pixieregions")
            ->leftJoin("c.pixie", "p")
            ->leftJoin("p.pixie", "pixie")
            ->leftJoin("pixie.regions", "pixieregions")
            ->leftJoin("c.thumb", "t")
            ->leftJoin("c.region", "r")
            ->leftJoin("c.address", "address")
            ->leftJoin("c.categories", "category")
            ->where('c.status = :status')->setParameter('status', CardStatus::VALIDATED)
            ->andWhere('c.deleted IS NULL OR c.deleted = 0')
            ->groupBy('c.id')
            ;
    }

    private function _buildCountQuery(){
        return $this->createQueryBuilder('c')
            ->select("COUNT(DISTINCT c.id)")
            ->leftJoin("c.pixie", "p")
            ->leftJoin("c.region", "r")
            ->leftJoin("c.categories", "category")
            ->where('c.status = :status')->setParameter('status', CardStatus::VALIDATED)
            ->andWhere('c.deleted IS NULL OR c.deleted = 0')
            ;
    }

    private function _buildCountPixies(){
        return $this->createQueryBuilder('c')
            ->select(["p.firstname", "p.lastname", "p.avatar"])
            ->leftJoin("c.pixie", "p")
            ->leftJoin("c.region", "r")
            ->leftJoin("c.categories", "category")
            ->where('c.status = :status')->setParameter('status', CardStatus::VALIDATED)
            ->andWhere('c.deleted IS NULL OR c.deleted = 0')
            ->groupBy('p.id')
            ;
    }

    private function _applyFilters($qb, $filters){
        if($filters) {
            if (!empty($filters["text"])) {
                $qb = $qb->andWhere("r.name LIKE :searchText OR category.name LIKE :searchText OR c.name LIKE :searchText OR c.content LIKE :contentText")
                    ->setParameter("searchText", "%".$filters["text"]."%")
                    ->setParameter("contentText", "%".htmlentities($filters["text"])."%");
            }
            if (!empty($filters["regions"])) {
                $qb = $qb->andWhere("r IN (:regions) OR r.slug IN (:regions)")->setParameter("regions", $filters["regions"]);
            }

            if (!empty($filters["categories"])) {
                $qb = $qb->andWhere("category.id IN (:categories) OR category.slug IN (:categories)")->setParameter("categories", $filters["categories"]);
            }

            if (!empty($filters["pixie"])) {
                $qb = $qb->andWhere("p = :user")->setParameter("user", $filters["pixie"]);
            }

            if (!empty($filters["users"])) {
                $qb = $qb->andWhere("p.id IN (:users)")->setParameter("users", $filters["users"]);
            }

            if (!empty($filters["cards"])) {
                $qb = $qb->andWhere("c.id IN (:cards)")->setParameter("cards", $filters["cards"]);
            }

            if (!empty($filters["status"])) {
                $qb = $qb->andWhere("c.status = :status)")->setParameter("status", $filters["status"]);
            }

            if (!empty($filters["userFavorite"])) {
                $qb = $qb->leftJoin("c.favoriteUsers", "favUser");
                $qb = $qb->andWhere("favUser.id = :userFavorite")->setParameter("userFavorite", $filters["userFavorite"]);
            }

            if (!empty($filters["lastWeek"])) {
                $qb = $qb->andWhere("c.updatedAt > :lastWeek")->setParameter("lastWeek", date('Y-m-d', strtotime('-7 days')));
            }
        }

        return $qb;
    }

    public function search($filters = [], $page = 1, $pageSize = 10, $orderByType = "popular",$userEmail=null)
    {
        $qb = $this->_buildQuery();
        $qb = $this->_applyFilters($qb, $filters);

        switch($orderByType){
            case "newest": $searchOrderBy = [["updatedAt", "DESC"]]; break;
            case "oldest": $searchOrderBy = [["updatedAt", "ASC"]]; break;
            case "popular":
            default:
                $searchOrderBy = [["likes", "DESC"], ["createdAt", "DESC"]];
        }

        foreach($searchOrderBy as $order){
            $qb = $qb->addOrderBy('c.'.$order[0], $order[1]);
        }
//        if($userEmail != null){
//            $qb = $qb->andWhere("p.email IN ('ganesh@pix.city','bsingh@pix.cityy')");
//        }
        if($userEmail != null){
            $qb = $qb->andWhere("p.email NOT IN (".$userEmail.") AND p.visible = 1");
        }
        /*
        $qb = $qb
            ->setFirstResult($pageSize * ($page-1))
            ->setMaxResults($pageSize);

        return new Paginator($qb);
        */


        return $qb
            ->setFirstResult($pageSize * ($page-1))
            ->addSelect('c')
            ->setMaxResults($pageSize)
            ->getQuery()
            // ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();


    }

    public function countSearchResult($filters = [], $userEmail=null)
    {
        $qb = $this->_buildCountQuery();
        if($userEmail != null){
            $qb = $qb->andWhere("p.email NOT IN (".$userEmail.") AND p.visible = 1");
        }
//        if($userEmail == null){
//            $qb = $qb->andWhere("p.email NOT IN ('ganesh@pix.city','bsingh@pix.cityy')");
//        }
        $qb = $this->_applyFilters($qb, $filters);

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countSearchPixies($filters = [])
    {
        $qb = $this->_buildCountPixies();
        $qb = $this->_applyFilters($qb, $filters);

        return $qb
            ->getQuery()
            ->getResult();
    }


    public function countCardsByRegion($regionId,$userEmail=null)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->leftJoin("c.pixie", "p")
            ->where('c.region = :region')->setParameter('region', $regionId)
            ->andWhere('c.status = :status')->setParameter('status', CardStatus::VALIDATED)
            ;
        if($userEmail != null){
            $qb = $qb->andWhere("p.email NOT IN (".$userEmail.")");
        }
//        if($userEmail == null){
//            $qb = $qb->andWhere("p.email NOT IN ('ganesh@pix.city','bsingh@pix.cityy')");
//        }
        $qb = $qb->getQuery()
            ->useResultCache(true, 0, "countCardsByRegion_".$regionId)
            ->getSingleScalarResult()
            ;
        return $qb;
    }

    public function findCardBySlug($slug)
    {
        return $this->createQueryBuilder('c')
            ->select("c, p, r, t, address, category")
            ->leftJoin("c.pixie", "p")
            ->leftJoin("c.thumb", "t")
            ->leftJoin("c.region", "r")
            ->leftJoin("c.address", "address")
            ->leftJoin("c.categories", "category")
            ->andWhere('c.slug = :slug')->setParameter('slug', $slug)
            ->andWhere('c.status = :status')->setParameter('status', CardStatus::VALIDATED)
            ->andWhere('c.deleted IS NULL OR c.deleted = 0')
            ->getQuery()
            ->useResultCache(true, 0, "findCardBySlug_".$slug)
            ->getOneOrNullResult()
            ;
    }

    public function findPixieCards($pixie)
    {
        return $this->createQueryBuilder('c')
            ->select("c")
            ->andWhere('c.pixie = :pixie')->setParameter('pixie', $pixie)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPixieCardsLikes($pixie)
    {
        return $this->createQueryBuilder('c')
            ->select("SUM(c.likes)")
            ->andWhere('c.pixie = :pixie')->setParameter('pixie', $pixie)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findPixieCardsByStatus($pixie, $status)
    {
        return $this->createQueryBuilder('c')
            ->select("c, p, r, t, address, category")
            ->leftJoin("c.pixie", "p")
            ->leftJoin("c.thumb", "t")
            ->leftJoin("c.region", "r")
            ->leftJoin("c.address", "address")
            ->leftJoin("c.categories", "category")
            ->andWhere('c.pixie = :pixie')->setParameter('pixie', $pixie)
            ->andWhere('c.status = :status')->setParameter('status', $status)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllCardsValidated($filters = [])
    {
        $qb = $this->_buildQuery();
        $qb = $this->_applyFilters($qb, $filters);

        $result = $qb->select(["address.latitude, address.longitude, c.id, category.icon"])
                 ->andWhere('c.status = :status')
                 ->setParameter('status',CardStatus::VALIDATED)
                 ->getQuery()
                 ->getResult();

        return $result;         
    }

    public function findNextCard($card)
    {
        $result = null;
        try{
            $qb = $this->_buildQuery();

            $result = $qb->where('c.status = :status')->setParameter('status',CardStatus::VALIDATED)
                // ->andWhere('c.pixie = :cityMaker')->setParameter('cityMaker',$card->getPixie())
                ->andWhere('c.id > :id')->setParameter('id',$card->getId())
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        }catch (\Exception $e)
        {
            // DO Nothing
        }

        return $result;
    }
    public function findPrevCard($card)
    {
        $result = null;

        try{
            $qb = $this->_buildQuery();

            $result = $qb->where('c.status = :status')->setParameter('status',CardStatus::VALIDATED)
                // ->andWhere('c.pixie = :cityMaker')->setParameter('cityMaker',$card->getPixie())
                ->andWhere('c.id < :id')->setParameter('id',$card->getId())
                ->orderBy('c.id','DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        }catch (\Exception $e)
        {
            // DO NOTHING
        }
        return $result;
    }
}
