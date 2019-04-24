<?php

namespace App\Controller\Front\Pages;

use App\Controller\Front\SearchPageController;
use App\Entity\Page;
use App\Repository\CardCategoryRepository;
use App\Repository\CardRepository;
use App\Repository\CardWallRepository;
use App\Repository\UserRepository;
use App\Utils\Pagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/guide-voyage/", name="front_wall_")
 */

class WallCardsController extends SearchPageController
{

    /**
     * @Route("{slug}/{orderby}", defaults={"slug"=null, "orderby"=null}, name="index")
     */
    public function index(
        Request $request,
        UserRepository $usersRepo,
        CardRepository $cardsRepo,
        CardCategoryRepository $categoriesRepo,
        CardWallRepository $cardWallRepos
    ){
        $pagination = new Pagination($request->query->has("page")?intval($request->query->get('page')):1, 10);
        $searchParams = $this->getSearchParams($request);

        if($request->get("orderby")){
            $searchParams["orderby"] = $request->get("orderby");
        }

        if($request->attributes->get("slug") && $request->attributes->get("slug") != "recherche") {
            $page = $cardWallRepos->findOneBySlug($request->attributes->get("slug"));
            if (!$page) throw new NotFoundHttpException('error.not_found');

            $filters = [
                "regions" => [$page->getRegion()?$page->getRegion()->getSlug():""],
                "categories" => [],
                "text" => $searchParams["text"]
            ];

            foreach ($page->getCategories() as $category) {
                $filters["categories"][] = $category->getSlug();
            }

            $searchParams["regions"] = $filters["regions"];
            $searchParams["categories"] = $filters["categories"];
        }
        else{
            $filters = [
                "pixie" => $searchParams["pixie"],
                "regions" => $searchParams["regions"],
                "categories" => $searchParams["categories"],
                "text" => $searchParams["text"]
            ];
        }


        $pixies = $usersRepo->findRandomPixies();

        $cards = $cardsRepo->search($filters, $pagination->getIndex(), $pagination->getLimit(), $searchParams["orderby"]);
        $totalCards = $cardsRepo->countSearchResult($filters);

        $searchPixies = $usersRepo->searchPixies($filters);

        shuffle($searchPixies);
        $totalPixies = count($searchPixies);
        $searchPixies = array_slice($searchPixies, 0, 4);

        $pagination->setTotalItems($totalCards);

        $categories = $categoriesRepo->findAllActive();

        //--------------------------------------------
        // If no wall has been found, use a default search page

        if(!isset($page)) {
            $page = new Page();
            $page->setName("Wall");
            $page->setMetaTitle("L’avis et les bons plans de nos influenceurs - Pixies");
            $page->setMetaDescription("Les avis et bons plans de nos pixies influenceurs - Guide de voyage et tourisme");
            $page->setIndexed(false);
        }


        return $this->render('front/search/index.html.twig', [
            'page' => $page,
            'filters' => $searchParams,
            'categories' => $categories,
            'pixies' => $pixies,
            'cards' => $cards,
            'totalCards' => $totalCards,
            'searchPixies' => $searchPixies,
            'totalPixies' => $totalPixies,
            'pagination' => $pagination
        ]);

    }


}