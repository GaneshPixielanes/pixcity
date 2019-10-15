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
            ->leftJoin('m.missionLogs', 'logs')
            ->setParameter('ongoing', MissionStatus::ONGOING)
            ->setParameter('cancel_requested', MissionStatus::CANCEL_REQUEST_INITIATED)
            ->setParameter('terimate_requested', MissionStatus::TERMINATE_REQUEST_INITIATED);
        if ($type == 'client') {
            $result = $result->andWhere('m.client = :user')->setParameter('user', $user)
                ->andWhere('m.missionAgreedClient = 1')
                ->andWhere('logs.isActive = 1');

        } else {
            $result = $result->andWhere('m.user = :user')->setParameter('user', $user);

        }

        $result = $result->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function findDraftAndOngoingMissions($user, $type = 'cm')
    {
        $result = $this->createQueryBuilder('m')
            ->where('m.status = :ongoing OR m.status = :cancel_requested OR m.status = :terimate_requested OR m.status = :draft')
            ->leftJoin('m.missionLogs', 'logs')
            ->setParameter('ongoing', MissionStatus::ONGOING)
            ->setParameter('cancel_requested', MissionStatus::CANCEL_REQUEST_INITIATED)
            ->setParameter('terimate_requested', MissionStatus::TERMINATE_REQUEST_INITIATED)
            ->setParameter('draft', MissionStatus::CREATED);
        if ($type == 'client') {
            $result = $result->andWhere('m.client = :user')->setParameter('user', $user)
                ->andWhere('m.missionAgreedClient = 1')
                ->andWhere('logs.isActive = 1');

        } else {
            $result = $result->andWhere('m.user = :user')->setParameter('user', $user);

        }

        $result = $result->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $result;
    }
    public function findMissionsWithLimit($filters = [], $user = null, $page = 1, $limit = 10)
    {
        return $this->createQueryBuilder('m')
            ->where('m.user = :user')->setParameter('user', $user)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
//                    ->andWhere()
    }

    public function findMissionForClient($user, $status, $page = 1, $limit = 10)
    {
        return $this->createQueryBuilder('m')
            ->where('m.client = :user')->setParameter('user', $user)
            ->andWhere('m.status = :status')->setParameter('status', $status)
            ->setFirstResult($limit * ($page - 1))
            ->getQuery()->getResult();
    }


    public function activePrices($mission)
    {

        return $this->createQueryBuilder('m')
            ->where('m.id = :mission')->setParameter('mission', $mission)
            ->leftJoin('m.missionLogs', 'logs')
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

    /**
     * Function used to create a slug associated to an "ugly" string.
     *
     * @param string $string the string to transform.
     *
     * @return string the resulting slug.
     */
    public function createSlug($string)
    {

        $table = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', '/' => '-', ' ' => '-'
        );

        // -- Remove duplicated spaces
        $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);

        // -- Returns the slug
        return strtolower(strtr($string, $table));


    }
}
