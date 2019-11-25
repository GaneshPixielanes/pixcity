<?php

namespace App\Controller\B2B;

use App\Repository\RoyaltiesRepository;
use App\Service\Mailer;
use App\Service\MangoPayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransferController extends AbstractController
{
    /**
     * @Route("/b2b/transfer", name="b2b_transfer")
     */
    public function transferWallet(RoyaltiesRepository $royaltiesRepository,MangoPayService $mangoPayService,Mailer $mailer)
    {

        $em = $this->getDoctrine()->getManager();$executedMissionIds = [];$incompleteMissionIds = [];

        $royalties = $royaltiesRepository->findAll();$missing = [];

        foreach ($royalties as $royalty){

            if($royalty->getStatus() == 'pending'){

                if($royalty->getCm()->getMangopayKycStatus() == 'VALIDATED' || $royalty->getCm()->getMangopayKycAddrStatus() == 'VALIDATED'){

                    $city_maker_wallet_id = $royalty->getCm()->getMangopayWalletId();
                    $client_id = $royalty->getMission()->getClient()->getClientInfo()->getMangopayUserId();
                    $client_wallet_id = $royalty->getMission()->getClient()->getClientInfo()->getMangopayWalletId();

                    $amount = $royalty->getBasePrice();

                    if($city_maker_wallet_id != null){

                        $result = $mangoPayService->transfer($city_maker_wallet_id,$client_id,$client_wallet_id,(int)$amount * 100);

                        if($result->Status == 'SUCCEEDED'){
                            $royalty->setStatus('transfer');
                            $royalty->setTransferId($result->Id);
                            $royalty->setTransferDate(new \DateTime());
                            $em->persist($royalty);
                            $em->flush();
                            $executedMissionIds [] = $royalty->getMission()->getId();
                        }else{
                            $incompleteMissionIds [] = $royalty->getMission()->getId();
                        }

                    }

                }else{

                    $missing[] = $royalty->getCm();

                }




            }

        }

        if(!empty($missing) != null){

            $mailer->send("rakesh@pix.city", 'KYC Issue in transfer wallets amount from city-maker to client ', 'emails/mangopay-transfer-error-report.html.twig', [
                'missingRecords' => $missing
            ]);

        }


        return new JsonResponse(['completed_mission_id' => $executedMissionIds,'incomplete_mission_id' => $incompleteMissionIds]);
    }

    /**
     * @Route("/b2b/payout", name="b2b_payout")
     */
    public function transferCityMakerBank(RoyaltiesRepository $royaltiesRepository,MangoPayService $mangoPayService,Mailer $mailer){

        $em = $this->getDoctrine()->getManager();$missing = [];

        $royalties = $royaltiesRepository->findAll();$payoutExecutedMissionIds = [];$incompleteMissionIds = [];

        foreach ($royalties as $royalty){

            if($royalty->getStatus() == 'transfer'){

                if($royalty->getCm()->getMangopayKycStatus() == 'VALIDATED' || $royalty->getCm()->getMangopayKycAddrStatus() == 'VALIDATED'){

                    $cm_user_id = $royalty->getCm()->getMangopayUserId();

                    $cm_wallet_id = $royalty->getCm()->getMangopayWalletId();

                    $amount = $royalty->getBasePrice();

                    $bank_id = $royalty->getCm()->getPixie()->getBilling()->getMangopayId();

                    if($cm_user_id != null && $cm_wallet_id != null && $amount > 0 && $bank_id != null){

                        $result = $mangoPayService->getPayOut($cm_user_id,$cm_wallet_id,$amount*100,$bank_id,$royalty->getCycle(),$royalty->getMission());

                        if($result->Status == 'CREATED'){
                            $royalty->setStatus('payout-completed');
                            $royalty->setPayoutId($result->Id);
                            $royalty->setPayoutDate(new \DateTime());
                            $em->persist($royalty);
                            $em->flush();
                            $payoutExecutedMissionIds [] = $royalty->getMission()->getId();
                        }else{
                            $incompleteMissionIds [] = $royalty->getMission()->getId();
                        }

                    }

                }else{

                    $missing [] = $royalty->getCm();

                }




            }

        }

        if(!empty($missing) != null){

            $mailer->send("rakesh@pix.city", 'KYC Issue in pay-out amount of city-makers', 'emails/mangopay-payout-error-report.html.twig', [
                'missingRecords' => $missing
            ]);

        }


        return new JsonResponse(['completed_mission_id' => $payoutExecutedMissionIds,'incomplete_mission_id' => $incompleteMissionIds]);

    }



}
