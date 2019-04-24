<?php

namespace App\Controller\Front\Pages\Pixie;

use App\Constant\CardProjectStatus;
use App\Constant\CompanyStatus;
use App\Entity\Page;
use App\Entity\UserLink;
use App\Form\Front\UserType;
use App\Repository\CardCategoryRepository;
use App\Repository\CardProjectRepository;
use App\Repository\CardRepository;
use App\Repository\RegionRepository;
use App\Repository\TransactionRepository;
use App\Utils\Pagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/pixie/compte", name="front_pixie_account_")
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
    public function settings(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();

        //-----------------------------------------------
        // Create the form

        $form = $this->createForm(UserType::class, $user, ["pixie" => true,"type" => "edit"]);
        $form->handleRequest($request);


        //-----------------------------------------------
        // On submit

        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {

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
        if(
           $pixieBilling->getBillingIban() == 'FR3330002005500000157841Z25' ||
           $pixieBilling->getBillingBic() == 'CRLYFRPP' ||
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
}