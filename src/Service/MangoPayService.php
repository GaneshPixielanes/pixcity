<?php

namespace App\Service;

use App\Entity\Option;
use App\Repository\ClientTransactionRepository;
use App\Repository\UserMissionRepository;
use MangoPay;
use MangoPay\DemoWorkflow\MockStorageStrategy;
use Symfony\Component\HttpFoundation\Session\Session;
use function GuzzleHttp\Psr7\str;

class MangoPayService
{

    private $mangoPayApi;

    public function __construct()
    {
        $this->mangoPayApi = new MangoPay\MangoPayApi();
        $this->mangoPayApi->Config->ClientId = 'azimforexprod';
        $this->mangoPayApi->Config->ClientPassword = '5ahxUPFNpzuBz0kK3P0Fwt6DeK2s6P44530LKLF1anLp3N5yWK';
//        $this->mangoPayApi->OAuthTokenManager->RegisterCustomStorageStrategy(new MockStorageStrategy());

        // $this->mangoPayApi->Config->TemporaryFolder = "D:\projects";
        $this->mangoPayApi->Config->TemporaryFolder = "uploads/mangopay";

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
        if($mission->getMissionType() == 'one-shot'){
            $tag = 'mission-id '.$mission->getId().' ©';
        }else{
            $tag = 'mission-id '.$mission->getId().' ®';
        }
        $payIn = new MangoPay\PayIn();
        $payIn->CreditedWalletId = $wallet->Id;
        $payIn->AuthorId = $mangoUser->Id;
        $payIn->Tag = $tag;
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
        $payIn->ExecutionDetails->SecureMode = "FORCE";
        $payIn->ExecutionDetails->SecureModeNeeded = true;
        $payIn->ExecutionDetails->SecureModeReturnURL = "http".(isset($_SERVER['HTTPS']) ? "s" : null)."://".$_SERVER["HTTP_HOST"]."/client/mission/mission-accept-process/".$transaction;

        // create Pay-In
        $createdPayIn = $this->mangoPayApi->PayIns->Create($payIn);

        return $createdPayIn->ExecutionDetails->SecureModeRedirectURL;
    }


    public function  getResponse($payincardweb){

        $response = $this->mangoPayApi->PayIns->Get($payincardweb);

        return $response;


    }

    public function refundPayment($transaction,$amount,$fees){

        if($transaction->getMission()->getMissionType() == 'one-shot'){
            $tag = 'mission-id '.$transaction->getMission()->getId().' ©';
        }else{
            $tag = 'mission-id '.$transaction->getMission()->getId().' ®';
        }

        $fees = $fees * 100;

        $debitedFund = $amount * 100;

        $PayInId = $transaction->getMangopayTransactionId();

        $Refund = new MangoPay\Refund();

        $Refund->AuthorId = $transaction->getMangopayUserId();
        $Refund->Tag = $tag;
        $Refund->DebitedFunds = new MangoPay\Money();
        $Refund->DebitedFunds->Currency = "EUR";
        $Refund->DebitedFunds->Amount = $debitedFund;

        $Refund->Fees = new \MangoPay\Money();
        $Refund->Fees->Currency = "EUR";

        $Refund->Fees->Amount = - $fees;

        $response = $this->mangoPayApi->PayIns->CreateRefund($PayInId, $Refund);

        return $response->ResultMessage;
    }

    public function refundPaymentWithFee($transaction,$amount,$refund_amount){

        if($transaction->getMission()->getMissionType() == 'one-shot'){
            $tag = 'mission-id '.$transaction->getMission()->getId().' ©';
        }else{
            $tag = 'mission-id '.$transaction->getMission()->getId().' ®';
        }

        $fees = $refund_amount * 100;

        $debitedFund = $amount * 100;

        $PayInId = $transaction->getMangopayTransactionId();

        $Refund = new MangoPay\Refund();

        $Refund->AuthorId = $transaction->getMangopayUserId();
        $Refund->Tag = $tag;
        $Refund->DebitedFunds = new MangoPay\Money();
        $Refund->DebitedFunds->Currency = "EUR";
        $Refund->DebitedFunds->Amount = $debitedFund;

        $Refund->Fees = new MangoPay\Money();
        $Refund->Fees->Currency = "EUR";
        $Refund->Fees->Amount = - $fees;

        $response = $this->mangoPayApi->PayIns->CreateRefund($PayInId, $Refund);

        return $response->Id;
    }

    public function kycCreate($mangopayUserId, $filename, $documentType)
    {
        // if($this->mangoPayApi->Users->GetKycDocuments($mangopayUserId) == null){
            //create the doc
            $KycDocument = new MangoPay\KycDocument();
            if($documentType === "IdentityProof")
            {
                $KycDocument->Type = MangoPay\KycDocumentType::IdentityProof;
            }
            else
            {

                $KycDocument->Type = MangoPay\KycDocumentType::AddressProof;
            }
            
            $result = $this->mangoPayApi->Users->CreateKycDocument($mangopayUserId, $KycDocument);
            $KycDocumentId = $result->Id;

            //add a page to this doc

            $result2 = $this->mangoPayApi->Users->CreateKycPageFromFile($mangopayUserId, $KycDocumentId, $filename);

            //return $result2;
            //submit the doc for validation
            $KycDocument = new MangoPay\KycDocument();
            $KycDocument->Id = $KycDocumentId;
            $KycDocument->Status = MangoPay\KycDocumentStatus::ValidationAsked;
            $result3 = $this->mangoPayApi->Users->UpdateKycDocument($mangopayUserId, $KycDocument);
            return $result3;

        // }else{
        //     return $this->mangoPayApi->Users->GetKycDocuments($mangopayUserId);
        // }
    }

    public function getKycDocuments($mangopayUserId)
    {
        return $this->mangoPayApi->Users->GetKycDocuments($mangopayUserId);
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

    public function transfer($city_maker_wallet_id,$client_id,$client_wallet_id,$amount){

        $Transfer = new MangoPay\Transfer();
        $Transfer->AuthorId = $client_id;//Client MangoPayUser ID
        $Transfer->DebitedFunds = new MangoPay\Money();
        $Transfer->DebitedFunds->Currency = "EUR";
        $Transfer->DebitedFunds->Amount = $amount;
        $Transfer->Fees = new MangoPay\Money();
        $Transfer->Fees->Currency = "EUR";
        $Transfer->Fees->Amount = 0;
        $Transfer->DebitedWalletId = $client_wallet_id;//Client Wallet ID
        $Transfer->CreditedWalletId = $city_maker_wallet_id;//User Wallet ID
        $result = $this->mangoPayApi->Transfers->Create($Transfer);

        return $result;
    }

    public function createBankAccount($user){
        $issue = [];

        try {

            $UserId = $user->getMangopayUserId();
            $BankAccount = new MangoPay\BankAccount();
            $BankAccount->Type = "IBAN";
            $BankAccount->Details = new MangoPay\BankAccountDetailsIBAN();
            $BankAccount->Details->IBAN = $user->getPixie()->getBilling()->getBillingIban();
            $BankAccount->Details->BIC = $user->getPixie()->getBilling()->getBillingBic();
            $BankAccount->OwnerName = $user->getFirstname() . ' ' . $user->getLastname();
            $BankAccount->OwnerAddress = new MangoPay\Address();
            $BankAccount->OwnerAddress->AddressLine1 = $user->getPixie()->getBilling()->getAddress()->getAddress();
            $BankAccount->OwnerAddress->City = $user->getPixie()->getBilling()->getAddress()->getCity();
            $BankAccount->OwnerAddress->Country = $user->getPixie()->getBilling()->getAddress()->getCountry();
            $BankAccount->OwnerAddress->PostalCode = $user->getPixie()->getBilling()->getAddress()->getZipcode();
            $BankAccount->OwnerAddress->Region = $user->getPixie()->getBilling()->getAddress()->getCity();

            $result = $this->mangoPayApi->Users->CreateBankAccount($UserId, $BankAccount);

            $issue = ['status' => true,'user' => $user,'result' => $result];

            return $issue;

        }catch(MangoPay\Libraries\ResponseException $e){

            $issue = ['status' => false,'user' => $user];

            return $issue;

        }


    }


    public function getPayOut($cm_user_id,$cm_wallet_id,$amount,$bank_id){

        $PayOut = new MangoPay\PayOut();
        $PayOut->AuthorId = $cm_user_id;
        $PayOut->DebitedWalletId = $cm_wallet_id;
        $PayOut->DebitedFunds = new \MangoPay\Money();
        $PayOut->DebitedFunds->Currency = "EUR";
        $PayOut->DebitedFunds->Amount = $amount;
        $PayOut->Fees = new \MangoPay\Money();
        $PayOut->Fees->Currency = "EUR";
        $PayOut->Fees->Amount = 0;
        $PayOut->PaymentType = MangoPay\PayOutPaymentType::BankWire;
        $PayOut->MeanOfPaymentDetails = new MangoPay\PayOutPaymentDetailsBankWire();
        $PayOut->MeanOfPaymentDetails->BankAccountId = $bank_id;

        $result = $this->mangoPayApi->PayOuts->Create($PayOut);

        return $result;

    }

    public function legalClient($client){

        $birthday_string =  strtotime(date('d-m-Y',strtotime( $client->getClientInfo()->getCompanyCreationDate()->format('d-m-Y'))));


        $email = $client->getClientInfo()->getEmail() == null ?  $client->getEmail() : $client->getClientInfo()->getEmail();

        $User = new MangoPay\UserLegal();
        $User->Name =$client->getFirstName().' '.$client->getLastName();
        $User->LegalPersonType = MangoPay\LegalPersonType::Business;
        $User->Email = $email;
        $User->LegalRepresentativeFirstName = $client->getFirstName();
        $User->LegalRepresentativeLastName = $client->getLastName();
        $User->LegalRepresentativeBirthday = $birthday_string;
        $User->LegalRepresentativeNationality = "FR";
        $User->LegalRepresentativeCountryOfResidence = "FR";
        $result = $this->mangoPayApi->Users->Create($User);
        dd($result);
        return $result;

    }


}
