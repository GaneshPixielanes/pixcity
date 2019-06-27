<?php

namespace App\Repository;

use App\Constant\MissionStatus;
use App\Entity\UserMission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserMission|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMission|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMission[]    findAll()
 * @method UserMission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMissionRepository extends ServiceEntityRepository
{
    private $em;
    public function __construct(RegistryInterface $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, UserMission::class);
        $this->em = $entityManager;
    }

    public function findOngoingMissions($user, $type = 'cm')
    {
        $result = $this->createQueryBuilder('m')
                        ->where('m.status = :ongoing OR m.status = :cancel_requested OR m.status = :terimate_requested')
                        ->setParameter('ongoing',MissionStatus::ONGOING)
                        ->setParameter('cancel_requested',MissionStatus::CANCEL_REQUEST_INITIATED)
                        ->setParameter('terimate_requested',MissionStatus::TERMINATE_REQUEST_INITIATED);
                        if($type == 'client')
                        {
                            $result = $result->andWhere('m.client = :user')->setParameter('user',$user )
                                             ->andWhere('m.missionAgreedClient = 1');

                        }
                        else
                        {
                            $result = $result->andWhere('m.user = :user')->setParameter('user',$user );
                        }

        $result = $result->orderBy('m.id','DESC')
                        ->getQuery()
                        ->getResult();

        return $result;
    }

    public function findMissionsWithLimit($filters= [], $user = null, $page = 1, $limit = 10)
    {
        return $this->createQueryBuilder('m')
                    ->where('m.user = :user')->setParameter('user', $user)
                    ->setFirstResult($limit * ($page - 1))
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
//                    ->andWhere()
    }

    public function findMissionForClient($user, $status, $page =1, $limit = 10)
    {
        return $this->createQueryBuilder('m')
                    ->where('m.client = :user')->setParameter('user', $user)
                    ->andWhere('m.status = :status')->setParameter('status', $status)
                    ->setFirstResult($limit * ($page - 1))
                    ->getQuery()->getResult();
    }


    public function activePrices($mission){

        return $this->createQueryBuilder('m')
            ->where('m.id = :mission')->setParameter('mission', $mission)
            ->leftJoin('m.missionLogs','logs')
            ->andWhere('logs.isActive = 1')
            ->getQuery()->getOneOrNullResult();

    }

    // /**
    //  * @return UserMission[] Returns an array of UserMission objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserMission
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
