<?php

namespace App\Controller\Front\Pages;

use App\Repository\CardCategoryRepository;
use App\Repository\CardProjectRepository;
use App\Repository\CardRepository;
use App\Repository\PageCategoryRepository;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/v2", name="v2_front_homepage_")
 */

class HomepageController extends Controller
{

    /**
     * @Route("", name="index")
     */
    public function index(
        Request $request,
        PageRepository $pagesRepo,
        PageCategoryRepository $pagesCategoriesRepo,
        UserRepository $usersRepo,
        CardRepository $cardsRepo,
        CardCategoryRepository $categoriesRepo,
        CardProjectRepository $cardProjectRepository
    ){
        $page = $pagesRepo->findOneBySlug("accueil");
        $regions = $pagesCategoriesRepo->findAllActive();

        $pixies = $usersRepo->findRandomPixies();
        $cards = $cardsRepo->search([],1,10,'newest');
        $categories = $categoriesRepo->findAllActive();
        $user = $this->getUser();
        $isCardFavoritedFirstTime = false;

        if(!is_null($user))
        {
            $filters = ["userFavorite"=>$user->getId()];

            if(count($cardsRepo->search($filters)) == 0) {
                $isCardFavoritedFirstTime = true;
            }
        }

        // $cookie = $this->clearCookies();
        // dd($cookie);
        return $this->render('front/homepage/index.html.twig', [
            'page' => $page,
            'regions' => $regions,
            'categories' => $categories,
            'pixies' => $pixies,
            'cards' => $cards,
            'isCardFavoritedFirstTime' => $isCardFavoritedFirstTime
        ]);
    }



    /**
     * @Route("/pixie/{slug1}/{slug2}",name="pixie_redirect");
     */

    public function redirectPixieToCityMaker(Request $request)
    {
        return $this->redirect('/city-maker/'.$request->attributes->get('slug1').'/'.$request->attributes->get('slug2').'/');
//        dd($request->attributes->get('slug1'));
    }


    /**
     * @Route("/pixie/{slug1}/{slug2}/{slug3}",name="pixie_redirect_2");
     */

    public function redirectPixieToCityMaker2(Request $request)
    {
        return $this->redirect('/city-maker/'.$request->attributes->get('slug1').'/'.$request->attributes->get('slug2').'/'.$request->attributes->get('slug3').'/');
//        dd($request->attributes->get('slug1'));
    }

    private function clearCookies()
    {
         $response = new Response();
         $response->headers->clearCookie('intercom-session-iswx8vq4');
         return $response->send();
    }

    /**
    * @Route("/maps-test",name="maps_test")
    */
    public function mapsTest()
    {
        return $this->render("front/trial.html.twig");
    }//End of maps test
}