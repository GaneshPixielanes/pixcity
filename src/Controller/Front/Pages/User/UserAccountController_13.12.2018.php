<?php

namespace App\Controller\Front\Pages\User;

use App\Entity\Card;
use App\Entity\CardCollection;
use App\Entity\Page;
use App\Form\Front\UserType;
use App\Repository\CardCategoryRepository;
use App\Repository\CardCollectionRepository;
use App\Repository\CardRepository;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use App\Service\GoogleCalendar;
use App\Utils\Pagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/voyageur/compte", name="front_user_account_")
 * @Security("has_role('ROLE_USER')")
 */

class UserAccountController extends Controller
{
    /**
     * @Route("/parametres", name="settings")
     */
    public function settings(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();

        //-----------------------------------------------
        // Create the form

        $form = $this->createForm(UserType::class, $user, ["pixie" => false,"type" => "edit"]);
        $form->handleRequest($request);


        //-----------------------------------------------
        // On submit

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

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Mes paramètres");
        $page->setMetaTitle("Mes paramètres");
        $page->setIndexed(false);

        return $this->render('front/account/user/settings.html.twig', array(
            'page' => $page,
            'form' => $form->createView()
        ));

    }


    /**
     * @Route("/pixies", name="pixies")
     */
    public function pixies(Request $request, UserRepository $userRepo)
    {
        $user = $this->getUser();

        $pixies = $userRepo->searchPixies(["pixies" => $user->getFavoritePixies()]);

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Pixies favoris");
        $page->setMetaTitle("Pixies favoris");
        $page->setIndexed(false);

        return $this->render('front/account/user/pixies.html.twig', array(
            'page' => $page,
            'pixies' => $pixies
        ));

    }


    /**
     * @Route("/cards", name="cards")
     */
    public function cards(Request $request, CardRepository $cardRepo, CardCategoryRepository $categoriesRepo, RegionRepository $regionsRepo)
    {
        $user = $this->getUser();

        $pagination = new Pagination($request->query->has("page")?intval($request->query->get('page')):1, 10);

        $filters = ["userFavorite"=>$user->getId()];

        $cards = $cardRepo->search($filters);

        $totalCards = $cardRepo->countSearchResult($filters);
        $pagination->setTotalItems($totalCards);

        $categories = $categoriesRepo->findAllActive();
        $regions = $regionsRepo->findAllActive();

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Mes Cards");
        $page->setMetaTitle("Mes Cards");
        $page->setIndexed(false);

        return $this->render('front/account/user/cards.html.twig', array(
            'page' => $page,
            'cards' => $cards,
            'totalCards' => $totalCards,
            'categories' => $categories,
            'regions' => $regions,
            'pagination' => $pagination
        ));

    }


    /**
     * @Route("/collections", name="collections")
     */
    public function collections(Request $request, CardRepository $cardRepo, CardCollectionRepository $collectionRepo, CardCategoryRepository $categoriesRepo, RegionRepository $regionsRepo)
    {
        $user = $this->getUser();

        //-----------------------------------------------
        // Get user favorites

        $pagination = new Pagination($request->query->has("page")?intval($request->query->get('page')):1, 10);

        $filters = ["userFavorite"=>$user->getId()];

        $cards = $cardRepo->search($filters);

        $totalCards = $cardRepo->countSearchResult($filters);
        $pagination->setTotalItems($totalCards);

        $categories = $categoriesRepo->findAllActive();
        $regions = $regionsRepo->findAllActive();


        //-----------------------------------------------
        // Get user collections

        $collections = $collectionRepo->findUserCollections($user);

        $totalCards = 0;
        foreach($collections as $collection){
            if($collection instanceof CardCollection)
                $totalCards += count($collection->getCards());
        }


        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Mes Collections");
        $page->setMetaTitle("Mes Collections");
        $page->setIndexed(false);

        return $this->render('front/account/user/collections.html.twig', array(
            'page' => $page,
            'cards' => $cards,
            'totalCards' => $totalCards,
            'collections' => $collections,
            'categories' => $categories,
            'regions' => $regions,
            'pagination' => $pagination
        ));

    }


    /**
     * @Route("/agendas/{id}", defaults={"id"=null}, name="agendas")
     */
    public function calendars(Request $request, CardRepository $cardRepo, CardCollectionRepository $collectionRepo, CardCategoryRepository $categoriesRepo, GoogleCalendar $googleCalendar)
    {
        $user = $this->getUser();

        //------------------------------------
        // Get user cards

        $pagination = new Pagination($request->query->has("page")?intval($request->query->get('page')):1, 100);

        $filters = ["userFavorite"=>$user->getId()];

        $cards = $cardRepo->search($filters);

        $totalCards = $cardRepo->countSearchResult($filters);
        $pagination->setTotalItems($totalCards);


        //-----------------------------------------------
        // Get user collections

        $collections = $collectionRepo->findUserCollections($user);


        //------------------------------------
        // Get user google calendars

        $res = $googleCalendar->load($request);
        $redirectUrl = (true !== $res)?$res:null;

        $calendars = $googleCalendar->getCalendars();


        //-----------------------------------------------
        // Get categories

        $categories = [];
        foreach($cards as $card){
            if($card instanceof Card) {
                foreach($card->getCategories() as $category){
                    $categories[] = $category;
                }
            }
        }

        $categories = array_unique($categories);

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Mes Agendas");
        $page->setMetaTitle("Mes Agendas");
        $page->setIndexed(false);

        return $this->render('front/account/user/agendas.html.twig', array(
            'page' => $page,

            'cards' => $cards,
            'totalCards' => $totalCards,
            'pagination' => $pagination,
            'collections' => $collections,

            'calendars' => $calendars,

            'categories' => $categories,

            'redirectUrl' => $redirectUrl
        ));

    }

}