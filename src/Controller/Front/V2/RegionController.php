<?php

namespace App\Controller\Front\V2;

use App\Controller\Front\SearchPageController;
use App\Repository\CardCategoryRepository;
use App\Repository\CardRepository;
use App\Repository\PageCategoryRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="front_regions_")
 */
class RegionController extends SearchPageController
{
    /**
     * @Route("{slug}/{orderby}/{display}", defaults={"slug"=null, "orderby"=null,"display"=false}, name="index")
     */
    public function index(
        $slug,
        Request $request,
        PageCategoryRepository $pagesCategoryRepo,
        UserRepository $usersRepo,
        CardRepository $cardsRepo,
        CardCategoryRepository $categoriesRepo
    )
    {
        $searchParams = $this->getSearchParams($request);
        $isCardFavoritedFirstTime = false;

        if ($request->get("orderby")) {
            $searchParams["orderby"] = $request->get("orderby");
        } else if (!isset($searchParams["orderby"])) {
            $searchParams["orderby"] = "popular";
        }

        $page = $pagesCategoryRepo->findOneBySlug($request->attributes->get("slug"));
        if (!$page) throw new NotFoundHttpException('error.not_found');

        if ($request->get("orderby")) {
            $page->setIndexed(false);
        }

        $regionId = $page->getRegion()->getId();
        $regionName = $page->getRegion()->getName();

        $totalPixies = $usersRepo->countPixieByRegion($regionId);
        $totalCards = $cardsRepo->countCardsByRegion($regionId);

        $pixies = $usersRepo->findRandomPixies($regionId);


        $searchParams["regions"] = [$page->getRegion()->getSlug()];

        $cards = $cardsRepo->search($searchParams, 1, 10, $searchParams["orderby"]);

        $newestCards = $cardsRepo->search(["regions" => [$regionId]], 1, 10, "newest");
        $mostPopularCards = $cardsRepo->search(["regions" => [$regionId], 1, 10, "popular"]);

        // $categories = $categoriesRepo->findAllActive();
        $user = $this->getUser();
        if (!is_null($user)) {
            $filters = ["userFavorite" => $user->getId()];

            if (count($cardsRepo->search($filters)) == 0) {
                $isCardFavoritedFirstTime = true;
            }
        }

        $categories = $categoriesRepo->findCategoriesByRegion($page->getRegion());
        return $this->render('v2/front/region/index.html.twig', [
            'page' => $page,
            'filters' => $searchParams,
            'totalPixies' => $totalPixies,
            'totalCards' => $totalCards,
            'categories' => $categories,
            'cards' => $cards,
            'newestCards' => $newestCards,
            'mostPopularCards' => $mostPopularCards,
            'pixies' => $pixies,
            'isCardFavoritedFirstTime' => $isCardFavoritedFirstTime,
            'region_id' => $regionId,
            'slug' =>$slug,
            'regionName' =>$regionName
        ]);
    }

    public function cards(CardRepository $cardRepo)
    {

    }
    /**
     * @Route("/{slug}", name="detail")
     */
//    public function details($slug = 'france', RegionRepository $regionRepo, UserRepository $userRepo)
//    {
//        $region = $regionRepo->findOneBy(['slug' => $slug]);
//
//        if(empty($region))
//        {
//            echo "Region not found lol";exit;
//        }
//        $totalPixies = $userRepo->countPixieByRegion($region->getId());
//
//        return $this->render('front/v2/region/index.html.twig',
//            [
//                'region' => $region,
//                'totalPixies' => $totalPixies
//            ]);
//    }
}
