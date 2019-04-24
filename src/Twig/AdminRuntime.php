<?php
namespace App\Twig;

use App\Constant\CardStatus;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;

class AdminRuntime
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findPendingCards(){
        $cards = $this->em->getRepository(Card::class);
        $cardsPending = $cards->createQueryBuilder("c")
            ->andWhere('c.status = :status')->setParameter('status', CardStatus::SUBMITTED)
            ->getQuery()
            ->getResult()
        ;
        return $cardsPending;
    }
}