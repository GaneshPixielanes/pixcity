<?php

namespace App\Repository;

use App\Constant\CardProjectStatus;
use App\Entity\CardProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CardProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardProject[]    findAll()
 * @method CardProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardProjectRepository extends ServiceEntityRepository
{
    private $activeStatus = [
        CardProjectStatus::ASSIGNED,
        CardProjectStatus::PIXIE_ACCEPTED,
        CardProjectStatus::PIXIE_REFUSED,
        CardProjectStatus::PIXIE_SUBMITTED,
        CardProjectStatus::VALIDATED,
        CardProjectStatus::REFUSED
    ];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardProject::class);
    }

    private function _buildQuery(){
        return $this->createQueryBuilder('project')
            ->select("project")
            ->leftJoin("project.pixie", "p")
            ->leftJoin("project.region", "r")
            ->leftJoin("project.categories", "category")
            ->groupBy('project.id')
            ;
    }

    private function _buildCountQuery(){
        return $this->createQueryBuilder('project')
            ->select("COUNT(DISTINCT project.id)")
            ->leftJoin("project.pixie", "p")
            ->leftJoin("project.region", "r")
            ->leftJoin("project.categories", "category")
            ;
    }

    private function _applyFilters($qb, $filters){
        if($filters) {
            if (isset($filters["regions"])) {
                $qb = $qb->andWhere("r IN (:regions) OR r.slug IN (:regions)")->setParameter("regions", $filters["regions"]);
            }

            if (isset($filters["categories"])) {
                $qb = $qb->andWhere("category.id IN (:categories) OR category.slug IN (:categories)")->setParameter("categories", $filters["categories"]);
            }

            if (isset($filters["pixie"])) {
                $qb = $qb->andWhere("p = :user")->setParameter("user", $filters["pixie"]);
            }

            if (isset($filters["status"])) {
                $qb = $qb->andWhere('project.status IN (:status)')->setParameter('status', $filters["status"]);
            }
            else{
                $qb = $qb->andWhere('project.status IN (:status)')->setParameter('status', $this->activeStatus);
            }

            if (isset($filters["no_transaction"])) {
                $qb = $qb->andWhere('project.transactions is empty');
            }

            if (isset($filters["delivery_date"]))
            {
                $now = new \DateTime("now");
                $qb = $qb->andWhere('project.deliveryDate <= :deliveryDate')->setParameter('deliveryDate',$filters['delivery_date'])
                         ->andWhere('project.deliveryDate >= :now')->setParameter('now', $now);
            }
        }

        return $qb;
    }

    public function search($filters = [], $page = 1, $pageSize = 10, $orderByType = "newest")
    {
        $qb = $this->_buildQuery();
        $qb = $this->_applyFilters($qb, $filters);

        switch($orderByType){
            case "oldest": $searchOrderBy = [["createdAt", "ASC"]]; break;
            case "newest":
            default:
                $searchOrderBy = [["createdAt", "DESC"]]; break;
        }

        foreach($searchOrderBy as $order){
            $qb = $qb->addOrderBy('project.'.$order[0], $order[1]);
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

    public function searchDeadlineComing($filters = [])
    {
        $qb = $this->_buildQuery();

        $now = new \DateTime("now");
        $qb = $qb->andWhere('project.deliveryDate < :now')->setParameter('now', $now);
        $qb = $qb->andWhere('project.status != :statusvalidated')->setParameter('statusvalidated', CardProjectStatus::VALIDATED);
        $qb = $qb->andWhere('project.status != :statusrefused')->setParameter('statusrefused', CardProjectStatus::REFUSED);
        $qb = $qb->andWhere('project.status != :statuspixierefused')->setParameter('statuspixierefused', CardProjectStatus::PIXIE_REFUSED);
        $qb = $qb->andWhere('project.reminderEmailSent IS NULL OR project.reminderEmailSent = 0');

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function generateContract($id, CardProjectRepository $projectsRepo, $user_id)
    {

        $project = $projectsRepo->findOneBy([
            "id" => $id,
            "status" => CardProjectStatus::ASSIGNED,
            "pixie" => $user_id
        ]);

        $user = $project->getPixie();

        if(!$project || !$user){
            return $this->redirectToRoute('front_pixie_account_cards_projects');
        }

        $contract = [
            "gender" => $project->getPixie()->getGender(),
            "firstname" => $project->getPixie()->getFirstname(),
            "lastname" => $project->getPixie()->getLastname(),
            "birthdate" => $project->getPixie()->getBirthdate(),
            "status" => $project->getPixie()->getPixie()->getBilling()->getStatus(),
            "company_name" => $project->getPixie()->getPixie()->getBilling()->getCompanyName(),
            "company_address" => $project->getPixie()->getPixie()->getBilling()->getAddress()->getInlineAddress(),
            "company_country" => $project->getPixie()->getPixie()->getBilling()->getAddress()->getCountry(),
            "company_tva" => $project->getPixie()->getPixie()->getBilling()->getTva(),
            "date" => date("Y-m-d H:i:s"),
            "card_project_name" => $project->getName(),
            "price" => $project->getPrice(),
            // "interview" => $project->getIsInterview()
        ];

        return $contract;
    }
}
