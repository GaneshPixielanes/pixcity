<?php

namespace App\Service;

use App\Repository\ClientTransactionRepository;
use MangoPay;
use MangoPay\DemoWorkflow\MockStorageStrategy;
class MangoPayService
{

    private $mangoPayApi;

    public function __construct()
    {
        $this->mangoPayApi = new MangoPay\MangoPayApi();
        $this->mangoPayApi->Config->ClientId = 'azimforexprod';
        $this->mangoPayApi->Config->ClientPassword = '5ahxUPFNpzuBz0kK3P0Fwt6DeK2s6P44530LKLF1anLp3N5yWK';
//        $this->mangoPayApi->OAuthTokenManager->RegisterCustomStorageStrategy(new MockStorageStrategy());
        $this->mangoPayApi->Config->TemporaryFolder = "C:\mangopay";
//        $this->mangoPayApi->OAuthTokenManager->RegisterCustomStorageStrategy(new MockStorageStrategy());
    }

    public function createUser(MangoPay\UserNatural $userNatural)
    {
        return $this->mangoPayApi->Users->Create($userNatural);
    }

    /*
     * Create Mangopay User
     * @return MangopPayUser $mangoUser
     */
    public function getMangoUser()
    {

        $mangoUser = new MangoPay\UserNatural();
        $mangoUser->PersonType = "NATURAL";
        $mangoUser->FirstName = 'John';
        $mangoUser->LastName = 'Doe';
        $mangoUser->Birthday = 1409735187;
        $mangoUser->Nationality = "FR";
        $mangoUser->CountryOfResidence = "FR";
        $mangoUser->Email = 'ganesh-new@yopmail.com';
        //Send the request
        $mangoUser = $this->mangoPayApi->Users->Create($mangoUser);

        return $mangoUser;
    }

    public function getWallet($user)
    {
        $wallet = new MangoPay\Wallet();
        $wallet->Owners = [$user];
        $wallet->Description = 'Demo wallet';
        $wallet->Currency = 'EUR';

        return $this->mangoPayApi->Wallets->Create($wallet);
    }

    public function getPayIn($mangoUser, $wallet, $amount, $transaction)
    {

        $payIn = new MangoPay\PayIn();
        $payIn->CreditedWalletId = $wallet->Id;
        $payIn->AuthorId = $mangoUser->Id;
        $payIn->PaymentType = MangoPay\PayInPaymentType::Card;
        $payIn->PaymentDetails = new MangoPay\PayInPaymentDetailsCard();
        $payIn->PaymentDetails->CardType = "CB_VISA_MASTERCARD";
        $payIn->DebitedFunds = new MangoPay\Money();
        $payIn->DebitedFunds->Currency = "EUR";
        $payIn->DebitedFunds->Amount = $amount;
        $payIn->Fees = new MangoPay\Money();
        $payIn->Fees->Currency = "EUR";
        $payIn->Fees->Amount = 0;
        $payIn->ExecutionType = MangoPay\PayInExecutionType::Web;
        $payIn->ExecutionDetails = new MangoPay\PayInExecutionDetailsWeb();
        $payIn->ExecutionDetails->ReturnURL = "http".(isset($_SERVER['HTTPS']) ? "s" : null)."://".$_SERVER["HTTP_HOST"]."/client/mission/mission-accept-process/".$transaction;
        $payIn->ExecutionDetails->Culture = "EN";

        $result =  $this->mangoPayApi->PayIns->Create($payIn);

        return $result->ExecutionDetails->RedirectURL;
    }


    public function  getResponse($payincardweb){

        $response = $this->mangoPayApi->PayIns->Get($payincardweb);

        return $response;


    }


    public function refundPayment($mission_id,ClientTransactionRepository $clientTransactionRepository){

        $transaction = $clientTransactionRepository->findBy(['mission' => $mission_id]);

        $PayInId = $transaction[0]->getMangopayUserId();
        $Refund = $this->mangoPayApi->Refunds();
        $Refund->AuthorId = $row[0];
        $Refund->DebitedFunds = $this->mangoPayApi->Money();
        $Refund->DebitedFunds->Currency = "EUR";
        $Refund->DebitedFunds->Amount = $row[3]*100;
        $Refund->Fees = new \MangoPay\Money();
        $Refund->Fees->Currency = "EUR";
        $Refund->Fees->Amount = $_POST['amount']*100;
        $result = $mangoPayApi->PayIns->CreateRefund($PayInId, $Refund);


    }

}