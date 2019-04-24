<?php

namespace App\Controller\Api;

use App\Controller\Front\SearchPageController;
use App\Repository\CardRepository;
use App\Utils\Pagination;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/cards", name="api_cards_")
 */

class CardsController extends SearchPageController
{

    private $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(array(new ObjectNormalizer()), array(new JsonEncoder()));
    }


    /**
     * Search card by name
     * @Route("/search", name="search")
     * @Method({"GET"})
     */
    public function search(Request $request, CardRepository $cards)
    {
        $query = $cards->createQueryBuilder("c");
        $query = $query
            ->select(["c", "r", "t"])
            ->innerJoin('c.region', 'r')
            ->innerJoin('c.thumb', 't')
            ->setMaxResults(10)
        ;

        if($request->query->get('q')){
            $query = $query->andWhere('c.name LIKE :search')->setParameter('search', "%".$request->query->get('q')."%");
        }

        $results = $query->getQuery()->getResult();

        foreach($results as $card){
            $json["results"][] = [
                "id" => $card->getId(),
                "text" => $card->getName(),
                "region" => $card->getRegion()->getName(),
                "thumb" => $card->getThumb()->getUrl(),
            ];
        }

        return new JsonResponse($json);
    }


    /**
     * Search cards paginated
     * @Route("/list", name="list")
     * @Method({"GET", "POST"})
     */
    public function list(Request $request, CardRepository $cardsRepo)
    {
        $selectable = $request->request->get("selectable", false);

        $pagination = new Pagination(
            $request->request->has("page")?intval($request->request->get('page')):1,
            $request->request->has("limit")?intval($request->request->get('limit')):10
        );

        $searchParams = $this->getSearchParams($request);
        $filters = [
            "regions" => $searchParams["regions"],
            "categories" => $searchParams["categories"],
            "pixie" => $searchParams["pixie"],
            "users" => $searchParams["users"],
            "userFavorite" => $searchParams["userFavorite"]
        ];
        if( !is_null($request->get('search')))
        {
            $filters["name"] = $request->get("search");
        }

        $cards = $cardsRepo->search($filters, $pagination->getIndex(), $pagination->getLimit(), $searchParams["orderby"]);
        $totalCards = $cardsRepo->countSearchResult($filters);
        $pagination->setTotalItems($totalCards);

        $responseContent = [
            "html" => $this->render('front/_shared/cards-list.html.twig', ['cards' => $cards, "selectable" => $selectable])->getContent(),
            "totalItems" => $pagination->getTotalItems(),
            "totalPages" => $pagination->getTotalPages(),
            "datas" => json_decode($this->serializer->serialize($cards, 'json', [
                'attributes' => [
                    'id',
                    'name',
                    'slug',
                    'region' => [
                        'id', 'slug', 'name'
                    ],
                    'categories' => [
                        'id', 'slug', 'name'
                    ],
                    'pixie' => [
                        'id', 'firstname', 'lastname'
                    ],
                    'address' => [
                        'latitude', 'longitude'
                    ]
                ]
            ]))
        ];


        return new JsonResponse($responseContent);
    }

    /**
     * Search cards paginated
     * @Route("/list-region/{slug}", name="list_region")
     * @Method({"GET", "POST"})
     */
    public function regionList($slug,Request $request, CardRepository $cardsRepo)
    {
        $selectable = $request->request->get("selectable", false);

        $pagination = new Pagination(
            $request->request->has("page")?intval($request->request->get('page')):1,
            $request->request->has("limit")?intval($request->request->get('limit')):10
        );

        $searchParams = $this->getSearchParams($request);
        $filters = [
            "regions" => $slug,
            "categories" => $searchParams["categories"],
        ];
        if( !is_null($request->get('search')))
        {
            $filters["name"] = $request->get("search");
        }

        $cards = $cardsRepo->search($filters, $pagination->getIndex(), $pagination->getLimit(), $searchParams["orderby"]);
        $totalCards = $cardsRepo->countSearchResult($filters);
        $pagination->setTotalItems($totalCards);

        $responseContent = [
            "html" => $this->render('v2/_shared/cards.html.twig', ['cards' => $cards,
            'count' => count($cards), "selectable" => $selectable])->getContent(),
            "totalItems" => $pagination->getTotalItems(),
            "totalPages" => $pagination->getTotalPages(),
            "datas" => json_decode($this->serializer->serialize($cards, 'json', [
                'attributes' => [
                    'id',
                    'name',
                    'slug',
                    'region' => [
                        'id', 'slug', 'name'
                    ],
                    'categories' => [
                        'id', 'slug', 'name'
                    ],
                    'pixie' => [
                        'id', 'firstname', 'lastname'
                    ],
                    'address' => [
                        'latitude', 'longitude'
                    ]
                ]
            ]))
        ];


        return new JsonResponse($responseContent);
    }

    /**
     * Search cards paginated
     * @Route("/v2/list", name="v2_list")
     * @Method({"GET", "POST"})
     */
    public function listCards(Request $request, CardRepository $cardsRepo)
    {
        $selectable = $request->request->get("selectable", false);

        $pagination = new Pagination(
            $request->request->has("page")?intval($request->request->get('page')):1,
            $request->request->has("limit")?intval($request->request->get('limit')):10
        );

        $searchParams = $this->getSearchParams($request);
        $filters = [
            "regions" => $searchParams["regions"],
            "categories" => $searchParams["categories"],
            "pixie" => $searchParams["pixie"],
            "users" => $searchParams["users"],
            "userFavorite" => $searchParams["userFavorite"]
        ];
        if( !is_null($request->get('search')))
        {
            $filters["name"] = $request->get("search");
        }

        $cards = $cardsRepo->search($filters, $pagination->getIndex(), $pagination->getLimit(), $searchParams["orderby"]);
        $totalCards = $cardsRepo->countSearchResult($filters);
        $pagination->setTotalItems($totalCards);

        $responseContent = [
            "html" => $this->render('v2/_shared/cards.html.twig', ['cards' => $cards, "selectable" => $selectable])->getContent(),
            "totalItems" => $pagination->getTotalItems(),
            "totalPages" => $pagination->getTotalPages(),
            "datas" => json_decode($this->serializer->serialize($cards, 'json', [
                'attributes' => [
                    'id',
                    'name',
                    'slug',
                    'region' => [
                        'id', 'slug', 'name'
                    ],
                    'categories' => [
                        'id', 'slug', 'name'
                    ],
                    'pixie' => [
                        'id', 'firstname', 'lastname'
                    ],
                    'address' => [
                        'latitude', 'longitude'
                    ]
                ]
            ]))
        ];


        return new JsonResponse($responseContent);
    }

    /**
     * @Route("/images/{id}",name="images")
     */
    public function cardImages($id, CardRepository $cardRepo)
    {
        $card = $cardRepo->find($id);
        $responseContent = [];
        foreach($card->getMedias() as $image)
        {
            if(file_exists('./media/cache/card_gallery_image'.$image->getUrl()))
            {
                $images['src'] = '/media/cache/card_gallery_image'.$image->getUrl();
                $images['thumb'] = '/media/cache/card_gallery_image'.$image->getUrl();
            }
            else
            {
                $images['src'] = $image->getUrl();
                $images['thumb'] = $image->getUrl();
            }

            
            $images['subHtml'] = explode('/',$card->getPixie()->getInstagram())[3];

            $responseContent[] = $images;
        }
        return new JsonResponse($responseContent);
    }
}