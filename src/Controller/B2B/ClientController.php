<?php

namespace App\Controller\B2B;

use App\Constant\CompanyStatus;
use App\Constant\MissionStatus;
use App\Controller\B2B\Client\MissionController;
use App\Entity\Option;
use App\Entity\Page;
use App\Form\B2B\ClientType;
use App\Repository\ClientMissionProposalRepository;
use App\Repository\MissionPaymentRepository;
use App\Repository\MissionRepository;
use App\Repository\NotificationsRepository;
use App\Repository\OptionRepository;
use App\Repository\UserMissionRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @Route("/client/", name="b2b_client_main_")
 * @Security("has_role('ROLE_USER')")
 */
class ClientController extends Controller
{

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("profil",name="profile")
     */
    public function profile(Request $request,OptionRepository $options,FileUploader $fileUploader, UserPasswordEncoderInterface $passwordEncoder,Filesystem $filesystem)
    {

        $user = $this->getUser();

        $form = $this->createForm(ClientType::class,$user,['type' => 'edit']);

        $form->handleRequest($request);

        $tax = $options->findBy(['slug' => 'tax']);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $file = $request->files->get('files');

            if(!is_null($file))
            {
                $user->setProfilePhoto($file);
            }

            if(trim($user->getPlainPassword()) != '')
            {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }


            $entityManager->persist($user);
            $entityManager->flush();

            // Move profile photo to the right directory
            if($filesystem->exists('uploads/clients/'.$user->getProfilePhoto()) && $user->getProfilePhoto() != ''){
                $filesystem->copy('uploads/clients/'.$user->getProfilePhoto(),'uploads/clients/'.$user->getId().'/'.$user->getProfilePhoto());
            }

            if($this->session->has('login_by')){
                $this->session->remove('login_by');
                $this->session->set('login_by',['type' => 'login_client','entity' => $user]);
            }



            return $this->redirect('/client/index');
        }
        #SEO
        $page = new Page();
        $page->setMetaTitle("Pix.city Services : profil client");
        $page->setMetaDescription("Retrouvez dans cet espace votre profil client");

        return $this->render('b2b/client/profile.html.twig',[
            'user' => $user,
            'tax' => $tax[0],
            'form' => $form->createView(),
            'page' => $page
        ]);
    }


    /**
     * @Route("index", name="index")
     */
    public function index(UserMissionRepository $missionRepo, NotificationsRepository $notificationRepo, ClientMissionProposalRepository $proposalRepo,OptionRepository $optionRepository)
    {
        // Get client notifications
        $notifications = $notificationRepo->findBy(['client'=>$this->getUser(), 'unread' => 1],['id' => 'DESC']);

        //Get missions
        $missions = $missionRepo->findOngoingMissions($this->getUser(), 'client');

        //Get proposals
        $proposal_unique = [];

        $proposals = $proposalRepo->findBy(['client' => $this->getUser()],['id'=>'DESC'],8);

        $margin = $optionRepository->findOneBy(['slug' => 'margin']);

        foreach ($proposals as $proposal) {
            if(!in_array($proposal->getUser()->getId(),$proposal_unique)){
                $proposal_unique [$proposal->getId()] = $proposal->getUser()->getId();
            }
        }
        #SEO
        $page = new Page();
        $page->setMetaTitle("Pix.city Services : Accueil client");
        $page->setMetaDescription("Retrouvez dans cet espace vos missions en cours, vos factures ainsi que vos city-makers préférés.");

        return $this->render('b2b/client/index.html.twig',[
            'notifications' => $notifications,
            'missions' => $missions,
            'proposals' => $proposals,
            'proposal_unique' => $proposal_unique,
            'margin' => $margin,
            'page' => $page
        ]);

    }


    /**
     * @Route("preview-mission", name="preview_mission")
     */
    public function previewMission(Request $request,UserMissionRepository $missionRepository,
                                   MissionPaymentRepository $missionPaymentRepository,NotificationsRepository $notificationRepo){

        if($request->get('notification_id') != 0){

            $em = $this->getDoctrine()->getManager();

            $notification = $notificationRepo->find($request->get('notification_id'));
            $notification->setUnread(0);

            $em->persist($notification);
            $em->flush();

        }


        $mission = $missionRepository->activePrices($request->get('id'));

        $options = $this->getDoctrine()->getRepository(Option::class);

        $tax = $options->findOneBy(['slug' => 'tax']);

        $margin = $options->findOneBy(['slug' => 'margin']);

        $cityMakerType = $mission->getUser()->getPixie()->getBilling()->getStatus();

        $margin = $margin->getValue();

        $tax = $tax->getValue();

        $result = $missionPaymentRepository->getPrices($mission->getLog()->getUserBasePrice(), $margin, $tax, $cityMakerType);

        $filename = $this->createSlug($mission->getTitle());

        $route = $request->get('route');

        $notifications = $notificationRepo->findBy(['client'=>$this->getUser(), 'unread' => 1],['id' => 'DESC']);

        return $this->render('b2b/client/mission/load-mission-preview.html.twig',[
            'mission' => $mission,
            'route' => $route,
            'filename' => $filename,
            'result' => $result,
            'margin' => $margin,
            'notifications' => $notifications
        ]);

    }

    /**
     * @Route("preview-invoce", name="preview_invoice")
     */
    public function previewInvoice(Request $request,UserMissionRepository $userMissionRepository){

        $id = $request->get('id');
        $type =  $request->get('type');
        $cycle = $request->get('cycle');
        $logId = $request->get('logid');

        $mission = $userMissionRepository->find($id);

        if($type == 'one-shot'){

            $client_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-client.pdf";

            $result = "http".(isset($_SERVER['HTTPS']) ? "s" : null).'://'.$_SERVER["HTTP_HOST"].'/invoices/'.$mission->getId().'/'.$client_filename;

        }else{

            $client_filename = 'PX-'.$mission->getId().'-'.$logId."-client.pdf";

            $result = "http".(isset($_SERVER['HTTPS']) ? "s" : null).'://'.$_SERVER["HTTP_HOST"].'/invoices/Recurring/'.$mission->getId().'/'.$cycle.'/'.$client_filename;

        }

        return new JsonResponse($result);

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

    /**
     * @Route("preview-payment", name="preview_payment")
     */
    public function previewPayment(Request $request,UserMissionRepository $missionRepository,OptionRepository $optionRepository,MissionPaymentRepository $missionPaymentRepository,NotificationsRepository $notificationsRepository){

        if($request->get('notification_id') != 0){

            $em = $this->getDoctrine()->getManager();

            $notification = $notificationsRepository->find($request->get('notification_id'));
            $notification->setUnread(0);

            $em->persist($notification);
            $em->flush();
        }

//        $mission = $missionRepository->find($request->get('id'));
        $mission = $missionRepository->activePrices($request->get('id'));

        $options = $this->getDoctrine()->getRepository(Option::class);

        $tax = $options->findOneBy(['slug' => 'tax']);
        $margin = $options->findOneBy(['slug' => 'margin']);

        $cityMakerType = '';
        if($mission->getIsTvaApplicable() != NULL)
        {
            $cityMakerType = CompanyStatus::COMPANY;
        }

        $last_result = $missionPaymentRepository->getPrices($mission->getActiveLog()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $tax = $optionRepository->findBy(['slug' => 'tax']);
        return $this->render('b2b/client/mission/load-payment-preview.html.twig',[
            'mission' => $mission,
            'tax' => $tax[0]->getValue(),
            'last_result' => $last_result
        ]);

    }

    /**
     * @Route("mission-details", name="mission_details")
     */
    public function missionDetail(Request $request,UserMissionRepository $missionRepository,MissionPaymentRepository $missionPaymentRepository){

        $mission = $missionRepository->activePrices($request->get('id'));

        $options = $this->getDoctrine()->getRepository(Option::class);

        $tax = $options->findOneBy(['slug' => 'tax']);
        $margin = $options->findOneBy(['slug' => 'margin']);

        $cityMakerType = $mission->getUser()->getPixie()->getBilling()->getStatus();

        $first_result = $missionPaymentRepository->getPrices($mission->getUserMissionPayment()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $last_result = $missionPaymentRepository->getPrices($mission->getActiveLog()->getUserBasePrice(), $margin->getValue(), $tax->getValue(), $cityMakerType);

        $result = [];

        $result['price'] = $last_result['client_price'];
        $result['tax'] = $last_result['client_tax'];
        $result['total'] = $result['price'] + $result['tax'];
        $result['advance_payment'] = $first_result['client_total'];
        $result['need_to_pay'] = $result['total'] - $result['advance_payment'];
        $result['refund_amount'] = $result['advance_payment'] - $result['total'];

        return new JsonResponse($result);

    }


    /**
     * @Route("brief-file/download/{id}", name="brief_file_invoices")
     */
    public function zipDownloadDocumentsAction($id, UserMissionRepository $userMissionRepository)
    {

        $mission = $userMissionRepository->findOneBy(['id'=>$id]);
        $filename = $this->createSlug($mission->getTitle());

        $documents = $mission->getDocuments();

        $files = [];

        foreach ($documents as $document) {
            array_push($files,  $this->get('kernel')->getProjectDir()."/public/uploads/missions/temp/".$document->getName());
        }



        // Create new Zip Archive.
        $zip = new \ZipArchive();

        // The name of the Zip documents.
        $zipName = 'm_'.$id.'.zip';

        $zip->open($zipName,  \ZipArchive::CREATE);
        foreach ($files as $file) {
            $zip->addFromString(basename($file),  file_get_contents($file));
        }
        $zip->close();

        $response = new Response(file_get_contents($zipName));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
        $response->headers->set('Content-length', filesize($zipName));

        @unlink($zipName);

        return $response;
    }


//    /**
//     * @Route("upload/mangopaykyc", name="mangopaykyc")
//     */
//    public function uploadMangopaykyc(Request $request, FileUploader $fileUploader)
//    {
//        $file = $request->files->get('file');
//        $fileName = $fileUploader->upload($file, 'mangopay_kyc/client/addr1/'.$request->get('id'), true);
//        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
//    }
//    /**
//     * @Route("upload/mangopayKycAddr", name="mangopayKycAddr")
//     */
//    public function uploadMangopayAddr(Request $request, FileUploader $fileUploader)
//    {
//        $file = $request->files->get('file');
//        dd($request->get('id'));
//        $fileName = $fileUploader->upload($file, 'mangopay_kyc/client/addr2/'.$request->get('id'), true);
//        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
//    }

}
