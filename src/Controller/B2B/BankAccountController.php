<?php

namespace App\Controller\B2B;

use App\Repository\RoyaltiesRepository;
use App\Repository\UserRepository;
use App\Service\MangoPayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BankAccountController extends AbstractController
{
    /**
     * @Route("/b2b/bank/account", name="b2b_bank_account")
     */
    public function index(UserRepository $userRepository,MangoPayService $mangoPayService,RoyaltiesRepository $royaltiesRepository)
    {
        $em = $this->getDoctrine()->getManager();

        $executedMissionIds = [];

        $users = $userRepository->findAll();

        foreach ($users as $user){

            $check_royalties = $royaltiesRepository->findOneBy(['cm' => $user->getId()]);

            if($user->getPixie() != null){

                if($user->getMangopayUserId() != null && $user->getPixie()->getBilling()->getBillingIban() != null && $user->getPixie()->getBilling()->getBillingBic() != null &&
                    $user->getPixie()->getBilling()->getMangopayNeedToUpdate() != 1 && $check_royalties != null){

                    $result = $mangoPayService->createBankAccount($user);

                    $user->getPixie()->getBilling()->setMangopayId($result->Id);

                    $user->getPixie()->getBilling()->setMangopayNeedToUpdate(1);

                    $em->persist($user);

                    $em->flush();

                    $executedMissionIds[] = $user->getId();

                }

            }


        }

        return new JsonResponse(['completed_id' => $executedMissionIds]);


    }
}
