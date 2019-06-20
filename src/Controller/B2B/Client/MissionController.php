<?php

namespace App\Controller\B2B\Client;

use App\Constant\MissionStatus;
use App\Entity\ClientTransaction;
use App\Repository\ClientRepository;
use App\Repository\ClientTransactionRepository;
use App\Repository\MissionRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserMissionRepository;
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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Route("client/mission", name="b2b_client_mission_")
 * @Security("has_role('ROLE_USER')")
 */
class MissionController extends AbstractController
{

    /**
     * @Route("/list", name="list")
     */
    public function index(UserMissionRepository $missionRepo)
    {
        $missions['ongoing'] = $missionRepo->findOngoingMissions($this->getUser(),'client');
        $missions['cancelled'] = $missionRepo->findBy(['status' => MissionStatus::CANCELLED, 'client' => $this->getUser()],[]);
        $missions['terminated'] = $missionRepo->findBy(['status' => MissionStatus::TERMINATED, 'client' => $this->getUser()],[]);
        $missions['created'] = $missionRepo->findBy(['status' => MissionStatus::CREATED, 'client' => $this->getUser()],[]);

        return $this->render('b2b/client/mission/index.html.twig', [
            'missions' => $missions,
        ]);
    }

    /**
     * @Route("/view/{id}",name="view")
     */
    public function view($id, UserMissionRepository $missionRepo)
    {
        $mission = $missionRepo->find($id);

        if(is_null($id) || is_null($mission) || ($this->getUser()->getId() != $mission->getClient()->getId()))
        {
            return $this->redirect('b2b_client_mission_list');
        }

        return $this->render('/b2b/client/mission/view.html.twig',
            [
               'mission' => $mission
            ]);
    }

    /**
     * @Route("/missions",name="missions")
     */
    public function missions(UserMissionRepository $missionRepo)
    {
        #Get the corresponding mission proposals that haven't been handled yet
        $missions = $missionRepo->findBy(['missionAgreedClient' => null, 'client' => $this->getUser()]);

        return $this->render('b2b/client/mission/missions.html.twig',[
            'missions' => $missions
        ]);
    }


    /**
     * @Route("/accept-mission/{id}",name="mission_accept")
     */
    public function missionAccept($id, UserMissionRepository  $missionRepo)
    {
        $mission = $missionRepo->findBy(
            [
                'id' => $id,
                'client' => $this->getUser()
            ]
        );

        if(empty($mission))
        {
            return $this->redirect('/client/mission/missions');
        }


        return $this->render('b2b/client/transaction/mission-accept.html.twig',
            [
               'mission' => $mission[0]
            ]);

    }//End of mission accept

    /**
     * @param $id
     * @param MangoPayService $mangoPayService
     * @param UserMissionRepository $missionRepo@
     * @Route("/process-mission-request/{id}", name="process_mission_request")
     */
    public function missionProcess($id,
                                   MangoPayService $mangoPayService,
                                   UserMissionRepository $missionRepo
    )
    {
        $transaction = new ClientTransaction();
        $mission = $missionRepo->find($id);
        $amount = $mission->getUserMissionPayment()->getTotal();
        // Create a mango pay user
        $mangoUser = new UserNatural();

        $mangoUser->PersonType = "NATURAL";
        $mangoUser->FirstName = $this->getUser()->getFirstname();
        $mangoUser->LastName = $this->getUser()->getLastname();
        $mangoUser->Birthday = 1409735187;
        $mangoUser->Nationality = "FR";
        $mangoUser->CountryOfResidence = "FR";
        $mangoUser->Email = $this->getUser()->getEmail();
        $mangoUser = $mangoPayService->createUser($mangoUser);

        //Create a wallet
        $wallet = $mangoPayService->getWallet($mangoUser->Id);

        //Create Transaction
        $transaction->setUser($mission->getClient());
        $transaction->setAmount($amount);
        $transaction->setMangopayUserId($mangoUser->Id);
        $transaction->setMangopayWalletId($wallet->Id);
        $transaction->setPaymentStatus(false);
        $transaction->setMission($mission);

        $em = $this->getDoctrine()->getManager();

        $em->persist($transaction);
        $em->flush();

        //Create Payin
        $result  = $mangoPayService->getPayIn($mangoUser, $wallet, $amount * 100, $transaction->getId());

        return $this->redirect($result);
    }

    /**
     * @Route("/mission-accept-process/{id}", name="mission_accept_process")
     */
    public function missionAcceptProcess($id, ClientTransactionRepository $transactionRepo,
                                         ClientRepository $clientRepository,
                                         UserMissionRepository $missionRepo,
                                         Request $request,MangoPayService $mangoPayService,NotificationsRepository $notificationsRepository)
    {

        $response = $mangoPayService->getResponse($request->get('transactionId'));


        if($response->Status != 'FAILED'){

            $transaction = $transactionRepo->find($id);
            $mission_id = $transaction->getMission();

            $transaction->setMangopayTransactionId($request->get('transactionId'));
            $transaction->setPaymentStatus(true);
            $transaction->getMission()->setStatus(MissionStatus::ONGOING);
            $transaction->getMission()->setMissionAgreedClient(1);
            $em = $this->getDoctrine()->getManager();

            $em->persist($transaction);
            $em->flush();

            $notification = $notificationsRepository->findBy(['notify_by' => $mission_id]);

            if(!empty($notification)){

                if($notification[0]->getType() == 'create_mission'){

                    $notification[0]->setUnread(0);
                    $em->persist($notification[0]);
                    $em->flush();
                }

            }

            return $this->render('b2b/client/transaction/success.html.twig');

        }else{

            return $this->render('b2b/client/transaction/failed.html.twig');

        }



    }


}
