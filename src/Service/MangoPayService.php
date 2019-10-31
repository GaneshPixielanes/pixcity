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

//      $this->mangoPayApi->OAuthTokenManager->RegisterCustomStorageStrategy(new MockStorageStrategy());

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

//    public function getClientId($url)
//    {
////        return $this->mangoPayApi->Clients->Get('azimforexprod');
//
//        $ClientLogoUpload = new MangoPay\ClientLogoUpload();
//        $ClientLogoUpload->File = $url;
//
//        $Result = $this->mangoPayApi->Clients->UploadLogo($ClientLogoUpload);
//        return $Result;
//    }

    public function getPayIn($mangoUser, $wallet, $amount, $transaction, $mission,$fee,$card_array)
    {

        $payIn = new MangoPay\PayIn();
        $payIn->CreditedWalletId = $wallet->Id;
        $payIn->AuthorId = $mangoUser->Id;
        $payIn->Tag = "Mission-id ".$mission;
        $payIn->DebitedFunds = new MangoPay\Money();
        $payIn->DebitedFunds->Amount = $amount;
        $payIn->DebitedFunds->Currency = 'EUR';
        $payIn->Fees = new MangoPay\Money();
        $payIn->Fees->Amount = $fee;
        $payIn->Fees->Currency = 'EUR';

        // payment type as CARD

        $payIn->PaymentDetails = new MangoPay\PayInPaymentDetailsCard();
        $payIn->PaymentDetails->CardType = $card_array['card_type'];
        $payIn->PaymentDetails->CardId = $card_array['card_id'];

        // execution type as DIRECT
        $payIn->ExecutionDetails = new MangoPay\PayInExecutionDetailsDirect();
        $payIn->ExecutionDetails->SecureModeNeeded = true;
        $payIn->ExecutionDetails->SecureModeReturnURL = "http".(isset($_SERVER['HTTPS']) ? "s" : null)."://".$_SERVER["HTTP_HOST"]."/client/mission/mission-accept-process/".$transaction;

        // create Pay-In
        $createdPayIn = $this->mangoPayApi->PayIns->Create($payIn);

        return $createdPayIn->Id;
    }


    public function  getResponse($payincardweb){

        $response = $this->mangoPayApi->PayIns->Get($payincardweb);

        return $response;


    }

    public function refundPayment($transaction,$amount,$fees){
        
        $fees = $fees * 100;

        $debitedFund = $amount * 100;

        $PayInId = $transaction->getMangopayTransactionId();

        $Refund = new \MangoPay\Refund();

        $Refund->AuthorId = $transaction->getMangopayUserId();
        $Refund->Tag = "Mission-id ".$transaction->getMission()->getId();
        $Refund->DebitedFunds = new \MangoPay\Money();
        $Refund->DebitedFunds->Currency = "EUR";
        $Refund->DebitedFunds->Amount = $debitedFund;

        $Refund->Fees = new \MangoPay\Money();
        $Refund->Fees->Currency = "EUR";

        $Refund->Fees->Amount = - $fees;

        $response = $this->mangoPayApi->PayIns->CreateRefund($PayInId, $Refund);

        return $response->ResultMessage;
    }

    public function refundPaymentWithFee($transaction,$amount,$refund_amount){

        $fees = $refund_amount * 100;

        $debitedFund = $amount * 100;

        $PayInId = $transaction->getMangopayTransactionId();

        $Refund = new \MangoPay\Refund();

        $Refund->AuthorId = $transaction->getMangopayUserId();

        $Refund->DebitedFunds = new \MangoPay\Money();
        $Refund->DebitedFunds->Currency = "EUR";
        $Refund->DebitedFunds->Amount = $debitedFund;

        $Refund->Fees = new \MangoPay\Money();
        $Refund->Fees->Currency = "EUR";
        $Refund->Fees->Amount = - $fees;

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


    public function setCardRegistration($naturalUserId){

        $cardRegister = new MangoPay\CardRegistration();
        $cardRegister->UserId = $naturalUserId;
        $cardRegister->Currency = 'EUR';
        $cardRegister->CardType = 'CB_VISA_MASTERCARD';
        $createdCardRegister = $this->mangoPayApi->CardRegistrations->Create($cardRegister);

        return $createdCardRegister;
    }

    public function finishCardRegistration($card_id,$data){

        $cardRegisterPut = $this->mangoPayApi->CardRegistrations->Get($card_id);
        $cardRegisterPut->RegistrationData = 'data='.$data;
        $updatedCardRegister = $this->mangoPayApi->CardRegistrations->Update($cardRegisterPut);

        if($updatedCardRegister->Status != MangoPay\CardRegistrationStatus::Validated || !isset($updatedCardRegister->CardId)){
            return false;
        }


        $card = $this->mangoPayApi->Cards->Get($updatedCardRegister->CardId);

        return $card;
    }

    public function transfer(){

        $Transfer = new MangoPay\Transfer();
        $Transfer->AuthorId = '66326339';//Client MangoPayUser ID
        $Transfer->DebitedFunds = new MangoPay\Money();
        $Transfer->DebitedFunds->Currency = "EUR";
        $Transfer->DebitedFunds->Amount = 700;
        $Transfer->Fees = new MangoPay\Money();
        $Transfer->Fees->Currency = "EUR";
        $Transfer->Fees->Amount = 0;
        $Transfer->DebitedWalletId = '66326340';//Client Wallet ID
        $Transfer->CreditedWalletId = '67271565';//User Wallet ID
        $result = $this->mangoPayApi->Transfers->Create($Transfer);
        dd($result);

    }

    public function createBankAccount(){

        $UserId = '66326339';
        $BankAccount = new MangoPay\BankAccount();
        $BankAccount->Type = "IBAN";
        $BankAccount->Details = new MangoPay\BankAccountDetailsIBAN();
        $BankAccount->Details->IBAN = "FR7618829754160173622224154";
        $BankAccount->Details->BIC = "CMBRFR2BCME";
        $BankAccount->OwnerName = "Joe Bloggs";
        $BankAccount->OwnerAddress = new \MangoPay\Address();
        $BankAccount->OwnerAddress->AddressLine1 = 'Address line 1';
        $BankAccount->OwnerAddress->AddressLine2 = 'Address line 2';
        $BankAccount->OwnerAddress->City = 'City';
        $BankAccount->OwnerAddress->Country = 'FR';
        $BankAccount->OwnerAddress->PostalCode = '11222';
        $BankAccount->OwnerAddress->Region = 'Region';
        $result = $this->mangoPayApi->Users->CreateBankAccount($UserId, $BankAccount);


    }


}
