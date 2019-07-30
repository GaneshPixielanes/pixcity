<?php

namespace App\Service;

use App\Entity\Option;
use App\Repository\ClientTransactionRepository;
use App\Repository\UserMissionRepository;
use MangoPay;
use MangoPay\DemoWorkflow\MockStorageStrategy;
use Symfony\Component\HttpFoundation\Session\Session;

class MangoPayService
{

    private $mangoPayApi;

    public function __construct()
    {
        $this->mangoPayApi = new MangoPay\MangoPayApi();
        $this->mangoPayApi->Config->ClientId = 'azimforexprod';
        $this->mangoPayApi->Config->ClientPassword = '5ahxUPFNpzuBz0kK3P0Fwt6DeK2s6P44530LKLF1anLp3N5yWK';
//        $this->mangoPayApi->OAuthTokenManager->RegisterCustomStorageStrategy(new MockStorageStrategy());
        $this->mangoPayApi->Config->TemporaryFolder = "uploads/mangopay/";
//        $this->mangoPayApi->OAuthTokenManager->RegisterCustomStorageStrategy(new MockStorageStrategy());
        $this->mangoPayMoney = new MangoPay\Money();
        $this->mangoPayRefund = new MangoPay\Refund();
    }

    public function createUser(MangoPay\UserNatural $userNatural)
    {
        return $this->mangoPayApi->Users->Create($userNatural);
    }


    public function getUser($userId)
    {
        return $this->mangoPayApi->Users->Get($userId);
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

    public function getWalletId($user)
    {
        return $this->mangoPayApi->Wallets->Get($user);
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


    public function refundPayment($transaction,$amount,$refund_amount){
        //dd($amount.' '.$refund_amount);
        $PayInId = $transaction[0]->getMangopayTransactionId();

        $Refund = $this->mangoPayRefund;
        $Refund->AuthorId = $transaction[0]->getMangopayUserId();
        $Refund->DebitedFunds = $this->mangoPayMoney;
        $Refund->DebitedFunds->Currency = "EUR";
        $Refund->DebitedFunds->Amount = $amount;

        $Refund->Fees = $this->mangoPayMoney;
        $Refund->Fees->Currency = "EUR";
        $Refund->Fees->Amount = $refund_amount;

        $reponse = $this->mangoPayApi->PayIns->CreateRefund($PayInId, $Refund);

        return $reponse->ResultMessage;
    }

}