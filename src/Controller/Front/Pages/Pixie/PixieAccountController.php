<?php

namespace App\Controller\Front\Pages\Pixie;


use App\Constant\CardProjectStatus;
use App\Constant\CompanyStatus;
use App\Constant\TransactionStatus;
use App\Entity\Card;
use App\Entity\CardProject;
use App\Entity\CommunityMedia;
use App\Entity\Page;
use App\Entity\UserAvatar;
use App\Entity\UserMedia;
use App\Form\Front\CardType;
use App\Form\Front\UserType;
use App\Entity\UserLink;
use App\Entity\Address;

use App\Repository\CardCategoryRepository;
use App\Repository\CardProjectRepository;
use App\Repository\CardRepository;
use App\Repository\CommunityMediaRepository;
use App\Repository\RegionRepository;
use App\Repository\TransactionRepository;
use App\Service\FileUploader;
use App\Utils\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/city-maker/compte", name="front_pixie_account_")
 * @Security("has_role('ROLE_PIXIE')")
 */

class PixieAccountController extends Controller
{


    /**
     * @Route("/cards/status", name="cards_status")
     */
    public function cards_status(Request $request, CardRepository $cardsRepo, CardProjectRepository $projectsRepo)
    {
        $pagination = new Pagination($request->query->has("page")?intval($request->query->get('page')):1, 20);

        $filters = ["pixie" => $this->getUser()->getId(), "status" => CardProjectStatus::PIXIE_SUBMITTED];
        $projects = $projectsRepo->search($filters, $pagination->getIndex(), $pagination->getLimit());
        $totalProjects = $projectsRepo->countSearchResult($filters);

        $pagination->setTotalItems($totalProjects);

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Mes Cards en attente");
        $page->setMetaTitle("Mes Cards en attente");
        $page->setIndexed(false);

        return $this->render('front/account/pixie/cards-status.html.twig', array(
            'page' => $page,
            'cards' => $projects,
            'totalCards' => $totalProjects,
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/cards/validees", name="cards_validated")
     */
    public function cards_validated(Request $request, CardRepository $cardsRepo, CardCategoryRepository $categoriesRepo, RegionRepository $regionsRepo)
    {
        $pagination = new Pagination($request->query->has("page")?intval($request->query->get('page')):1, 10);

        $filters = ["pixie" => $this->getUser()->getId()];

        $cards = $cardsRepo->search($filters);

        $totalCards = $cardsRepo->countSearchResult($filters);
        $pagination->setTotalItems($totalCards);

        $categories = $categoriesRepo->findAllActive();
        $regions = $regionsRepo->findAllActive();

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Mes Cards validées");
        $page->setMetaTitle("Mes Cards validées");
        $page->setIndexed(false);

        return $this->render('front/account/pixie/cards-validated.html.twig', array(
            'page' => $page,
            'cards' => $cards,
            'totalCards' => $totalCards,
            'categories' => $categories,
            'regions' => $regions,
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/parametres", name="settings")
     */
    public function settings(Request $request, UserPasswordEncoderInterface $passwordEncoder, TransactionRepository $transactionRepository)
    {
        $user = $this->getUser();
        $message = '';
        $bankDetailsEditable = true;
        //-----------------------------------------------
        // Create the form

        $form = $this->createForm(UserType::class, $user, ["pixie" => true,"type" => "edit"]);
        $form->handleRequest($request);
        $bankTransactions = $transactionRepository->search(['status'=>TransactionStatus::PIXIE_ASKED_BANKTRANSFER_PAYMENT, 'pixie'=>$user]);
        $chequeTransactions = $transactionRepository->search(['status'=>TransactionStatus::PIXIE_ASKED_CHECK_PAYMENT, 'pixie' => $user]);

//        dd($bankTransactions);
        if(!empty($bankTransactions) || !empty($chequeTransactions))
        {
            if(!empty($bankTransactions))
            {
                $message = $bankTransactions[0]->getName();
            }
            else{
                $message = $chequeTransactions[0]->getName();
            }
            $bankDetailsEditable = false;
        }

        //-----------------------------------------------
        // On submit

        $errors = [];

        if ($form->isSubmitted()) {
//        if ($form->isSubmitted() && $form->isValid()) {
            if(!$bankDetailsEditable)
            {
                // $this->addFlash('settings_error',$message);
                // return $this->redirectToRoute('front_pixie_account_settings');
            }
            // Encode the password
            if($user->getPlainPassword()) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            // Save the user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Add the flash message
            $this->addFlash('account_saved_settings', '');

        }
        elseif($form->isSubmitted() && !$form->isValid()){

            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

        }

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Mes paramètres");
        $page->setMetaTitle("Mes paramètres");
        $page->setIndexed(false);
        return $this->render('front/account/pixie/settings.html.twig', array(
            'page' => $page,
            'form' => $form->createView(),
            'errors' => $errors,
            'bandDetailsEditable' => $bankDetailsEditable,
            'user' => $user
        ));

    }

    /**
     * @Route("/cards/demandes", name="cards_projects")
     */
    public function cards_projects(Request $request, CardProjectRepository $projectsRepo)
    {

        $pagination = new Pagination($request->query->has("page")?intval($request->query->get('page')):1, 20);

        $filters = ["pixie" => $this->getUser()->getId()];
        $projects = $projectsRepo->search($filters, $pagination->getIndex(), $pagination->getLimit());
        $totalProjects = $projectsRepo->countSearchResult($filters);

        $pagination->setTotalItems($totalProjects);

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Mes demandes de Cards");
        $page->setMetaTitle("Mes demandes de Cards");
        $page->setIndexed(false);

        return $this->render('front/account/pixie/cards-projects.html.twig', array(
            'page' => $page,
            'projects' => $projects,
            'totalProjects' => $totalProjects,
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/cards/paiements", name="cards_payments")
     */
    public function cards_payments(Request $request, CardProjectRepository $projectsRepo, TransactionRepository $transactionRepo)
    {
        $isWrongData = false;
        $pagination = new Pagination($request->query->has("page")?intval($request->query->get('page')):1, 20);

        $user = $this->getUser();

        $pixieBilling = $user->getPixie()->getBilling();
//        dd($pixieBilling->getAddress()-);
        if(
           $pixieBilling->getBillingIban() == 'FR3330002005500000157841Z25' &&
           $pixieBilling->getBillingBic() == 'CRLYFRPP' &&
           strtoupper($pixieBilling->getAddress()->getAddress()) == "27 RUE ARAGO"
        )
        {
            $isWrongData = true;
        }

        $ongoingRegistration = CompanyStatus::ONGOING_REGISTRATION === $pixieBilling->getStatus();


        $filters = [
            "pixie" => $this->getUser()->getId(),
            "status" => [CardProjectStatus::VALIDATED],
            "no_transaction" => true
        ];
        $unpayedProjects = $projectsRepo->search($filters, 1, 100);


        $filters = [
            "pixie" => $this->getUser()->getId()
        ];
        $transactions = $transactionRepo->search($filters, $pagination->getIndex(), $pagination->getLimit());
        $totalTransaction = $transactionRepo->countSearchResult($filters);

        $pagination->setTotalItems($totalTransaction);

        $totalPayementPending = 0;
        foreach($unpayedProjects as $project){
            $totalPayementPending += $project->getPrice();
        }

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Mes rémunérations");
        $page->setMetaTitle("Mes rémunérations");
        $page->setIndexed(false);

        return $this->render('front/account/pixie/cards-payments.html.twig', array(
            'page' => $page,
            'ongoingRegistration' => $ongoingRegistration,
            'unpayedProjects' => $unpayedProjects,
            'transactions' => $transactions,
            'totalTransaction' => $totalTransaction,
            'totalPayementPending' => $totalPayementPending,
            'pagination' => $pagination,
            'isWrongData' => $isWrongData
        ));
    }

    /**
     * @Route("/address",name="card_address")
     */
    public function address(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Create a new card and a project
        // The created card will be associated with the project
        // All new address will be stored as a card here
        $card = new Card();
        
        $form = $this->createForm(CardType::class, $card);
        
        $form->handleRequest($request);

        // Form validation check
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the card/address is already present or not
            // If card/address is already available, do not save it but throw an error
//            dd($request->get('card')['address']);
            $duplicateCard = $repository = $this->getDoctrine()->getRepository(Address::class);
            $duplicateCard = $duplicateCard->findBy([
                'address'=>$request->get('card')['address']['address']
            ]);
            if(count($duplicateCard) > 0)
            {
                $this->addFlash('error','This address has already been submitted, please add a new address');
                return $this->redirectToRoute('front_pixie_account_card_address');
            }
            //Create new project
            $project = new CardProject();

            // Assign slug/url for the newly created card/project
            if(empty($card->getSlug())){
                $card->generateSlug();
            }
            //Set currently logged in city maker to the project
            $entityManager = $this->getDoctrine()->getManager();
            $project->setName($form->getData()->getName());
            $project->setIsInterview('0');
            $project->setPixie($this->getUser());
            // Set the same region and department for both project and department
            $project->setCard($card);
            $project->setRegion($card->getRegion());
            $project->setDepartment($card->getDepartment());
            $project->setPrice('0'); // Price will be 0 for all address/cards
            $project->setCreatedAt(new \DateTime());

            $card->setPixie($this->getUser());
            // If the user has provided the password, activate the user by saving the password
            if($request->get('password') !== null)
            {
                $user = $this->getUser();
                $links = new UserLink();

                $password = $passwordEncoder->encodePassword($user, $request->get('password'));
                $user->setPassword($password); // Set the password

                $links->setType('instagram');
                $links->setUrl($request->get('instagram')); // Set user's Instagram
                $links->setUser($user);

                $entityManager->persist($user);
                $entityManager->persist($links);
            }
            $entityManager->persist($card);

            $entityManager->persist($project);

            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

//            return $this->redirectToRoute('admin_cards_list');
        }

        $page = new Page();
        $page->setName("Proposer des adresses ");
        $page->setMetaTitle("Proposer des adresses ");
        $page->setIndexed(false);

        return $this->render('front/account/pixie/cards-addresses.html.twig',[
            'page' => $page,
            'form' => $form->createView(),
            'projects' => $this->getUser()->getProjects()
        ]);
    }

    /**
     * @Route("/community/manager",name="community_manager")
     */
    public function community_manager(Request $request){

        $user = $this->getUser();
        // Create the form
        $form = $this->createForm(UserType::class, $user, ["b2b" => true,"type" => "edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $entityManager = $this->getDoctrine()->getManager();


            $product = $entityManager->getRepository(Product::class)->find($id);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('front_pixie_account_community_manager');
        }

        // Create the page
//        $page = new Page();
//        $page->setName("Mes Cards en attente");
//        $page->setMetaTitle("Mes Cards en attente");
//        $page->setIndexed(false);

//        return $this->render('front/account/pixie/community-manager.html.twig', array(
//            'page' => $page,
//            'form' => $form->createView(),
//            'user' => $user
//        ));
//        $user = $this->getUser();
//
//        // Create the form
//        $form = $this->createForm(UserType::class, $user, ["b2b" => true,"type" => "edit"]);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted()) {
//
//            $entityManager = $this->getDoctrine()->getManager();
//
//
//            $product = $entityManager->getRepository(Product::class)->find($id);
//
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($user);
//            $em->flush();
//
//            return $this->redirectToRoute('front_pixie_account_community_manager');
//        }
//
//        // Create the page
//        $page = new Page();
//        $page->setName("Mes Cards en attente");
//        $page->setMetaTitle("Mes Cards en attente");
//        $page->setIndexed(false);
//
//        return $this->render('front/account/pixie/community-manager.html.twig', array(
//            'page' => $page,
//            'form' => $form->createView(),
//            'user' => $user
//        ));

        return $this->render('b2b/static/index.html.twig');

    }


    /**
     * @Route("/fileuploadhandler", name="fileuploadhandler")
     */
    public function fileUploadHandler(Request $request) {

        $user = $this->getUser();

        $output = array('uploaded' => false);

        $file = $request->files->get('file');

        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $uploadDir = $this->get('kernel')->getRootDir() . '/../public/uploads/community_media/'.$user->getId();

        if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }



        if ($file->move($uploadDir, $fileName)) {
            $em = $this->getDoctrine()->getManager();

            $mediaEntity = new CommunityMedia();
            $mediaEntity->setName($fileName);
            $mediaEntity->setUpdatedBy('1');
            $mediaEntity->setUser($this->getUser());

            $em->persist($mediaEntity);
            $em->flush();

            $output['uploaded'] = true;
            $output['fileName'] = $fileName;
        }
        return new JsonResponse($output);
    }

    /**
     * @Route("/image-display",name="display_image")
     */
    public function showImages(Request $request){

        $user = $this->getUser();
        $result = [];

        if(count($user->getCommunityMedia())){

            foreach($user->getCommunityMedia() as $media)
            {
                $obj['name'] = $media->getName();
                $obj['size'] = filesize('uploads/community_media/'.$user->getId().'/'.$media->getName());
                $obj['path'] = 'uploads/community_media/'.$user->getId().'/'.$media->getName();
                $obj['id'] = $user->getId();
                $result[] = $obj;
            }

        }

        return new JsonResponse($result);



    }

    /**
     * @Route("/image-delete",name="delete_image")
     */
    public function deleteImages(Request $request,CommunityMediaRepository $communityMediaRepository){

        $em = $this->getDoctrine()->getEntityManager();

        $media = $communityMediaRepository->findBy(['name' => $request->get('name')]);

        unlink('uploads/community_media/'.$media[0]->getName());

        $em->remove($media[0]);

        $em->flush();


        exit;


    }

}
