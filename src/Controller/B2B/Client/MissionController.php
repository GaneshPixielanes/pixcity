<?php

namespace App\Controller\B2B\Client;


use App\Constant\CompanyStatus;
use App\Constant\MissionStatus;
use App\Entity\ClientTransaction;
use App\Entity\MissionRecurring;
use App\Entity\MissionRecurringPriceLog;
use App\Entity\Option;
use App\Entity\Page;
use App\Entity\Royalties;
use App\Repository\ClientInfoRepository;
use App\Repository\ClientRepository;
use App\Repository\ClientTransactionRepository;
use App\Repository\MissionPaymentRepository;
use App\Repository\MissionRecurringRepository;
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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

/**
 * @Route("client/mission", name="b2b_client_mission_")
 * @Security("has_role('ROLE_USER')")
 */
class MissionController extends Controller
{

    /**
     * @Route("", name="list")
     */
    public function index(UserMissionRepository $missionRepo, NotificationsRepository $notificationsRepository)
    {
        $options = $this->getDoctrine()->getRepository(Option::class);
        $margin = $options->findOneBy(['slug' => 'margin']);
        $missions['ongoing'] = $missionRepo->findOngoingMissions($this->getUser(),'client');
        $missions['cancelled'] = $missionRepo->findBy(['status' => MissionStatus::CANCELLED, 'client' => $this->getUser()],['createdAt' => 'DESC']);
        $missions['terminated'] = $missionRepo->findBy(['status' => MissionStatus::TERMINATED, 'client' => $this->getUser()],['createdAt' => 'DESC']);
        $missions['created'] = $missionRepo->findBy(['status' => MissionStatus::CREATED, 'client' => $this->getUser()],['createdAt' => 'DESC']);

        #SEO
        $page = new Page();
        $page->setMetaTitle("Pix.city Services : liste des missions");
        $page->setMetaDescription("Retrouvez dans cet espace vos missions en cours, annulées ou terminées");

        return $this->render('b2b/client/mission/index.html.twig', [
            'missions' => $missions,
            'margin' => $margin,
            'notifications' => $notificationsRepository->findBy([
               'unread' => 1,
               'client' => $this->getUser()
            ]),
            'page' => $page
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
     * @Route("/process-mission-view/{id}", name="process_mission_view")
     */
    public function viewPaymentPage($id,Request $request,
                                    MangoPayService $mangoPayService,
                                    UserMissionRepository $missionRepo,
                                    ClientInfoRepository $clientInfoRepository,
                                    MissionPaymentRepository $missionPaymentRepository){

        $em = $this->getDoctrine()->getManager();

        $userMissionTblId = $missionRepo->findOneBy(['id'=>$id]);

        $tansClientId = $clientInfoRepository->findOneBy(['client'=>$userMissionTblId->getClient()]);

        $mangopayid = $tansClientId->getMangopayUserId();

        if(isset($mangopayid) == null){
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
            $tansClientId->setMangopayUserId($mangoUser->Id);
            $tansClientId->setMangopayWalletId($wallet->Id);
            $tansClientId->setMangopayCreatedAt(new \DateTime());
            $em->persist($tansClientId);

            $em->flush();
        }


        $mangoUser = $mangoPayService->getUser($tansClientId->getMangopayUserId());

        $createdCardRegister = $mangoPayService->setCardRegistration($mangoUser->Id);

        $session  = new Session();
        $session->remove('card_id');

        $session->set('card_id',$createdCardRegister->Id);

        $returnUrl = 'http' . ( isset($_SERVER['HTTPS']) ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'];

        $em = $this->getDoctrine()->getManager();
        $options = $this->getDoctrine()->getRepository(Option::class);

        $tax = $options->findOneBy(['slug' => 'tax']);
        $margin = $options->findOneBy(['slug' => 'margin']);

        $mission = $missionRepo->activePrices($id);

        $cityMakerType = '';
        if($mission->getIsTvaApplicable() != NULL)
        {
            $cityMakerType = CompanyStatus::COMPANY;
        }

        $options = $this->getDoctrine()->getRepository(Option::class);

        $tax = $options->findOneBy(['slug' => 'tax']);
        $margin = $options->findOneBy(['slug' => 'margin']);

        $first_result = $missionPaymentRepository->getPrices($mission->getUserMissionPayment()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $last_result = $missionPaymentRepository->getPrices($mission->getActiveLog()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $result['price'] = $last_result['client_price'];
        $result['tax'] = $last_result['client_tax'];
        $result['total'] = $result['price'] + $result['tax'];
        $result['advance_payment'] = $first_result['client_total'];
        $result['need_to_pay'] = $result['total'] -  $result['advance_payment'];

        $amount = 0;$fee = 0;

        if($mission->getStatus() == MissionStatus::CREATED){

            $amount = $result['total'];

            $margin = $last_result['client_price'] - $last_result['cm_price'];

        }elseif($mission->getStatus() == MissionStatus::TERMINATE_REQUEST_INITIATED || $mission->getStatus() == MissionStatus::ONGOING){

            $margin = $last_result['cm_price'] - $first_result['cm_price'];

            $amount = $result['need_to_pay'];

        }

        return $this->render('b2b/client/transaction/payin.html.twig',[
            'createdCardRegister' => $createdCardRegister,
            'returnUrl' => $returnUrl,
            'id' => $id,
            'amount' => $amount,
            'mission' => $mission
        ]);

    }


    /**
     * @param $id
     * @param MangoPayService $mangoPayService
     * @param UserMissionRepository $missionRepo@
     * @Route("/process-mission-request/{id}", name="process_mission_request")
     */
    public function missionProcess($id,Request $request,
                                   MangoPayService $mangoPayService,
                                   UserMissionRepository $missionRepo,
                                   MissionPaymentRepository $missionPaymentRepository,
                                   ClientTransactionRepository $clientTransactionRepository,
                                   ClientInfoRepository $clientInfoRepository,
                                   MissionRecurringRepository $missionRecurringRepository
    )
    {




        $em = $this->getDoctrine()->getManager();
        $options = $this->getDoctrine()->getRepository(Option::class);

        $tax = $options->findOneBy(['slug' => 'tax']);
        $margin = $options->findOneBy(['slug' => 'margin']);

        $transaction = new ClientTransaction();
        $mission = $missionRepo->activePrices($id);

        $cityMakerType = '';
        if($mission->getIsTvaApplicable() != NULL)
        {
            $cityMakerType = CompanyStatus::COMPANY;
        }

        $first_result = $missionPaymentRepository->getPrices($mission->getUserMissionPayment()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $last_result = $missionPaymentRepository->getPrices($mission->getActiveLog()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $result['price'] = $last_result['client_price'];
        $result['tax'] = $last_result['client_tax'];
        $result['total'] = $result['price'] + $result['tax'];
        $result['advance_payment'] = $first_result['client_total'];
        $result['need_to_pay'] = $result['total'] -  $result['advance_payment'];

        $amount = 0;$fee = 0;

        if($mission->getStatus() == MissionStatus::CREATED){

            $amount = $result['total'];

            $margin = $last_result['client_price'] - $last_result['cm_price'];

        }elseif($mission->getStatus() == MissionStatus::TERMINATE_REQUEST_INITIATED || $mission->getStatus() == MissionStatus::ONGOING){

            $margin = $last_result['cm_price'] - $first_result['cm_price'];

            $amount = $result['need_to_pay'];

        }



        if($result['tax'] != 0){

            $tax_value = $tax->getValue() / 100 * $margin;

            if($result['need_to_pay'] != 0){

                $fee = $amount - ($margin + $tax_value);

            }else{

                $fee = $margin + $tax_value;

            }

        }else{

            if($result['need_to_pay'] != 0){
                $fee = $amount - $margin;
            }else{
                $fee = $margin;
            }


        }

        $userMissionTblId = $missionRepo->findOneBy(['id'=>$id]);

        $tansClientId = $clientInfoRepository->findOneBy(['client'=>$userMissionTblId->getClient()]);
        //$tansClientId = $clientTransactionRepository->findOneBy(['user'=>$userMissionTblId->getClient()]);
        $mangopayid = $tansClientId->getMangopayUserId();
        if(isset($mangopayid) == null){
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
            $tansClientId->setMangopayUserId($mangoUser->Id);
            $tansClientId->setMangopayWalletId($wallet->Id);
            $tansClientId->setMangopayCreatedAt(new \DateTime());
            $em->persist($tansClientId);

            $em->flush();
        }
        else{
            $mangoUser = $mangoPayService->getUser($tansClientId->getMangopayUserId());
            $wallet = $mangoPayService->getWalletId($tansClientId->getMangopayWalletId());
        }

        //Create Transaction
        $transaction->setUser($mission->getClient());
        $transaction->setAmount($amount);
        $transaction->setMangopayUserId($mangoUser->Id);
        $transaction->setMangopayWalletId($wallet->Id);
        $transaction->setPaymentStatus(false);
        $transaction->setMission($mission);
        if($result['need_to_pay'] != 0){
            $transaction->setTransactionType('PayIn-Excess');
        }else{
            $transaction->setTransactionType('PayIn');
        }

        $transaction->setTotalAmount($amount);
        $transaction->setFee($fee);
        $em->persist($transaction);

        $em->flush();

        $payment_type = $mission->getMissionType();$card_array = [];

        $session  = new Session();

        $em = $this->getDoctrine()->getManager();

        $card = $mangoPayService->finishCardRegistration($session->get('card_id'),$request->get('data'));

        if($card){

            if($payment_type != 'one-shot'){

                $serializer = $this->container->get('serializer');
                $card_details = new MissionRecurring();
                $card_details->setClient($userMissionTblId->getClient());
                $card_details->setMission($userMissionTblId);
                $card_details->setCardType($card->CardProvider);
                $card_details->setCardId($card->Id);
                $card_details->setPaymentDate(new \DateTime());
                $card_details->setInvoiceDate(new \DateTime());
                $card_details->setPaymentStatus('pending');
                $card_details->setCardAlias($card->Alias);
                $card_details->setCardExpirationDate($card->ExpirationDate);
                $card_details->setCardResponse($serializer->serialize($card, 'json'));
                $card_details->setCreatedAt(new \DateTime());
                $card_details->setUpdatedAt(new \DateTime());

                $em->persist($card_details);

                $mission_price_log = new MissionRecurringPriceLog();
                $mission_price_log->setMission($userMissionTblId);
                $mission_price_log->setActivePrice($mission->getActiveLog());
                $mission_price_log->setCycle(1);
                $mission_price_log->setMonth(date('F'));
                $mission_price_log->setYear(date('Y'));

                $em->persist($mission_price_log);

                $em->flush();

            }

            $card_array['card_type'] = $card->CardProvider;
            $card_array['card_id'] = $card->Id;

            $result  = $mangoPayService->getPayIn($mangoUser, $wallet, $amount * 100, $transaction->getId(),$mission->getId(),$fee * 100,$card_array);

            return $this->redirect('/client/mission/mission-accept-process/'.$transaction->getId().'/'.$result);//$this->redirect($result);//$this->redirect('/client/mission/mission-accept-process/'.$transaction->getId().'/'.$result);

        }else{

            return $this->render('b2b/client/transaction/failed.html.twig',['response' => 'card is not properly. your transaction is not completed']);

        }




    }

    /**
     * @Route("/mission-payin-process/", name="mission_payin_process")
     */
    public function customPayinForm(){

        return $this->render('b2b/client/transaction/payin.html.twig');
    }

    /**
     * @Route("/mission-accept-process/{id}/{transaction_id}", name="mission_accept_process")
     */
    public function missionAcceptProcess($id,$transaction_id ,ClientTransactionRepository $transactionRepo,
                                         ClientRepository $clientRepository,
                                         UserMissionRepository $missionRepo,
                                         Request $request,
                                         MangoPayService $mangoPayService,
                                         NotificationsRepository $notificationsRepository,
                                         Filesystem $filesystem,
                                         MissionPaymentRepository $missionPaymentRepository)
    {

        $response = $mangoPayService->getResponse($transaction_id);

        if($response->Status != 'FAILED'){

            $transaction = $transactionRepo->find($id);

            $mission_id = $transaction->getMission();

            $transaction->setMangopayTransactionId($transaction_id);
            $transaction->setPaymentStatus(true);

            $transaction->getMission()->setMissionAgreedClient(1);

            $em = $this->getDoctrine()->getManager();

            $mission = $missionRepo->activePrices($mission_id);

            $options = $this->getDoctrine()->getRepository(Option::class);

            $tax = $options->findOneBy(['slug' => 'tax']);

            $margin = $options->findOneBy(['slug' => 'margin']);

            $cityMakerType = $mission->getUser()->getPixie()->getBilling()->getStatus();

            $last_result = $missionPaymentRepository->getPrices($mission->getActiveLog()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

            $transaction->getMission()->getUserMissionPayment()->setUserBasePrice($last_result['cm_price']);
            $transaction->getMission()->getUserMissionPayment()->setCmTax($last_result['cm_tax']);
            $transaction->getMission()->getUserMissionPayment()->setCmTotal($last_result['cm_total']);
            $transaction->getMission()->getUserMissionPayment()->setClientPrice($last_result['client_price']);
            $transaction->getMission()->getUserMissionPayment()->setClientTax($last_result['client_tax']);
            $transaction->getMission()->getUserMissionPayment()->setClientTotal($last_result['client_total']);
            $transaction->getMission()->getUserMissionPayment()->setPcsPrice($last_result['pcs_price']);
            $transaction->getMission()->getUserMissionPayment()->setPcsTax($last_result['pcs_tax']);
            $transaction->getMission()->getUserMissionPayment()->setPcsTotal($last_result['pcs_total']);
            $transaction->getMission()->setMissionBasePrice($last_result['cm_price']);

            if($transaction->getMission()->getStatus() == MissionStatus::CREATED){

                $transaction->getMission()->setStatus(MissionStatus::ONGOING);
                $message = $mission_id->getClient().' a accepté votre devis et a effectué son pré-paiement, la mission peut démarrer. ';
                $notificationsRepository->insert($mission_id->getUser(),null,'mission_client_paid',$message,$mission_id->getId());

                $message = 'Notre partenaire a bien reçu votre pré-paiement. Le city-maker va être averti du cantonnement de cette somme et il va pouvoir démarrer la mission. ';
                $notificationsRepository->insert(null,$mission_id->getClient(),'mission_cliet_paid_complete',$message,$mission_id->getId());

            }elseif($transaction->getMission()->getStatus() == MissionStatus::ONGOING || $transaction->getMission()->getStatus() == MissionStatus::TERMINATE_REQUEST_INITIATED){

                $transaction->getMission()->setStatus(MissionStatus::TERMINATED);

                $filesystem->mkdir('invoices/'.$mission->getId(),0777);


                $client_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-client.pdf";

                $clientInvoicePath = "invoices/".$mission->getId().'/'.$client_filename;

                $this->container->get('knp_snappy.pdf')->generateFromHtml(
                    $this->renderView('b2b/invoice/client_invoice.html.twig',
                        array(
                            'mission' => $mission
                        )
                    ), $clientInvoicePath
                );

                $cm_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-cm.pdf";

                $cmInvoicePath = "invoices/".$mission->getId().'/'.$cm_filename;

                $this->container->get('knp_snappy.pdf')->generateFromHtml(
                    $this->renderView('b2b/invoice/cm_invoice.html.twig',
                        array(
                            'mission' => $mission
                        )
                    ), $cmInvoicePath
                );

                $pcs_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-pcs.pdf";


                $pcsInvoicePath = "invoices/".$mission->getId().'/'.$pcs_filename;

                $this->container->get('knp_snappy.pdf')->generateFromHtml(
                    $this->renderView('b2b/invoice/pcs_invoice.html.twig',
                        array(
                            'mission' => $mission
                        )
                    ), $pcsInvoicePath
                );

                $royalties = new Royalties();
                $royalties->setMission($mission_id);
                $royalties->setCm($mission_id->getUser());
                $royalties->setTax($tax->getValue());
                $royalties->setBasePrice($mission_id->getUserMissionPayment()->getUserBasePrice());
                $royalties->setTaxValue($mission_id->getUserMissionPayment()->getCmTax());
                $royalties->setTotalPrice($mission_id->getUserMissionPayment()->getCmTotal());
                $royalties->setInvoicePath($cmInvoicePath);
                $royalties->setStatus('pending');
                $royalties->setCycle('1');
                $royalties->setBankDetails(json_encode($response));
                $em->persist($royalties);
                $em->flush();

                $message = $mission_id->getClient().' a accepté votre devis et a effectué son pré-paiement, la mission peut démarrer. ';
                $notificationsRepository->insert($mission_id->getUser(),null,'mission_client_paid',$message,$mission_id->getId());

                $message = 'Notre partenaire a bien reçu votre pré-paiement. Le city-maker va être averti du cantonnement de cette somme et il va pouvoir démarrer la mission. ';
                $notificationsRepository->insert(null,$mission_id->getClient(),'mission_cliet_paid_complete',$message,$mission_id->getId());

            }

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
            $error = $this->mangoPayErrorResponses($response->ResultCode);
            return $this->render('b2b/client/transaction/failed.html.twig',['response' => $error]);

        }



    }

    public function mangoPayErrorResponses($code){


        $errors['009199'] = ['The error due to Card is not supported by Mangopay ,Amount is higher than the maximum amount per transaction,Operation doesn’t fit to your Mangopay account settings and Use of a non-3DSecure test card for a payment which requires 3DSecure'];
        $errors['001999'] = ['An incident or connection issue has occured and closed all transactions'];
        $errors['001001'] = ['The e-wallet does not contain enough funds to process the transaction'];
        $errors['001002'] = ['The user ID used as Author has to be the wallet owner'];
        $errors['001013'] = ['The user\'s bank has rejected the transaction. Users should contact his/her bank for more information.'];
        $errors['105101'] = ['The card number given doesn’t match the real number of the card.'];
        $errors['105102'] = ['The card holder name given doesn’t match the real owner of the card'];
        $errors['105103'] = ['The Personal Identification Number code is invalid. The user should check card information and retry.'];
        $errors['105104'] = ['The Personal Identification Number format is invalid. The user should check card information and retry'];
        $errors['101101'] = ['The error "Do not honor" is a message from the bank. You could get it for several raisons: Maximum amount spent per month has been reached on this card // Maximum amount spent on internet per month has been reached on this card // No more funds on bank account'];
        $errors['101105'] = ['The card has expired'];
        $errors['101106'] = ['The card is inactive'];
        $errors['101109'] = ['The payment period has expired'];
        $errors['101410'] = ['This is a card limitation on spent amount'];
        $errors['101116'] = ['The number of authorised daily transactions has been exceeded. Please contact our support to adjust the number of daily transactions.'];
        $errors['105202'] = ['Card number: invalid format'];
        $errors['105203'] = ['Expiry date: missing or invalid format'];
        $errors['105204'] = ['CVV: missing or invalid format'];
        $errors['02625'] = ['Invalid card number'];
        $errors['02626'] = ['Invalid date. Use mmdd format'];
        $errors['02627'] = ['Invalid CCV number'];

        if(isset($errors[$code])){
            return $errors[$code][0];
        }else{
            return 'please check in MangoPay for more details for the code '.$code;
        }

    }

    /**
     * Function used to create a slug associated to an "ugly" string.
     *
     * @param string $string the string to transform.
     *
     * @return string the resulting slug.
     */
    public function createSlug($string) {

        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-'
        );

        // -- Remove duplicated spaces
        $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);

        // -- Returns the slug
        return strtolower(strtr($string, $table));


    }


}
