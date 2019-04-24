<?php

namespace App\Controller\Front\Pages\Pixie;

use App\Constant\CardProjectStatus;
use App\Constant\CompanyStatus;
use App\Constant\TransactionStatus;
use App\Constant\TransactionType;
use App\Entity\Transaction;
use App\Repository\CardProjectRepository;
use App\Repository\TransactionRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/city-maker/transactions", name="front_pixie_transaction_")
 * @Security("has_role('ROLE_PIXIE')")
 */

class PixieTransactionController extends Controller
{
    private $logger;
    private $transactionRepo;
    private $projectsRepo;
    private $em;
    private $mailer;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, TransactionRepository $transactionRepo, CardProjectRepository $projectsRepo, Mailer $mailer)
    {
        $this->logger = $logger;
        $this->transactionRepo = $transactionRepo;
        $this->projectsRepo = $projectsRepo;
        $this->em = $em;
        $this->mailer = $mailer;
    }

    private function transactionLog(Transaction $transaction, $message, $datas = [], $important = false){
        if(!$important) {
            $this->logger->notice("TRANSACTION_LOG : ID " . $transaction->getId() . " : " . $message, $datas);
        }
        else{
            $this->logger->error("TRANSACTION_LOG : ID " . $transaction->getId() . " : " . $message, $datas);
        }
    }

    private function _findUnpayedCards(){
        $filters = [
            "pixie" => $this->getUser()->getId(),
            "status" => [CardProjectStatus::VALIDATED],
            "no_transaction" => true
        ];
        $unpayedProjects = $this->projectsRepo->search($filters, 1, 100);

        return $unpayedProjects;
    }

    private function _getUnpayedTotal($unpayedProjects){
        $totalPayementPending = 0;
        foreach($unpayedProjects as $project){
            $totalPayementPending += $project->getPrice();
        }

        return $totalPayementPending;
    }

    private function _createNewTransaction($unpayedProjects, $totalPayementPending, $type, $status){

        $user = $this->getUser();
        $pixieBilling = $user->getPixie()->getBilling();

        if(CompanyStatus::ONGOING_REGISTRATION === $pixieBilling->getStatus()){
            // Pixie cannot ask for payment without having a company registered
            return false;
        }

        // Count total transaction of the month
        $totalUserTransactions = $this->transactionRepo->countSearchResult(["pixie" => $this->getUser()->getId(), "current_month" => true]);
        $totalUserTransactions++;

        // Create the new transaction
        $transaction = new Transaction();
        $transaction->setName("C".$this->getUser()->getId()."-".date('Y').'-'.date('m')."-".$totalUserTransactions);
        $transaction->setTotal($totalPayementPending);
        $transaction->setProjects($unpayedProjects);
        $transaction->setType($type);
        $transaction->setStatus($status);
        $transaction->setUser($this->getUser());

        // Save the transaction
        $this->em->persist($transaction);
        $this->em->flush();

        // Generate the invoice
        try {
            $invoicePath = "../invoices/" . $transaction->getName() . ".pdf";
            $this->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('front/account/pixie/invoice.html.twig',
                    array(
                        'transaction' => $transaction
                    )
                ), $invoicePath
            );
        }
        catch(Exception $e){
            $this->transactionLog($transaction, "could not create invoice");
        }

        $this->transactionLog($transaction, "Pixie (".$this->getUser()->getId().") created a new Transaction");

        //----------------------------------------
        // Send confirmation email

        $this->mailer->send($this->getUser()->getEmail(), 'Ta demande de paiement a été enregistrée', 'emails/pixie-card-payment-request.html.twig', [
            'firstname' => $this->getUser()->getFirstname()
        ]);

        $this->mailer->send($this->getParameter("email_admin"), 'Nouvelle demande de paiement', 'emails/admin-card-payment-request.html.twig', [
            'pixie' => $this->getUser()->getFirstname()." ".$this->getUser()->getLastname()
        ]);

        return $transaction;
    }


    /**
     * @Route("/demande-paiement-cheque", name="check_payment")
     */
    public function check_payment(Request $request)
    {
        $unpayedProjects = $this->_findUnpayedCards();
        $totalPayementPending = $this->_getUnpayedTotal($unpayedProjects);

        if($totalPayementPending < 20){
            $this->addFlash('error_transaction', 'error.transaction.min_amount');
            return $this->redirectToRoute("front_pixie_account_cards_payments");
        }

        // Create check transaction
        $transaction = $this->_createNewTransaction($unpayedProjects, $totalPayementPending, TransactionType::CHECK, TransactionStatus::PIXIE_ASKED_CHECK_PAYMENT);

        return $this->redirectToRoute("front_pixie_account_cards_payments");
    }

    /**
     * @Route("/demande-paiement-virement", name="banktransfer_payment")
     */
    public function banktransfer_payment(Request $request)
    {
        $unpayedProjects = $this->_findUnpayedCards();
        $totalPayementPending = $this->_getUnpayedTotal($unpayedProjects);

        if($totalPayementPending < 20){
            $this->addFlash('error_transaction', 'error.transaction.min_amount');
            return $this->redirectToRoute("front_pixie_account_cards_payments");
        }

        // Create bank transfer transaction
        $transaction = $this->_createNewTransaction($unpayedProjects, $totalPayementPending, TransactionType::BANKTRANSFER, TransactionStatus::PIXIE_ASKED_BANKTRANSFER_PAYMENT);

        return $this->redirectToRoute("front_pixie_account_cards_payments");
    }




    /**
     * @Route("/facture/{id}", name="invoice")
     */
    public function invoice(Request $request, TransactionRepository $transactionRepo, CardProjectRepository $projectsRepo)
    {
        $filters = [
            "pixie" => $this->getUser()->getId(),
            "id" => $request->get("id")
        ];
        $transactions = $transactionRepo->search($filters);

        if(1 === count($transactions)){
            $transaction = $transactions[0];
            $invoiceFile = $transaction->getName().".pdf";
            $invoicePath = "../invoices/".$invoiceFile;

            $response = new Response(file_get_contents($invoicePath));
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $invoiceFile . '"');
            $response->headers->set('Content-length', filesize($invoicePath));

            return $response;
        }

        return $this->redirectToRoute("front_pixie_account_cards_payments");
    }

}