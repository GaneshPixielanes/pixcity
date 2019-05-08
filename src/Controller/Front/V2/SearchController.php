<?php

namespace App\Controller\Front\V2;

use App\Repository\CardRepository;
use App\Repository\UserRepository;
use App\Repository\CardCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Card;
use App\Constant\CardStatus;
use App\Entity\Page;
use App\Controller\Front\SearchPageController;
/**
 * @Route("/recherche/", name="front_search_")
 */
class SearchController extends SearchPageController
{
    /**
     * @Route("index", name="index")
     */
    public function index(Request $request,
    CardRepository $cardRepository,
    UserRepository $userRepo,
    CardCategoryRepository $categoryRepo)
    {
        $filters = [
                    'name' => trim($request->get("search")),
                    'text' => trim($request->get("search")),
                    'regions' => $request->get("regions"),
                    'content' => trim($request->get("search")),
					'categories' => $request->get('categories')
                    ];
         $searchParams = $this->getSearchParams($request);            
        if($searchParams["type"] === "pixies") {

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
           
        $start = $request->get('start')?$request->get('start'):0;
        $limit = $request->get('limit')?$request->get('limit'):10;

        #Get all cards w.r.t search filters
        $cards = $cardRepository->search($filters, $start, $limit, 'newest');

        #Get the cards count
        $cardCount = $cardRepository->countSearchResult($filters);
        
        $categories = $categoryRepo->findCategoriesBySearchParam($filters);
        if(!isset($page)) {
            $page = new Page();
            $page->setName("Wall");
            $page->setMetaTitle("L’avis et les bons plans de nos influenceurs - Pixies");
            $page->setMetaDescription("Les avis et bons plans de nos pixies influenceurs - Guide de voyage et tourisme");
            $page->setIndexed(false);
        }
        return $this->render('v2/front/search/index.html.twig', [
            'cards' => $cards,
            'pixies' => $userRepo->findRandomPixies(),
            'totalCards' => $cardCount,
            'categories' => $categories,
			'filters' => $filters,
            'page' => $page
        ]);
    }

    // private function _searchCards($start = 0, $limit = 10, $filters = [], $cardRepository)
    // {
    //     // return $cardRepository->search($filters, $start, $limit, 'newest');

    //            $em = $this->getDoctrine()->getManager();
    //            $result = $em->getRepository(Card::class)->createQuerybuilder('c')
    //                         ->join('c.address','a')
    //                         ->join('c.region','r')
    //                         ->andWhere('c.status = :status AND (c.name LIKE :name OR r.name LIKE :regionName OR c.content LIKE :content)')
    //                         ->setParameter('content','%'.htmlentities($filters['name']).'%');;
				// 			if(!empty($filters['regions']) && $filters['regions'] != null && $filters['categories'] != 'all')
				// 			{
				// 				$result = $result->andWhere('r.slug IN (:region)')->setParameter('region',$filters['regions']);
				// 			}
				// 			if(!empty($filters['categories']) && $filters['categories'] != null && $filters['categories'] != 'all')
				// 			{
				// 				$result = $result->leftJoin('c.categories','t')
    //                                     ->andWhere('t.slug IN (:category)')->setParameter('category',$filters['categories']);
				// 			}
    //                         $result = $result->setParameter('status',CardStatus::VALIDATED)
    //                         ->andWhere('c.deleted = :isDeleted')->setParameter('isDeleted',0)
    //                         ->setParameter('name','%'.$filters['name'].'%')
    //                         ->setParameter('regionName','%'.$filters['name'].'%')
				// 			->setMaxResults($limit)
    //                         ->getQuery()
    //                         ->getResult();
    //            return $result;
    // }

    /**
     * @Route("/city-maker/{cityMaker}/{region}", name="city_maker_region", defaults={"cityMaker"=null, "region"=null})
     */
    public function searchCityMakerRegionCards(Request $request,
    CardRepository $cardRepository,
    UserRepository $userRepo,
    CardCategoryRepository $categoryRepo)
    {
        $filters = [
                    'regions' => $request->get("region"),
                    'cityMaker' => $request->get("cityMaker")
                    ];
        $start = $request->get('start');
        $limit = $request->get('limit');

        $em = $this->getDoctrine()->getManager();
        $cards = $em->getRepository(Card::class)->createQuerybuilder('c')
                     ->join('c.address','a')
                     ->join('c.categories','t')
                     ->join('c.region','r')
                     ->join('c.pixie','u')
                     ->andWhere('c.status = :status AND r.id = :region')
                     ->andWhere('u.id = :user')
                     ->setParameter('status',CardStatus::VALIDATED)
                     ->setParameter('region',$request->get("region"))
                     ->setParameter('user',$request->get("cityMaker"))
                     ->getQuery()
                     ->getResult();
                     // dd($cards);
        $cardCount = count($cards);


        $categories = $categoryRepo->findCategoriesBySearchParam($request->get("search"));
        return $this->render('v2/front/search/index.html.twig', [
            'cards' => $cards,
            'pixies' => $userRepo->findRandomPixies(),
            'totalCards' => $cardCount,
            'categories' => $categories
        ]);
    }
}
