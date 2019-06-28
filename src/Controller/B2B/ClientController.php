<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Entity\Option;
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
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/client/", name="b2b_client_main_")
 * @Security("has_role('ROLE_USER')")
 */
class ClientController extends Controller
{
    /**
     * @Route("profile",name="profile")
     */
    public function profile(Request $request, FileUploader $fileUploader, UserPasswordEncoderInterface $passwordEncoder,Filesystem $filesystem)
    {
        $user = $this->getUser();

        $form = $this->createForm(ClientType::class,$user,['type' => 'edit']);

        $form->handleRequest($request);

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


            return $this->redirect('/client/profile');
        }

        return $this->render('b2b/client/profile.html.twig',[
           'user' => $user,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("index", name="index")
     */
    public function index(UserMissionRepository $missionRepo, NotificationsRepository $notificationRepo, ClientMissionProposalRepository $proposalRepo)
    {
        // Get client notifications
        $notifications = $notificationRepo->findBy(['client'=>$this->getUser(), 'unread' => 1],['id' => 'DESC']);

        //Get missions
        $missions = $missionRepo->findOngoingMissions($this->getUser(), 'client');

        //Get proposals
        $proposal_unique = [];

        $proposals = $proposalRepo->findBy(['client' => $this->getUser()],['id'=>'DESC'],8);

        foreach ($proposals as $proposal) {
            if(!in_array($proposal->getUser()->getId(),$proposal_unique)){
                $proposal_unique [$proposal->getId()] = $proposal->getUser()->getId();
            }
        }

        return $this->render('b2b/client/index.html.twig',[
            'notifications' => $notifications,
            'missions' => $missions,
            'proposals' => $proposals,
            'proposal_unique' => $proposal_unique
        ]);

    }


    /**
     * @Route("preview-mission", name="preview_mission")
     */
    public function previewMission(Request $request,UserMissionRepository $missionRepository,MissionPaymentRepository $missionPaymentRepository){


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

        return $this->render('b2b/client/mission/load-mission-preview.html.twig',[
            'mission' => $mission,
            'route' => $route,
            'filename' => $filename,
            'result' => $result,
            'margin' => $margin
        ]);

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
    public function previewPayment(Request $request,UserMissionRepository $missionRepository,OptionRepository $optionRepository){

//        $mission = $missionRepository->find($request->get('id'));
        $mission = $missionRepository->findBy(['id' => $request->get('id')]);
        $tax = $optionRepository->findBy(['slug' => 'tax']);
        return $this->render('b2b/client/mission/load-payment-preview.html.twig',[
            'mission' => $mission[0],
            'tax' => $tax[0]->getValue()
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
        $result['advance_payment'] = $first_result['client_price'];
        $result['need_to_pay'] = $result['total'] - $first_result['client_price'];
        dd($result);

        return new JsonResponse($result);

    }



}
