<?php

namespace App\Controller\B2B\Client;


use App\Constant\MissionStatus;
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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

/**
 * @Route("client/mangopaycron", name="b2b_client_mangopaycron_")
 * @Security("has_role('ROLE_USER')")
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
     * @param UserMissionRepository $missionRepo@
     * @Route("/process-cron-cm/", name="process_cm")
     */
    public function cronCitymakerProcess(
                                   MangoPayService $mangoPayService,
                                   UserRepository $userRepository
    )
    {
        $userRepositoryTbl = $userRepository->findAll();

        foreach ($userRepositoryTbl as $key => $value)
        {
            if($value->getMangopayUserId() == null){
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
                $em = $this->getDoctrine()->getManager();

                $em->persist($value);
                $em->flush();
            }
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
                                   ClientRepository $clientRepository
    )
    {

        $clientRepositoryTbl = $clientRepository->findAll();

        foreach ($clientRepositoryTbl as $key => $value)
        {
            if($value->getClientInfo()->getMangopayUserId() == null){
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
                $value->getClientInfo()->setMangopayUserId($mangoUser->Id);
                $value->getClientInfo()->setMangopayWalletId($wallet->Id);
                $em = $this->getDoctrine()->getManager();

                $em->persist($value);
                $em->flush();
            }
        }

        return JsonResponse::create(['success' => true]);
    }


}
