<?php

namespace App\Repository;

use App\Entity\CardCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CardCollection|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardCollection|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardCollection[]    findAll()
 * @method CardCollection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardCollectionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CardCollection::class);
    }

    public function findUserCollections($user)
    {
        return $this->createQueryBuilder('collection')
            ->select("collection, notes, c, p, r, t, address, category, pixie, pixieregions")
            ->leftJoin("collection.notes", "notes")
            ->leftJoin("collection.cards", "c")
                ->leftJoin("c.pixie", "p")
                    ->leftJoin("p.pixie", "pixie")
                        ->leftJoin("pixie.regions", "pixieregions")
                ->leftJoin("c.thumb", "t")
                ->leftJoin("c.region", "r")
                ->leftJoin("c.address", "address")
                ->leftJoin("c.categories", "category")

            ->where('collection.user = :user')->setParameter('user', $user)
            ->orderBy('collection.createdAt', 'DESC')
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult()
        ;
    }

    public function findBySlugOrUuid($slug)
    {
        return $this->createQueryBuilder('collection')
            ->select("collection, notes, c, p, r, t, address, category, pixie, pixieregions, user")
            ->leftJoin("collection.notes", "notes")
            ->leftJoin("collection.cards", "c")
            ->leftJoin("c.pixie", "p")
            ->leftJoin("p.pixie", "pixie")
            ->leftJoin("pixie.regions", "pixieregions")
            ->leftJoin("c.thumb", "t")
            ->leftJoin("c.region", "r")
            ->leftJoin("c.address", "address")
            ->leftJoin("c.categories", "category")
            ->leftJoin("collection.user", "user")

            ->where('collection.slug = :slug OR collection.uuid = :slug')->setParameter('slug', $slug)

            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getOneOrNullResult()
            ;
    }

}
