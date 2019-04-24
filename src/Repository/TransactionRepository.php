<?php

namespace App\Repository;

use App\Entity\Transaction;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    private function _buildQuery(){
        return $this->createQueryBuilder('t')
            ->select(["t", "u", "pixie", "billing", "address", "projects"])
            ->leftJoin("t.user", "u")
            ->leftJoin("u.pixie", "pixie")
            ->leftJoin("pixie.billing", "billing")
            ->leftJoin("billing.address", "address")
            ->leftJoin("t.projects", "projects")
            ;
    }

    private function _buildCountQuery(){
        return $this->createQueryBuilder('t')
            ->select("COUNT(DISTINCT t.id)")
            ->leftJoin("t.user", "u")
            ;
    }

    private function _applyFilters($qb, $filters){
        if($filters) {
            if (isset($filters["pixie"])) {
                $qb = $qb->andWhere("u = :user")->setParameter("user", $filters["pixie"]);
            }

            if (isset($filters["status"])) {
                $qb = $qb->andWhere('t.status IN (:status)')->setParameter('status', $filters["status"]);
            }

            if (isset($filters["id"])) {
                $qb = $qb->andWhere('t.id = :id')->setParameter('id', $filters["id"]);
            }

            if (isset($filters["current_month"])) {
                $currentMonth = new DateTime('midnight first day of this month');
                $qb = $qb->andWhere('t.createdAt >= :date')->setParameter('date', $currentMonth);
            }
        }

        return $qb;
    }

    public function search($filters = [], $page = 1, $pageSize = 10, $orderByType = "newest")
    {
        $qb = $this->_buildQuery();
        $qb = $this->_applyFilters($qb, $filters);

        switch($orderByType){
            case "oldest": $searchOrderBy = [["updatedAt", "ASC"]]; break;
            case "newest":
            default:
                $searchOrderBy = [["updatedAt", "DESC"]]; break;
        }

        foreach($searchOrderBy as $order){
            $qb = $qb->addOrderBy('t.'.$order[0], $order[1]);
        }

        return $qb
            ->setFirstResult($pageSize * ($page-1))
            ->setMaxResults($pageSize)
            ->getQuery()
            ->getResult();
    }

    public function countSearchResult($filters = [])
    {
        $qb = $this->_buildCountQuery();
        $qb = $this->_applyFilters($qb, $filters);

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }
}
