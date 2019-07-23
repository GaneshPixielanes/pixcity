
<?php

namespace App\Repository;

use App\Constant\CompanyStatus;
use App\Entity\MissionPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MissionPayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissionPayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissionPayment[]    findAll()
 * @method MissionPayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionPaymentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MissionPayment::class);
    }

    public function getPrices($price, $margin, $tax, $citymakerType)
    {
        if($citymakerType != CompanyStatus::COMPANY)
        {
            /* Get CM price details */
            $result['cm_price'] = $price;
            $result['cm_tax'] = 0;
            $result['cm_total'] = $price;

            /* Get client price details*/
            $result['client_price'] = (100 * $price)/(100 - $margin);
            $result['client_tax'] = 0;
            $result['client_total'] = $result['client_price'];



            /* Get Pix City Services details */
            $result['pcs_price'] = (($result['client_price'] - $price)/100)*(100-16.66667);
            $result['pcs_tax'] = (($result['client_price'] - $price)/100)* 16.66667;
            $result['pcs_total'] = $result['pcs_price'] + $result['pcs_tax'];


        }
        else
        {

            $result['cm_price']=  $price;
            $result['cm_total'] = $price + ($price * ($tax/100));
            $result['cm_tax'] = $result['cm_total'] - $price;

            /* Get client price details*/
            $result['client_price'] = $price/(100 - $margin) * 100;
            $result['client_tax'] = $result['client_price'] * $tax/100;
            $result['client_total'] = $result['client_price'] + $result['client_tax'];


            /* Get Pix City Services details */
            $result['pcs_price'] = $result['client_price'] - $price;
            $result['pcs_tax']  = $result['pcs_price'] * ($tax/100);
            $result['pcs_total'] = $result['pcs_price'] + $result['pcs_tax'];


        }

        return $result;
    }
    // /**
    //  * @return MissionPayment[] Returns an array of MissionPayment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MissionPayment
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
