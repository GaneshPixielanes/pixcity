<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BlogPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    // /**
    //  * @return BlogPost[] Returns an array of BlogPost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogPost
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function blogPaginations($limit = 12, $page = 1, $isRandom = false)
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.category', 'category')
            ->leftJoin('u.createdBy','createdBy')
            ->where('u.deleted IS NULL')
            ->andWhere('u.postStatus = 1')
        ;

        if($isRandom == true)
        {
            $qb = $qb->orderBy('RAND()');
        }
        else
        {
            $qb = $qb->orderBy('u.id','DESC');
        }

        $qb = $qb->groupBy('u.id');

        $qb = $qb->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        $qb = $qb->getQuery()->getResult();

        return $qb;
    }

    public function blogCount($limit, $page)
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.category', 'category')
            ->leftJoin('u.createdBy','createdBy')
            ->where('u.deleted IS NULL')
            ->andWhere('u.postStatus = 1')
            ->select('COUNT(DISTINCT u.id)');

        $qb = $qb->getQuery()->getSingleScalarResult();

        return $qb;
    }
    public function findBlogBySlug($slug)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin("c.category", "p")
            ->leftJoin("c.createdBy", "t")
            ->andWhere('c.slug = :slug')->setParameter('slug', $slug)
            ->andWhere('c.deleted IS NULL OR c.deleted = 0')
            ->andWhere('c.postStatus = 1')
            ->getQuery()
            ->useResultCache(true, 0, "findBlogBySlug_".$slug)
            ->getOneOrNullResult()
            ;
    }
    public function bannerHeader()
    {
        return $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.category', 'category')
            ->leftJoin('u.createdBy','createdBy')
            ->where('u.deleted IS NULL')
            ->andWhere('u.postStatus = 1')
            ->andWhere('u.position = 1')
            ->orderBy('u.updatedAt','DESC')
            ->setMaxResults(1)
            ->getQuery()->getResult();
        ;
    }
}
