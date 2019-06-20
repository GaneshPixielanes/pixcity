<?php

namespace App\Controller\B2B\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/client/transaction", name="client_transaction_")
 */
class TransactionController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('b2b/client/transaction/index.html.twig');
    }
}
