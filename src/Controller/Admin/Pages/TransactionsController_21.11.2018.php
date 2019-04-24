<?php

namespace App\Controller\Admin\Pages;

use App\Constant\BillingMethod;
use App\Constant\CardProjectStatus;
use App\Constant\CompanyStatus;
use App\Constant\TransactionStatus;
use App\Entity\Address;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\UserPixieBilling;
use App\Repository\CardProjectRepository;
use App\Repository\OptionRepository;
use App\Repository\TransactionRepository;
use App\Service\Mailer;
use App\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Psr\Log\LoggerInterface;
use RevolutPHP\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/transactions", name="admin_transactions_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class TransactionsController extends Controller
{
    private $logger;
    private $projectsRepo;

    public function __construct(LoggerInterface $logger, CardProjectRepository $projectsRepo)
    {
        $this->logger = $logger;
        $this->projectsRepo = $projectsRepo;
    }

    private function transactionLog(Transaction $transaction, $message, $datas = [], $important = false){
        if(!$important) {
            $this->logger->notice("TRANSACTION_LOG : ID " . $transaction->getId() . " : " . $message, $datas);
        }
        else{
            $this->logger->error("TRANSACTION_LOG : ID " . $transaction->getId() . " : " . $message, $datas);
        }
    }

    private function _getUnpayedTotal($unpayedProjects){
        $totalPayementPending = 0;
        foreach($unpayedProjects as $project){
            $totalPayementPending += $project->getPrice();
        }

        return $totalPayementPending;
    }

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(Request $request, TransactionRepository $transactions)
    {
        $query = $transactions->createQueryBuilder("t")
            ->select(["t", "u"])
            ->leftJoin('t.user', 'u')
        ;

        $query = $query->orderBy('t.id', 'DESC');
        $list = $query->getQuery()->getResult();

        return $this->render('admin/transactions/index.html.twig', [
            'list' => $list
        ]);
    }

    /**
     * @Route("/{id}/paid-by-check", name="paid_by_check")
     * @Method({"POST"})
     */
    public function paidByCheck(Request $request, Transaction $transaction)
    {
        if (!$this->isCsrfTokenValid('paid', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_transactions_list');
        }

        if(TransactionStatus::PIXIE_ASKED_CHECK_PAYMENT === $transaction->getStatus()) {
            $transaction->setStatus(TransactionStatus::CHECK_SUCCESS);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('admin_transactions_list');

    }

    /**
     * @Route("/{id}/paid-by-bank-transfer", name="paid_by_bank_transfer")
     * @Method({"POST"})
     */
    public function paidByBankTransfer(Request $request, Transaction $transaction, EntityManagerInterface $em, Client $revolut, OptionRepository $optionRepo, Mailer $mailer)
    {

        if (!$this->isCsrfTokenValid('paid', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_transactions_list');
        }

        if(TransactionStatus::PIXIE_ASKED_BANKTRANSFER_PAYMENT === $transaction->getStatus()) {

            if($transaction instanceof Transaction) {

                $user = $transaction->getUser();
                $pixieBilling = $user->getPixie()->getBilling();
                $revolutCounterpartyId = null;
                $totalPayementPending = $this->_getUnpayedTotal($transaction->getProjects());

                if(CompanyStatus::ONGOING_REGISTRATION === $pixieBilling->getStatus()){
                    // Pixie cannot ask for payment without having a company registered
                    $this->addFlash('error', 'No company informations defined');
                    return $this->redirectToRoute('admin_transactions_list');
                }

                //---------------------------------------------------
                // Get or update the Revolut Counterparty
                //---------------------------------------------------

                if ($pixieBilling instanceof UserPixieBilling && $user instanceof User) {

                    if (BillingMethod::BANK_TRANSFER !== $pixieBilling->getBillingType()) {
                        $this->addFlash('error', 'Wrong billing method');
                        return false;
                    }

                    if (empty($pixieBilling->getRevolutId()) || $pixieBilling->getNeedRevolutUpdate()) {

                        //----------------------------------------------------
                        // Need to create a new Revolut counterparty for the first transaction or if the pixie changed his billing informations

                        $counterpartyDatas = [
                            "bank_country" => $pixieBilling->getBillingCountry(),
                            "currency" => "EUR",
                            "email" => $user->getEmail(),
                            "phone" => $pixieBilling->getPhone(),
                            "iban" => $pixieBilling->getBillingIban(),
                            "bic" => $pixieBilling->getBillingBic(),
                        ];

                        if (CompanyStatus::COMPANY === $pixieBilling->getStatus()) {
                            $counterpartyDatas["company_name"] = Utils::remove_accents($pixieBilling->getCompanyName());
                        } else {
                            $counterpartyDatas["individual_name"] = [
                                "first_name" => Utils::remove_accents($pixieBilling->getFirstname()),
                                "last_name" => Utils::remove_accents($pixieBilling->getLastname())
                            ];
                        }

                        $pixieAddress = $pixieBilling->getAddress();
                        if ($pixieAddress instanceof Address) {
                            $counterpartyDatas["address"] = [
                                "street_line1" => Utils::remove_accents($pixieAddress->getAddress()),
                                "postcode" => $pixieAddress->getZipcode(),
                                "city" => Utils::remove_accents($pixieAddress->getCity()),
                                "country" => $pixieBilling->getBillingCountry()
                            ];
                        }

                        try {
                            $counterparty = $revolut->counterparties->create($counterpartyDatas);
                            $revolutCounterpartyId = $counterparty->id;

                            //---------------------------------------------------
                            // Update user Revolut ID

                            $pixieBilling->setRevolutId($revolutCounterpartyId);
                            $pixieBilling->setNeedRevolutUpdate(false);

                            $em->persist($pixieBilling);
                            $em->flush();

                        } catch (Exception $e) {
                            $this->addFlash('error', 'error.transaction.create_counterparty');
                        }
                    } else {
                        $revolutCounterpartyId = $pixieBilling->getRevolutId();
                    }
                }
                else{
                    $this->addFlash('error', 'No billing infos');
                }

                //---------------------------------------------------
                // Create the payment
                //---------------------------------------------------

                if ($revolutCounterpartyId && $transaction->getName()) {

                    // Retrieve account ID to pay from (from options)
                    $fromAccount = $optionRepo->findOneBy(["slug" => "revolut_account_id"]);
                    $fromAccountId = $fromAccount->getValue();

                    // Create the payment
                    $paymentError = null;

                    try {
                        $payment = $revolut->payments->create([
                            'request_id' => $transaction->getName(),
                            'account_id' => $fromAccountId,
                            'receiver' => [
                                'counterparty_id' => $revolutCounterpartyId,
                            ],
                            'amount' => $totalPayementPending,
                            'currency' => 'EUR',
                            'reference' => $transaction->getName()
                        ]);
                    }
                    catch(Exception $e){
                        $paymentError = [
                            "code" => $e->getCode(),
                            "message" => $e->getMessage()
                        ];
                    }

                    if($paymentError){
                        $transaction->setStatus(TransactionStatus::BANKTRANSFER_ERROR);
                        $this->transactionLog($transaction, "bank transfer error", $paymentError, true);
                    }else{
                        $transaction->setStatus(TransactionStatus::BANKTRANSFER_SUCCESS);
                        $this->transactionLog($transaction, "bank transfer success");
                    }

                    $em->persist($transaction);
                    $em->flush();


                    //----------------------------------------
                    // Send payment confirmation email

                    $mailer->send($this->getUser()->getEmail(), 'Ton paiement a été effectué', 'emails/pixie-card-payment-done.html.twig', [
                        'firstname' => $this->getUser()->getFirstname(),
                        'date' => date('d/m/Y à H:hi')
                    ]);


                } else {
                    $this->addFlash('error', 'error.transaction.no_revolut_id');
                }

            }
            else{
                $this->addFlash('error', 'No transactions');
            }

        }
        else{
            $this->addFlash('error', 'Wrong billing type');
        }

        return $this->redirectToRoute('admin_transactions_list');

    }

    /**
     * @Route("/revolut", name="revolut_debug")
     */
    public function revolut(Request $request, Client $revolut)
    {
        //dump($revolut->counterparties->all());
        //dump($revolut->transactions->all());
        //dump($revolut->accounts->all());

        return $this->render('admin/transactions/revolut.html.twig', [

        ]);
    }

    /**
     * @Route("/facture/{id}", name="invoice")
     */
    public function invoice(Request $request, TransactionRepository $transactionRepo)
    {
        $filters = [
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

        return $this->redirectToRoute("admin_transactions_list");
    }

    /**
     * @Route("/facture/{id}/refresh", name="invoice_refresh")
     */
    public function invoice_refresh(Request $request, TransactionRepository $transactionRepo)
    {
        $filters = [
            "id" => $request->get("id")
        ];
        $transactions = $transactionRepo->search($filters);

        if(1 === count($transactions)){
            $transaction = $transactions[0];

            $invoicePath = "../invoices/" . $transaction->getName() . ".pdf";
            $this->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('front/account/pixie/invoice.html.twig',
                    array(
                        'transaction' => $transaction
                    )
                ), $invoicePath
            );
        }

        return $this->redirectToRoute("admin_transactions_list");
    }

}