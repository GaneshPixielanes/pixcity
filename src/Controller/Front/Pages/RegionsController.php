<?php

namespace App\Controller\Front\Pages;

use App\Controller\Front\SearchPageController;
use App\Repository\CardCategoryRepository;
use App\Repository\CardRepository;
use App\Repository\PageCategoryRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/old-regions/", name="old_front_regions_")
 */

class RegionsController extends SearchPageController
{

    /**
     * @Route("{slug}/{orderby}/{display}", defaults={"slug"=null, "orderby"=null,"display"=false}, name="index")
     */
    public function index(
        Request $request,
        PageCategoryRepository $pagesCategoryRepo,
        UserRepository $usersRepo,
        CardRepository $cardsRepo,
        CardCategoryRepository $categoriesRepo
    ){
        $searchParams = $this->getSearchParams($request);
        $isCardFavoritedFirstTime = false;
        $limit = 10;

        if($request->get("orderby")){
            $searchParams["orderby"] = $request->get("orderby");
        }
        else if(!isset($searchParams["orderby"])){
            $searchParams["orderby"] = "popular";
        }

        $page = $pagesCategoryRepo->findOneBySlug($request->attributes->get("slug"));
        if(!$page) throw new NotFoundHttpException('error.not_found');

        if($request->get("orderby")){
            $page->setIndexed(false);
        }

        $regionId = $page->getRegion()->getId();

        $totalPixies = $usersRepo->countPixieByRegion($regionId);
        $totalCards = $cardsRepo->countCardsByRegion($regionId);

        $pixies = $usersRepo->findRandomPixies($regionId);


        $searchParams["regions"] = [$page->getRegion()->getSlug()];

        if($request->attributes->get("display") == "true")
        {
            $limit = $cardsRepo->countCardsByRegion($regionId);
        }
        $cards = $cardsRepo->search($searchParams, 1, $limit, $searchParams["orderby"]);

        $newestCards = $cardsRepo->search(["regions"=>[$regionId]], 1, 10, "newest");
        $mostPopularCards = $cardsRepo->search(["regions"=>[$regionId], 1, 10, "popular"]);

        $categories = $categoriesRepo->findAllActive();
        $user = $this->getUser();
        if(!is_null($user))
        {
            $filters = ["userFavorite"=>$user->getId()];

            if(count($cardsRepo->search($filters)) == 0) {
                $isCardFavoritedFirstTime = true;
            }
        }
        return $this->render('front/regions/index.html.twig', [
            'page' => $page,
            'filters' => $searchParams,
            'totalPixies' => $totalPixies,
            'totalCards' => $totalCards,
            'categories' => $categories,
            'cards' => $cards,
            'newestCards' => $newestCards,
            'mostPopularCards' => $mostPopularCards,
            'pixies' => $pixies,
            'isCardFavoritedFirstTime' => $isCardFavoritedFirstTime
        ]);
    }


}