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
        $this->mangoPayApi->Config->TemporaryFolder = "C:\mangopay";
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

    public function getPayIn($mangoUser, $wallet, $amount, $transaction, $mission,$fee)
    {

        $payIn = new MangoPay\PayIn();
        $payIn->CreditedWalletId = $wallet->Id;
        $payIn->AuthorId = $mangoUser->Id;
        $payIn->Tag =$mission;
        $payIn->PaymentType = MangoPay\PayInPaymentType::Card;
        $payIn->PaymentDetails = new MangoPay\PayInPaymentDetailsCard();
        $payIn->PaymentDetails->CardType = "CB_VISA_MASTERCARD";
        $payIn->DebitedFunds = new MangoPay\Money();
        $payIn->DebitedFunds->Currency = "EUR";
        $payIn->DebitedFunds->Amount = $amount;
        $payIn->Fees = new MangoPay\Money();
        $payIn->Fees->Currency = "EUR";
        $payIn->Fees->Amount = $fee;
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


    public function refundPayment($transaction,int $amount,int $refund_amount){

        $basePrice = $amount * 100;
        $percentage = (2 / 100) * $basePrice;
        $fees = (int) $percentage;

        $debitedFund = $refund_amount * 100;

        $PayInId = $transaction[0]->getMangopayTransactionId();

        $Refund = new \MangoPay\Refund();

        $Refund->AuthorId = $transaction[0]->getMangopayUserId();

        $Refund->DebitedFunds = new \MangoPay\Money();
        $Refund->DebitedFunds->Currency = "EUR";
        $Refund->DebitedFunds->Amount = $debitedFund;

        $Refund->Fees = new \MangoPay\Money();
        $Refund->Fees->Currency = "EUR";
        $Refund->Fees->Amount = 2;

        $response = $this->mangoPayApi->PayIns->CreateRefund($PayInId, $Refund);

        return $response->ResultMessage;
    }

    public function refundPaymentWithFee($transaction,int $amount,int $refund_amount){

        $fees = $refund_amount * 100;

        $debitedFund = $amount * 100;

        $PayInId = $transaction[0]->getMangopayTransactionId();

        $Refund = new \MangoPay\Refund();

        $Refund->AuthorId = $transaction[0]->getMangopayUserId();

        $Refund->DebitedFunds = new \MangoPay\Money();
        $Refund->DebitedFunds->Currency = "EUR";
        $Refund->DebitedFunds->Amount = $debitedFund;

        $Refund->Fees = new \MangoPay\Money();
        $Refund->Fees->Currency = "EUR";
        $Refund->Fees->Amount = $fees;

        $response = $this->mangoPayApi->PayIns->CreateRefund($PayInId, $Refund);

        return $response->Id;
    }

    public function kycCreate($mangopayUserId, $filename)
    {
        if($this->mangoPayApi->Users->GetKycDocuments($mangopayUserId) == null){
            //create the doc
            $KycDocument = new \MangoPay\KycDocument();
            $KycDocument->Type = "IDENTITY_PROOF";
            $result = $this->mangoPayApi->Users->CreateKycDocument($mangopayUserId, $KycDocument);
            $KycDocumentId = $result->Id;

            //add a page to this doc

            $result2 = $this->mangoPayApi->Users->CreateKycPageFromFile($mangopayUserId, $KycDocumentId, $filename);

            //return $result2;
            //submit the doc for validation
            $KycDocument = new MangoPay\KycDocument();
            $KycDocument->Id = $KycDocumentId;
            $KycDocument->Status = "VALIDATION_ASKED";
            $result3 = $this->mangoPayApi->Users->UpdateKycDocument($mangopayUserId, $KycDocument);
            return $result3;

        }else{
            return $this->mangoPayApi->Users->GetKycDocuments($mangopayUserId);
        }
    }
    public function getAllUsers($Page=null,$Per_Page=null){
        return $this->mangoPayApi->Users->GetAll($Page,$Per_Page);
    }
}