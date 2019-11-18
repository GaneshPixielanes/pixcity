<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Service\MangoPayService;
use App\Repository\UserRepository;
use App\Service\Mailer;
use MangoPay\KycDocumentStatus;
/**
 * @Route("/api/transaction/", name="api_transaction")
 */
class TransactionController extends AbstractController
{

    /**
     * @Route("check-kyc", name="api_transaction")
     */
    public function checkKyc(UserRepository $userRepo, MangoPayService $mangoPay, Mailer $mailer)
    {
        $users = $userRepo->findKycEligibleUsers();
        $refusedUsers['ADDRESS_PROOF'] = [];
        $refusedUsers['IDENTITY_PROOF'] = [];
        $addressProofCount = 0;
        $identityProofCount = 0;
        if(count($users) == 0)
        {

            return new JsonResponse('["success" => true, "message" => "No user has KYC pending"]');
        }

        try{
            $em = $this->getDoctrine()->getManager();

            foreach($users as $user)
            {
                $documents = $mangoPay->getKycDocuments($user->getMangopayUserId());

                foreach($documents as $document)
                {
                    switch($document->Type)
                    {
                        case 'IDENTITY_PROOF': 
                                                if($document->Status != $user->getMangopayKycStatus())
                                                {
                                                    if($document->Status == 'REFUSED')
                                                    {
                                                        $refusedUsers['IDENTITY_PROOF'][] = $user;
                                                    }
                                                    $user->setMangopayKycStatus($document->Status);
                                                }

                                                $identityProofCount = $identityProofCount+1;
                                                break;
                        case 'ADDRESS_PROOF': 
                                                if($document->Status != $user->getMangopayKycAddrStatus())
                                                {
                                                    if($document->Status == 'REFUSED')
                                                    {
                                                        $refusedUsers['ADDRESS_PROOF'][] = $user;
                                                    }
                                                    $user->setMangopayKycAddrStatus($document->Status);
                                                }

                                                $addressProofCount = $addressProofCount + 1;
                                                break;
                    }
                    
                }

                $em->persist($user);
                $em->flush();
            }

            if(!empty($refusedUsers['IDENTITY_PROOF']) || !empty($refusedUsers['ADDRESS_PROOF']))
            {
                $emails = ['ganesh@pix.city','rakesh@pix.city'];

                foreach($emails as $email)
                {
                    $mailer->send($email,'Users could not get their KYC verified','emails/kyc-refused-email.html.twig',[
                        'address_refused_users' => $refusedUsers['ADDRESS_PROOF'],
                        'identity_refused_users' => $refusedUsers['IDENTITY_PROOF']
                    ],[]);
                }
            }

            return new JsonResponse('["success" => true, "message" => "Process complete"]');
        }
        catch(\Exception $e)
        {
            return new JsonResponse('["success" => false, "message" => "Something went wrong. Please contact dev/support team"]');
        }
    }

}



