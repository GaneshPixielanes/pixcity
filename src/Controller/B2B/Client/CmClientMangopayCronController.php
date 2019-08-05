<?php

namespace App\Controller\B2B\Client;


use App\Constant\MissionStatus;
use App\Entity\Client;
use App\Entity\ClientTransaction;
use App\Entity\Option;
use App\Entity\Royalties;
use App\Repository\ClientInfoRepository;
use App\Repository\ClientRepository;
use App\Repository\ClientTransactionRepository;
use App\Repository\MissionPaymentRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use App\Service\MangoPayService;
use MangoPay\Money;
use MangoPay\PayIn;
use MangoPay\PayInExecutionDetailsWeb;
use MangoPay\PayInExecutionType;
use MangoPay\PayInPaymentDetails;
use MangoPay\PayInPaymentDetailsCard;
use MangoPay\PayInPaymentType;
use MangoPay\UserNatural;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Asset\Package;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

/**
 * @Route("client/mangopaycron", name="b2b_client_mangopaycron_")
 */
class CmClientMangopayCronController extends Controller
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('b2b/cmClientCron.html.twig');
    }


    /**
     * @param MangoPayService $mangoPayService
     * @param UserRepository $userRepository
     * @Route("/process-cron-cm/", name="process_cm")
     */
    public function cronCitymakerProcess(
                                   MangoPayService $mangoPayService,
                                   UserRepository $userRepository,
                                   Mailer $mailer
    )
    {
        $userRepositoryTbl = $userRepository->findBy(array(), array('id' => 'DESC'),5);
        $missingCm = array();
        foreach ($userRepositoryTbl as $key => $value)
        {
            if($value->getMangopayUserId() == null && $value->getFirstname() != null && $value->getLastname() != null && $value->getEmail() != null){
                // Create a mango pay user
                $mangoUser = new UserNatural();

                $mangoUser->PersonType = "NATURAL";
                $mangoUser->FirstName = $value->getFirstname();
                $mangoUser->LastName = $value->getLastname();
                $mangoUser->Birthday = 1409735187;
                $mangoUser->Nationality = "FR";
                $mangoUser->CountryOfResidence = "FR";
                $mangoUser->Email = $value->getEmail();
                $mangoUser = $mangoPayService->createUser($mangoUser);
                //Create a wallet
                $wallet = $mangoPayService->getWallet($mangoUser->Id);
                $value->setMangopayUserId($mangoUser->Id);
                $value->setMangopayWalletId($wallet->Id);
                $value->setMangopayCreatedAt(new \DateTime());
                $value->setMangopayKycStatus("PENDING");
                $em = $this->getDoctrine()->getManager();

                $em->persist($value);
                $em->flush();
            }
            if($value->getMangopayUserId() == null || $value->getFirstname() == null || $value->getLastname() == null || $value->getEmail() || null){
                $missingCm[] = $value;
            }
        }
        if(isset($missingCm) != null){
            // Send missing information email
            $mailer->send("rakesh@pix.city", 'Issue in creating Mangopay user and wallet ', 'emails/mangopay-client-error-report.html.twig', [
                'missingRecords' => $missingCm
            ]);
        }
        return JsonResponse::create(['success' => true]);
    }
    /**
     * @param MangoPayService $mangoPayService
     * @param UserMissionRepository $missionRepo@
     * @Route("/process-cron-client/", name="process_client")
     */
    public function cronClientProcess(
                                   MangoPayService $mangoPayService,
                                   ClientRepository $clientRepository,
                                   Mailer $mailer
    )
    {

        $clientRepositoryTbl = $clientRepository->findBy(array(), array('id' => 'DESC'),5);

        $missingClients = array();
        foreach ($clientRepositoryTbl as $key => $value)
        {
            if($value->getClientInfo()->getMangopayUserId() == null && $value->getFirstName() != null && $value->getLastName() != null && $value->getEmail() != null){
                // Create a mango pay user
                $mangoUser = new UserNatural();

                $mangoUser->PersonType = "NATURAL";
                $mangoUser->FirstName = $value->getFirstName();
                $mangoUser->LastName = $value->getLastName();
                $mangoUser->Birthday = 1409735187;
                $mangoUser->Nationality = "FR";
                $mangoUser->CountryOfResidence = "FR";
                $mangoUser->Email = $value->getEmail();
                $mangoUser = $mangoPayService->createUser($mangoUser);
                //Create a wallet
                $wallet = $mangoPayService->getWallet($mangoUser->Id);
                $value->getClientInfo()->setMangopayUserId($mangoUser->Id);
                $value->getClientInfo()->setMangopayWalletId($wallet->Id);
                $value->getClientInfo()->setMangopayCreatedAt(new \DateTime());
                $value->getClientInfo()->setMangopayKycStatus("PENDING");
                $em = $this->getDoctrine()->getManager();

                $em->persist($value);
                $em->flush();
            }
            if($value->getClientInfo()->getMangopayUserId() == null || $value->getFirstName() == null || $value->getLastName() == null || $value->getEmail() == null){
                $missingClients[] = $value;
            }
        }
        if(isset($missingCm) != null) {
            // Send missing information email
            $mailer->send("rakesh@pix.city", 'Issue in creating Mangopay user and wallet ', 'emails/mangopay-client-error-report.html.twig', [
                'missingRecords' => $missingClients
            ]);
        }

        return JsonResponse::create(['success' => true]);
    }
    /**
     * @param MangoPayService $mangoPayService
     * @param ClientRepository $clientRepository
     * @Route("/process-cron-client-kyc/", name="process_client_kyc")
     */
    public function cronClientKycProcess(MangoPayService $mangoPayService, ClientRepository $clientRepository)
    {
        $clientRepositoryTbl = $clientRepository->findBy(array(), array('id' => 'DESC'),5);

        $filename = 'uploads/clients/29ad0366a8e95b78fe740f95bc6fd383.jpeg';
        foreach ($clientRepositoryTbl as $key => $value)
        {
            if($value->getClientInfo()->getMangopayUserId() != null){
               // $filename = 'uploads/mangopay_kyc/client/'.$value->getId().'/addr1/'.$value->getClientInfo()->getMangopayKycFile();
                //$filename1 = 'uploads/mangopay_kyc/client/'.$value->getId().'/addr2/'.$value->getClientInfo()->getMangopayKycAddr();
                $res = $mangoPayService->kycCreate($value->getClientInfo()->getMangopayUserId(),$filename);
               // $mangoPayService->kycCreate($value->getClientInfo()->getMangopayUserId(),$filename1);
                if(isset($res) != null ){
                    foreach($res as $v){
                        if(isset($v->Status) != null) {
                            if ($v->Status == "VALIDATION_ASKED") {
                                $value->getClientInfo()->setMangopayKycStatus("UNDER_VERIFICATION");
                            }
                            if($v->Status == "SUCCEEDED") {
                                $value->getClientInfo()->setMangopayKycStatus("SUCCESS");
                            }
                            if($v->Status == "REFUSED") {
                                $value->getClientInfo()->setMangopayKycStatus("REJECT");
                            }
                            $em = $this->getDoctrine()->getManager();

                            $em->persist($value);
                            $em->flush();
                        }
                    }
                }
            }
        }
        return JsonResponse::create(['success' => true]);
    }
    /**
     * @param MangoPayService $mangoPayService
     * @param UserRepository $userRepository
     * @Route("/process-cron-cm-kyc/", name="process_cm_kyc")
     */
    public function cronCitymakerKycProcess(MangoPayService $mangoPayService, UserRepository $userRepository)
    {
        $userRepositoryTbl = $userRepository->findBy([],['id'=>'DESC'],5);
        $filename = 'uploads/clients/29ad0366a8e95b78fe740f95bc6fd383.jpeg';
        foreach ($userRepositoryTbl as $key => $value)
        {
            if($value->getMangopayUserId() != null) {
                //  $filename = 'uploads/mangopay_kyc/cm/'.$value->getId().'/addr1/'.$value->getMangopayKycFile();
                //$filename1 = 'uploads/mangopay_kyc/cm/'.$value->getId().'/addr2/'.$value->getMangopayKycAddr();

                $res = $mangoPayService->kycCreate($value->getMangopayUserId(), $filename);
                //$mangoPayService->kycCreate($value->getMangopayUserId(),$filename1);

                if(isset($res) != null ){
                    foreach($res as $v){
                        if(isset($v->Status) != null) {
                            if ($v->Status == "VALIDATION_ASKED") {
                                $value->setMangopayKycStatus("UNDER_VERIFICATION");
                            }
                            if($v->Status == "SUCCEEDED") {
                                $value->setMangopayKycStatus("SUCCESS");
                            }
                            if($v->Status == "REFUSED") {
                                $value->setMangopayKycStatus("REJECT");
                            }
                            $em = $this->getDoctrine()->getManager();

                            $em->persist($value);
                            $em->flush();
                        }
                    }
                }
            }
        }
        return JsonResponse::create(['success' => true]);
    }

    /**
     * @param MangoPayService $mangoPayService
     * @param ClientRepository $clientRepository
     * @Route("/process-cron-client-kycstatus/", name="process_client_kycstatus")
     */
    public function cronClientKycStatus(MangoPayService $mangoPayService, ClientRepository $clientRepository,
                                        Mailer $mailer)
    {
        $clientRepositoryTbl = $clientRepository->findBy(array(), array('id' => 'DESC'), 20);
        $docRejected= array();
        foreach ($clientRepositoryTbl as $key => $value) {
            if ($value->getClientInfo()->getMangopayUserId() != null && $value->getClientInfo()->getMangopayKycStatus() == "REJECT") {
                $docRejected[] = $value;
            }
        }
        if(isset($docRejected) != null) {
            // Send missing information email
            $mailer->send("rakesh@pix.city", 'Document is Rejected by Mangopay ', 'emails/mangopay-client-error-report.html.twig', [
                'missingRecords' => $docRejected
            ]);
        }
    }

    /**
     * @param MangoPayService $mangoPayService
     * @param ClientRepository $clientRepository
     * @Route("/process-cron-cm-kycstatus/", name="process_cm_kycstatus")
     */
    public function cronCmKycStatus(MangoPayService $mangoPayService, UserRepository $userRepository,
                                        Mailer $mailer)
    {
        $userRepositoryTbl = $userRepository->findBy(array(), array('id' => 'DESC'), 20);
        $docRejected= array();
        foreach ($userRepositoryTbl as $key => $value) {
            if ($value->getMangopayUserId() != null && $value->getMangopayKycStatus() == "REJECT") {
                $docRejected[] = $value;
            }
        }
        if(isset($docRejected) != null) {
            // Send missing information email
            $mailer->send("rakesh@pix.city", 'Document is Rejected by Mangopay ', 'emails/mangopay-cm-error-report.html.twig', [
                'missingRecords' => $docRejected
            ]);
        }
    }
}
