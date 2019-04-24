<?php

namespace App\Controller\Front\V2\Account;

use App\Entity\Card;
use App\Entity\CardCollection;
use App\Entity\Page;
use App\Form\Front\UserType;
use App\Repository\CardCategoryRepository;
use App\Repository\CardCollectionRepository;
use App\Repository\CardRepository;
use App\Repository\RegionRepository;
use App\Repository\StaticPagesRepository;
use App\Repository\UserRepository;
use App\Service\GoogleCalendar;
use App\Utils\Pagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("v2/voyageur/compte/", name="front_v2_user_account_")
 * @Security("has_role('ROLE_USER')")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index()
    {
        return $this->render('v2/front/account/user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("home", name="index")
     */
    public function card(Request $request, CardRepository $cardRepo, CardCategoryRepository $categoriesRepo, RegionRepository $regionsRepo)
    {
        $user = $this->getUser();
        $filters = ["userFavorite"=>$user->getId()];
        $cards = $cardRepo->search($filters);
        $totalCards = $cardRepo->countSearchResult($filters);
        $categories = $categoriesRepo->findCategoriesByFavorites($user->getId());
        $regions = $regionsRepo->findAllActive();

        //-----------------------------------------------
        // Create the pager
        $page = new Page();
        $page->setName("Mes Cards");
        $page->setMetaTitle("Mes Cards");
        $page->setIndexed(false);

        return $this->render('v2/front/account/user/index.html.twig', array(
            'page' => $page,
            'cards' => $cards,
            'totalCards' => $totalCards,
            'categories' => $categories,
            'regions' => $regions
        ));
    }

    /**
     * @Route("city-makers", name="city_maker")
     */
     public function cityMaker(Request $request, UserRepository $userRepo)
     {
       $user = $this->getUser();

       $cityMakers = $userRepo->searchPixies(["pixies" => $user->getFavoritePixies()]);

       //-----------------------------------------------
       // Create the page

       $page = new Page();
       $page->setName("Pixies favoris");
       $page->setMetaTitle("Pixies favoris");
       $page->setIndexed(false);

       $uri = explode('/',$request->getRequestUri())[3];

       return $this->render('v2/front/account/user/city-makers.html.twig', array(
           'page' => $page,
           'cityMakers' => $cityMakers
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
