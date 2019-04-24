<?php

namespace App\Controller\Front\Pages;

use App\Controller\Front\SearchPageController;
use App\Repository\CardWallRepository;
use App\Repository\PageCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/old-recherche/", name="old_front_search_")
 */

class SearchController extends SearchPageController
{

    /**
     * @Route("index", name="index")
     */
    public function index(
        Request $request,
        PageCategoryRepository $pagesCategoryRepo,
        CardWallRepository $cardWallRepo
    ){
        $searchParams = $this->getSearchParams($request);
        $filters = [
            "regions" => $searchParams["regions"],
            "categories" => $searchParams["categories"]
        ];

        $page = null;


        //----------------------------------------------
        // Search a Card
        //----------------------------------------------

        if($searchParams["type"] === "cards") {

            //----------------------------------------------
            // Try to find a card wall

            $page = $cardWallRepo->findOneByFilters($filters);

            if (count($page) > 0) {

                $page = $page[0];

                return $this->redirectToRoute('front_wall_index', [
                    'slug' => $page->getSlug(),
                    'search' => $searchParams["text"],
                    'orderby' => $searchParams["orderby"],
                    'request' => $request
                ], 307);

            } else {

                //----------------------------------------------
                // If no card wall, try to find a region page

                if (!empty($searchParams["regions"]) && count($searchParams["regions"]) === 1 && empty($searchParams["text"]) && empty($searchParams["categories"])) {
                    $page = $pagesCategoryRepo->findOneBySlug($searchParams["regions"][0]);

                    if ($page) {
                        return $this->redirectToRoute('front_regions_index', [
                            'slug' => $page->getSlug(),
                            'search' => $searchParams["text"],
                            'regions' => $searchParams["regions"],
                            'categories' => $searchParams["categories"],
                            'orderby' => $searchParams["orderby"],
                            'request' => $request,
                            'display' => $request->get('display')
                        ], 307);
                    }
                }

            }

            return $this->redirectToRoute('front_wall_index', [
                'slug' => "recherche",
                'pixie' => $searchParams["pixie"],
                'search' => $searchParams["text"],
                'regions' => $searchParams["regions"],
                'categories' => $searchParams["categories"],
                'orderby' => $searchParams["orderby"],
                'request' => $request
            ], 307);

        }

        //----------------------------------------------
        // Search a Pixie
        //----------------------------------------------

        elseif($searchParams["type"] === "pixies") {

            $categories = !empty($searchParams["categories"])?$searchParams["categories"]:[];
            $regions = !empty($filters["regions"])?$filters["regions"]:[];

            $isSingleRegion = (count($categories) === 0 && count($regions) === 1 && $searchParams["text"] === "")?true:false;
            $slug = ($isSingleRegion)?$regions[0]:null;

            return $this->redirectToRoute('front_pixies_index', [
                'slug' => $slug,
                'search' => (!$isSingleRegion && $searchParams["text"] !== "")?$searchParams["text"]:null,
                'regions' => (!$isSingleRegion)?$regions:null,
                'categories' => (!$isSingleRegion)?$categories:null,
                'request' => $request
            ], 307);

        }

    }



}