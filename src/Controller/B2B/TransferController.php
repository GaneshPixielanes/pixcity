<?php

namespace App\Controller\B2B;

use App\Repository\RoyaltiesRepository;
use App\Service\MangoPayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransferController extends AbstractController
{
    /**
     * @Route("/b2b/transfer", name="b2b_transfer")
     */
    public function transferWallet(RoyaltiesRepository $royaltiesRepository,MangoPayService $mangoPayService)
    {

        $em = $this->getDoctrine()->getManager();$executedMissionIds = [];$incompleteMissionIds = [];

        $royalties = $royaltiesRepository->findAll();

        foreach ($royalties as $royalty){

            if($royalty->getStatus() == 'pending'){

                $city_maker_wallet_id = $royalty->getCm()->getMangopayWalletId();
                $client_id = $royalty->getMission()->getClient()->getClientInfo()->getMangopayUserId();
                $client_wallet_id = $royalty->getMission()->getClient()->getClientInfo()->getMangopayWalletId();

                $amount = $royalty->getBasePrice();

                $result = $mangoPayService->transfer($city_maker_wallet_id,$client_id,$client_wallet_id,(int)$amount * 100);

                if($result->Status != 'FAILED'){
                    $royalty->setStatus('transfer');
                    $em->persist($royalty);
                    $em->flush();
                    $executedMissionIds [] = $royalty->getMission()->getId();
                }else{
                    $incompleteMissionIds [] = $royalty->getMission()->getId();
                }

            }

        }

        return new JsonResponse(['completed_mission_id' => $executedMissionIds,'incomplete_mission_id' => $incompleteMissionIds]);
    }

    /**
     * @Route("/b2b/payout", name="b2b_payout")
     */
    public function transferCityMakerBank(RoyaltiesRepository $royaltiesRepository,MangoPayService $mangoPayService){

        $em = $this->getDoctrine()->getManager();

        $royalties = $royaltiesRepository->findAll();$executedMissionIds = [];$incompleteMissionIds = [];

        foreach ($royalties as $royalty){

            if($royalty->getStatus() == 'transfer'){

                $cm_user_id = $royalty->getCm()->getMangopayUserId();

                $cm_wallet_id = $royalty->getCm()->getMangopayWalletId();

                $amount = $royalty->getBasePrice();

                $bank_id = $royalty->getCm()->getPixie()->getBilling()->getMangopayId();

                $result = $mangoPayService->getPayOut($cm_user_id,$cm_wallet_id,$amount*100,$bank_id);

                if($result->Status == 'CREATED'){
                    $executedMissionIds [] = $royalty->getMission()->getId();
                    $royalty->setStatus('payout-completed');
                    $em->persist($royalty);
                    $em->flush();
                }else{
                    $incompleteMissionIds [] = $royalty->getMission()->getId();
                }

            }

        }

        return new JsonResponse(['completed_mission_id' => $executedMissionIds,'incomplete_mission_id' => $incompleteMissionIds]);

    }



}
