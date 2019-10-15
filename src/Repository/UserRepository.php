<?php

namespace App\Repository;

use App\Constant\UserLevel;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Constant\CardStatus;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }


    //---------------------------------------
    // Users
    //---------------------------------------

    public function searchUsers($filters = [], $page = 1, $pageSize = 10, $orderBy = [])
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.userPacks','packs')
            ->select(["u"])
        ;

        foreach($orderBy as $order){
            $qb = $qb->addOrderBy('u.'.$order[0], $order[1]);
        }

        $qb = $this->_applyFilters($qb, $filters);

        $qb = $qb
            ->setFirstResult($pageSize * ($page-1))
            ->setMaxResults($pageSize)
            ->getQuery()->getResult();

        return $qb;
    }

    public function countUsers($filters = [])
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.userPacks','packs')
            ->select("COUNT(DISTINCT u.id)")
        ;

        $qb = $this->_applyFilters($qb, $filters);

        $qb = $qb
            ->getQuery()
            ->getSingleScalarResult();

        return $qb;
    }

    public function searchUserById($id)
    {
        $qb = $this->createQueryBuilder('u')
            ->select(["u", "c", "p", "r", "category"])
            ->leftJoin('u.links', 'c')
            ->leftJoin('u.favoriteCategories', 'category')
            ->innerJoin('u.pixie', 'p')
            ->innerJoin('p.regions', 'r')
            ->where("u.id = :id")->setParameter("id", $id)
        ;

        $qb = $qb->getQuery()
            ->getOneOrNullResult();

        return $qb;
    }

    public function searchUserFollowingPixies()
    {
        $qb = $this->createQueryBuilder('u')
            ->select(["u"])
            ->innerJoin('u.favoritePixies', 'user_pixies')
        ;

        $qb = $qb->getQuery()
            ->getResult();

        return $qb;
    }


    //---------------------------------------
    // Pixies
    //---------------------------------------

    public function searchPixies($filters = [],$userEmail=null)
    {
        $qb = $this->createQueryBuilder('u')
            ->select(["u", "avatar", "c", "p",  "category"])
            ->leftJoin('u.avatar', 'avatar')
            ->leftJoin('u.cards', 'cards')
            ->leftJoin('u.links', 'c')
            ->leftJoin('u.favoriteCategories', 'category')
            ->innerJoin('u.pixie', 'p')
            //    ->innerJoin('p.regions', 'r')

            ->where('u.deleted IS NULL OR u.deleted = 0')
            ->andWhere('u.visible = 1')
        ;
        if($userEmail != null){
            $qb = $qb->andWhere("u.email NOT IN (".$userEmail.")");
        }

//        if($userEmail == null){
//            $qb = $qb->andWhere("u.email NOT IN ('ganesh@pix.city','bsingh@pix.cityy')");
//        }
        $qb = $this->_applyFilters($qb, $filters);

        $qb = $qb->getQuery()->getResult();

        return $qb;
    }

    //---------------------------------------
    // CITY MAKER SEARCH
    //---------------------------------------

    public function searchClients($filters = [], $limit = 12, $page = 1, $isRandom = false,$userEmail=null,$loggedType=null)
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.avatar', 'avatar')
            ->leftJoin('u.userSkills','s')
            ->innerJoin('u.userPacks','packs')
            ->leftJoin('u.userRegion', 'r')
            ->andWhere('u.visible = 1 AND u.active = 1 AND u.roles LIKE :role')->setParameter('role','%ROLE_CM%');

        if($userEmail != null && $loggedType == 'login_client'){
            $qb = $qb->andWhere("u.email IN (".$userEmail.")");
        }
        if($userEmail != null && $loggedType == 'login_cm'){
            $qb = $qb->andWhere("u.email IN (".$userEmail.")");
        }
        if($userEmail != null && $loggedType == null){
            $qb = $qb->andWhere("u.email NOT IN (".$userEmail.")");
        }
        if($isRandom == true)
        {
            $qb = $qb->orderBy('RAND()');
        }
        else
        {
            $qb = $qb->orderBy('u.id','DESC');
        }

        $qb = $qb->groupBy('u.id');

        $qb = $this->_applyFiltersClients($qb, $filters)->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        $qb = $qb->getQuery()->getResult();

        return $qb;
    }

    public function searchCommunityManagerCount($filters = [], $limit, $page,$userEmail=null,$loggedType=null)
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.avatar', 'avatar')
            ->leftJoin('u.userSkills','s')
            ->select('COUNT(DISTINCT u.id)')
            ->innerJoin('u.userPacks','packs')
            ->leftJoin('u.userRegion', 'r')
            ->andWhere('u.visible = 1 AND u.active = 1 AND u.roles LIKE :role')->setParameter('role','%ROLE_CM%');
        if($userEmail != null && $loggedType == 'login_client'){
            $qb = $qb->andWhere("u.email IN (".$userEmail.")");
        }
        if($userEmail != null && $loggedType == 'login_cm'){
            $qb = $qb->andWhere("u.email IN (".$userEmail.")");
        }
        if($userEmail != null && $loggedType == null){
            $qb = $qb->andWhere("u.email NOT IN (".$userEmail.")");
        }
        $qb = $this->_applyFiltersClients($qb, $filters);
        $qb = $qb->getQuery()->getSingleScalarResult();

        return $qb;
    }



    private function _applyFiltersClients($qb, $filters){
        if($filters) {
            if (isset($filters["text"])) {
                if(trim($filters["text"]) != ''){

                    if(isset($filters['skills']) or isset($filters["regions"])){

                        $qb = $qb->orWhere("((packs.title LIKE :packText OR packs.description LIKE :packText)) OR CONCAT(u.firstname, ' ', u.lastname) LIKE :packText")->setParameter('packText','%'.$filters['text'].'%');

                    }else{

                        $qb = $qb->andWhere("((packs.title LIKE :packText OR packs.description LIKE :packText)) OR CONCAT(u.firstname, ' ', u.lastname) LIKE :packText")->setParameter('packText','%'.$filters['text'].'%');

                    }
                }
            }

            if(!isset($filters["text"]) || trim($filters["text"]) == '')
            {
                $filters["text"] = "__NO_KEYWORD_FOUND__";
            }

            if(isset($filters["regions"]) && trim($filters["regions"][0]) != '' && isset($filters["skills"]) && trim($filters["skills"][0]) != "")
            {
                $qb = $qb->andwhere("u.b2b_cm_approval = 1 AND (r.slug IN (:regions) OR s.id IN (:skills) OR packs.packSkill in (:skills) OR ((packs.title LIKE :packText OR packs.description LIKE :packText)) OR CONCAT(u.firstname, ' ', u.lastname) LIKE :packText)")->setParameter("regions", $filters["regions"])->setParameter("skills",$filters['skills'])->setParameter('packText','%'.$filters['text'].'%');
            }
            else
            {
                if (isset($filters["regions"])) {

                    if(trim($filters["regions"][0]) != '')
                    {
                        if((isset($filters['skills']) && $filters['skills'][0]  != '') or isset($filters["text"])){
//                            $qb = $qb->orWhere("r.slug IN (:regions) ")->setParameter("regions", $filters["regions"]);
                            $qb = $qb->andWhere("(r.slug IN (:regions) OR ((packs.title LIKE :packText OR packs.description LIKE :packText)) OR CONCAT(u.firstname, ' ', u.lastname) LIKE :packText)")->setParameter("regions", $filters["regions"])->setParameter('packText','%'.$filters['text'].'%');

                        }else{
//                            $qb = $qb->andWhere("r.slug IN (:regions)")->setParameter("regions", $filters["regions"]);
                        }

                    }

                }


                if(isset($filters['skills']))
                {
                    if(trim($filters["skills"][0]) != '')
                    {
                        if((isset($filters["regions"]) && $filters['regions'][0]  != '') or isset($filters["text"])){
//                            $qb = $qb->orWhere('s.id IN (:skills) OR packs.packSkill in (:skills)')->setParameter("skills",$filters['skills']);
                            $qb = $qb->andWhere('(s.id IN (:skills) OR packs.packSkill in (:skills)  OR ((packs.title LIKE :packText OR packs.description LIKE :packText)) OR CONCAT(u.firstname, \' \', u.lastname) LIKE :packText)')->setParameter("skills",$filters['skills'])->setParameter('packText','%'.$filters['text'].'%');
                        }else{
//                            $qb = $qb->andWhere('s.id IN (:skills) OR packs.packSkill in (:skills)')->setParameter("skills",$filters['skills']);

                        }

                    }
                }
            }

        }

        $qb->andwhere('packs.deleted IS NULL OR packs.deleted = 0');
        $qb->andWhere('u.b2b_cm_approval = :approval')->setParameter('approval', '1');

        return $qb;
    }


    public function searchPixiesFilters($filters = [])
    {
        $qb = $this->createQueryBuilder('u')
            ->select(["u", "avatar", "c", "p", "r", "category"])
            ->leftJoin('u.avatar', 'avatar')
            ->leftJoin('u.cards', 'cards')
            ->leftJoin('u.links', 'c')
            ->leftJoin('u.favoriteCategories', 'category')
            ->innerJoin('u.pixie', 'p')
            ->innerJoin('p.regions', 'r')

            ->where('u.deleted IS NULL OR u.deleted = 0')
            ->andWhere('u.visible = 1')
        ;

        $qb = $this->_applyFilters($qb, $filters);

        $qb = $qb->getQuery()->getResult();

        return $qb;
    }

    public function findByPixieRegion($regionId,$userEmail=null)
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.avatar', 'avatar')
            ->innerJoin('u.pixie', 'p')
            ->innerJoin('p.regions', 'r')
            ->where('r.id = :region OR r.slug = :region')
            ->andWhere('u.deleted IS NULL OR u.deleted = 0')
            ->andWhere('u.visible = 1')
            ;
        if($userEmail != null){
            $qb = $qb->andWhere("u.email NOT IN (".$userEmail.")");
        }
        $qb = $qb->setParameter('region', $regionId)
            ->getQuery()
            ->getResult()
            ;
        return $qb;
    }



    public function countPixieByRegion($regionId,$userEmail=null)
    {
        $qb = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->innerJoin('u.pixie', 'p')
            ->innerJoin('p.regions', 'r')
            ->where('r.id = :region')->setParameter('region', $regionId)
            ->andWhere('u.deleted IS NULL OR u.deleted = 0')
            ->andWhere('u.visible = 1');
        if($userEmail != null){
            $qb = $qb->andWhere("u.email NOT IN (".$userEmail.")");
        }
//        if($userEmail == null){
//            $qb = $qb->andWhere("u.email NOT IN ('ganesh@pix.city','bsingh@pix.cityy')");
//        }
        $qb = $qb->getQuery()
            ->useResultCache(true, 0, "countPixieByRegion_".$regionId)
            ->getSingleScalarResult()
            ;
        return $qb;
    }

    public function findRandomPixies($regionId = null,$userEmail=null)
    {
        $qb = $this->createQueryBuilder('u')
            ->select(["u", "c", "p", "r", "RAND() as HIDDEN rand"])
            ->join('u.cards','ca')
            ->leftJoin('u.links', 'c')
            ->innerJoin('u.pixie', 'p')
            ->innerJoin('p.regions', 'r')
            ->where('u.deleted IS NULL OR u.deleted = 0')
            ->where('ca.status = :status')->setParameter('status',CardStatus::VALIDATED)
            ->andWhere('u.visible = 1')
        ;

        if($regionId){
            $qb = $qb->andWhere("r.id = :region")->setParameter("region", $regionId);
        }
        if($userEmail != null){
            $qb = $qb->andWhere("u.email NOT IN (".$userEmail.")");
        }
//        if($userEmail == null){
//            $qb = $qb->andWhere("u.email NOT IN ('ganesh@pix.city','bsingh@pix.cityy')");
//        }
        return $qb
            ->groupBy('u.id')
            ->orderBy('rand')
            ->setMaxResults(5)
            ->getQuery()
            ->useResultCache(true, 0, "findRandomPixies")
            ->getResult()
            ;
    }

    private function _applyFilters($qb, $filters){
        if($filters) {
            if (isset($filters["text"])) {
                if(trim($filters["text"]) != ''){

                    //$qb = $qb->andWhere("CONCAT(u.firstname, ' ', u.lastname) LIKE :searchText")->setParameter("searchText", "%".$filters["text"]."%");
                    $qb = $qb->andWhere("CONCAT(u.firstname, ' ', u.lastname) LIKE :packText OR u.email LIKE :packText")->setParameter('packText','%'.$filters['text'].'%');
                }

            }

            if (isset($filters["regions"])) {
                if(trim($filters["regions"][0]) != '')
                {

                    $qb = $qb->innerJoin('p.regions', 'region')
                        ->andWhere("region.id IN (:regions) OR region.slug IN (:regions)")->setParameter("regions", $filters["regions"]);
                }
            }

            if (isset($filters["categories"])) {

                $qb = $qb->andWhere("category.id IN (:categories) OR category.slug IN (:categories)")->setParameter("categories", $filters["categories"]);
            }

            if (isset($filters["pixies"])) {

                $qb = $qb->andWhere("u.id IN (:pixies)")->setParameter("pixies", $filters["pixies"]);
            }

            if (isset($filters["pixie"])) {

                $qb = $qb->andWhere("u.id = :pixie")->setParameter("pixie", $filters["pixie"]);
            }

            if(isset($filters['roles']))
            {
                if(trim($filters["roles"]) != ''){
                    $qb = $qb->andWhere("u.roles LIKE :roles")->setParameter("roles", '%'.$filters["roles"].'%');
                }

            }

            if(isset($filters['skills']))
            {
                if(trim($filters["skills"][0]) != '')
                {
                    $qb = $qb->andWhere('s.id IN (:skills)')->setParameter("skills",$filters['skills']);
                }
            }
        }

        return $qb;
    }

    public function cityMakerInfo($id)
    {
        $qb = $this->createQueryBuilder('u')
            ->addSelect('u, n, p, m')
            ->leftJoin('u.notifications','n')
            ->leftJoin('u.userPacks','p')
            ->leftJoin('u.userMission','m')
            ->andWhere('u.id = :user')->setParameter('user',$id)
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function calculateLevel($id)
    {
        $qb = $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT c.id) as cards')
            ->leftJoin('u.cards','c')
            ->andWhere('u.id = :user')->setParameter('user',$id)
            ->andWhere('c.status = :status')->setParameter('status',CardStatus::VALIDATED)
            ->getQuery()
            ->getSingleScalarResult();

        if($qb == UserLevel::LEVEL_1)
        {
            return 1;
        }
        elseif ($qb > UserLevel::LEVEL_1 && $qb < UserLevel::LEVEL_2)
        {
            return 2;
        }
        elseif($qb >= UserLevel::LEVEL_2 && $qb < UserLevel::LEVEL_3)
        {
            return 3;
        }
        elseif($qb >= UserLevel::LEVEL_3 && $qb < UserLevel::LEVEL_4)
        {
            return 4;
        }
        else
        {
            return 5;
        }

        return false;
    }



}
