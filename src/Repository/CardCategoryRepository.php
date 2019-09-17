<?php

namespace App\Repository;

use App\Entity\CardCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Constant\CardStatus;
/**
 * @method CardCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardCategory[]    findAll()
 * @method CardCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardCategory::class);
    }

    public function findAllActive()
    {
        return $this->createQueryBuilder('c')
            ->select(["c"])
            ->where('c.hidden = false')
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->useResultCache(true, 0, "findAllCardCategoriesActive")
            ->getResult()
        ;
    }

    public function findAllActiveWithCards($userEmail=null)
    {
        $qb = $this->createQueryBuilder('c')
          ->select(["c",'count(ca) AS cardCount'])
          ->join('c.cards','ca')
          ->leftJoin("ca.pixie", "uid")
          ->andWhere('c.hidden = false')
          ->andWhere('ca.status = :status')->setParameter('status',CardStatus::VALIDATED)
          ->orderBy('c.position', 'ASC')
          ->groupBy('c.id');

        if($userEmail != null){
            $qb = $qb->andWhere("uid.email NOT IN (".$userEmail.") AND uid.visible = 1 ");
        }
//        if($userEmail == null){
//            $qb = $qb->andWhere("uid.email NOT IN ('ganesh@pix.city','bsingh@pix.cityy')");
//        }
        return $qb->getQuery()
          ->useResultCache(true, 0, "findAllCardCategoriesActive")
          ->getResult()
      ;
    }

    public function findAllActiveWithCardsDesc()
    {
      return $this->createQueryBuilder('c')
          ->select(["c, count(ca)"])
          ->join('c.cards','ca')
          ->andWhere('c.hidden = false')
          ->andWhere('ca.status = :status')->setParameter('status',CardStatus::VALIDATED)
          ->orderBy('c.position', 'ASC')
          ->groupBy('c.id')
          ->getQuery()
          // ->useResultCache(true, 0, "findAllCardCategoriesActive")
          ->getResult()
      ;
    }

    public function findCategoriesByRegion($region,$userEmail=null)
    {
      $qb = $this->createQueryBuilder('c')
          ->select(["c, ca",'count(ca) AS cardCount'])
          ->join('c.cards','ca')
          ->join('ca.region','r')
          ->leftJoin('ca.pixie','uid')
          ->andWhere('ca.region = :region')
          ->andWhere('c.hidden = false')
          ->andWhere('ca.status = :status')
          ->setParameter('region',$region->getId())
          ->setParameter('status',CardStatus::VALIDATED);

        if($userEmail != null){
            $qb = $qb->andWhere("uid.email NOT IN (".$userEmail.") AND uid.visible = 1");
        }
        $qb = $qb
          ->orderBy('c.id')
          ->orderBy('c.position', 'ASC')
          ->groupBy('c.id')
          ->getQuery()
          ->getResult();
        return $qb;
    }

    public function findCategoriesBySearchParam($filters,$userEmail=null)
    {
      // dd($search);
      $result = $this->createQueryBuilder('c')
          ->select(["c, ca",'count(ca) AS cardCount'])
          ->join('c.cards','ca')
          ->join('ca.region','r')
          ->leftJoin("ca.pixie", "uid")
          ->andWhere('ca.name LIKE :search OR r.name LIKE :search OR c.name IN (:search) OR ca.content LIKE :content')
          ->andWhere('ca.status = :status')
          ->setParameter('status',CardStatus::VALIDATED)
          ->setParameter('content', '%'.htmlentities($filters['text']).'%')
          ->setParameter('search','%'.$filters['text'].'%');
					if(!empty($filters['regions']) && $filters['regions'] != null && $filters['categories'] != 'all')
					{
						$result = $result->andWhere('r.slug IN (:region)')->setParameter('region',$filters['regions']);
					}
        if($userEmail != null){
            $result = $result->andWhere("uid.email NOT IN (".$userEmail.") AND uid.visible = 1");
        }
//        if($userEmail == null){
//            $result = $result->andWhere("uid.email NOT IN ('ganesh@pix.city','bsingh@pix.cityy')");
//        }
     $result = $result->orderBy('c.id')
          ->orderBy('c.position', 'ASC')
          ->groupBy('c.id')
          ->getQuery()
          ->getResult()
      ;

			return $result;
    }

    public function findCategoriesByCityMaker($id)
    {
      // dd($search);
      return $this->createQueryBuilder('c')
          ->select(["c, ca",'count(ca) AS cardCount'])
          ->join('c.cards','ca')
          ->join('ca.pixie', 'u')
          ->andWhere('u.id = :user')
          ->andWhere('ca.status = :status')
          ->andWhere('c.hidden = false')
          ->setParameter('status',CardStatus::VALIDATED)
          ->setParameter('user',$id)
          ->orderBy('c.position', 'ASC')
          ->groupBy('c.id')
          ->getQuery()
          ->getResult()
      ;
    }

    public function findCategoriesByFavorites($id)
    {
      return $this->createQuerybuilder('c')
            ->select(["c, ca",'count(ca) AS cardCount'])
            ->join('c.cards','ca')
            ->join('ca.pixie','u')
            ->join('ca.favoriteUsers','f')
            ->andWhere('f.id = :user')->setParameter('user',$id)
            ->andWhere('ca.status = :status')->setParameter('status', CardStatus::VALIDATED)
            ->orderBy('c.position', 'ASC')
            ->groupBy('c.id')
            ->getQuery()
            ->getResult();
    }
}
