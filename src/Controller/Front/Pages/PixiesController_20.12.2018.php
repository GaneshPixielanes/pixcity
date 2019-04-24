<?php

namespace App\Controller\Front\Pages;

use App\Controller\Front\SearchPageController;
use App\Entity\Page;
use App\Repository\CardRepository;
use App\Repository\PageCategoryRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("", name="front_pixies_")
 */

class PixiesController extends SearchPageController
{
    private $regions = [];

    private function _groupPixiesByRegions($pixies){
        $this->regions = [];
        foreach($pixies as $user){

            $userRegion = $user->getPixie()->getRegions();

            $found = false;
            foreach($this->regions as &$region){
                if($userRegion[0]->getId() === $region['infos']->getId()){
                    $region['pixies'][] = $user;
                    $found = true;
                }
            }

            if(!$found){
                $this->regions[] = [
                    'infos' => $userRegion[0],
                    'pixies' => [$user]
                ];
            }

        }
    }

    /**
     * @Route("/tous-nos-pixies-locaux-{slug}", defaults={"slug"=null}, name="index")
     */
    public function index(
        Request $request,
        UserRepository $usersRepo,
        PageCategoryRepository $pagesCategoryRepo
    ){
        $searchParams = $this->getSearchParams($request);

        $pageCategory = null;

        if($request->attributes->get("slug") && $request->attributes->get("slug") !== "france") {
            $filters = [
                "regions" => [$request->attributes->get("slug")],
                "categories" => $searchParams["categories"],
                "text" => $searchParams["text"]
            ];

            $pixies = $usersRepo->searchPixies($filters);
            $pageCategory = $pagesCategoryRepo->findOneBySlug($request->attributes->get("slug"));
        }
        else{
            $filters = [
                "regions" => $searchParams["regions"],
                "categories" => $searchParams["categories"],
                "text" => $searchParams["text"]
            ];

            $pixies = $usersRepo->searchPixies($filters);
        }

        $this->_groupPixiesByRegions($pixies);


        //--------------------------------------------
        // Default page

        if(!isset($page)) {
            $page = new Page();
            $page->setName("Pixies");

            if($pageCategory){
                $page->setMetaTitle("Retrouvez la liste de tous nos Pixies locaux en ".$pageCategory->getName()." découvrez leurs meilleures adresses et photos");
            }
            else{
                $page->setMetaTitle("Retrouvez la liste de tous nos Pixies locaux - découvrez leurs meilleures adresses et photos");
            }

            $page->setMetaDescription("Liste des Pixies - influenceurs locaux par région. Accédez à leurs avis région par région, leurs bons plans près de chez eux: bars, restaurants, sorties, vie nocturne, musées, sport, sorties en famille, sorties avec enfant...");
            $page->setIndexed(true);
        }


        return $this->render('front/pixies/index.html.twig', [
            'page' => $page,
            'filters' => $searchParams,
            'pixies' => $pixies,
            'regions' => $this->regions,
            'pageCategory' => $pageCategory
        ]);

    }

    /**
     * @Route("/profil-pixie-local/{slug}/{id}", name="single")
     */
    public function single(
        Request $request,
        UserRepository $usersRepo,
        PageCategoryRepository $pagesCategoriesRepo,
        CardRepository $cardsRepo
    )
    {

        $user = $usersRepo->searchUserById($request->attributes->get("id"));
        if(!$user->getActive())
        {
            $this->addFlash('error','Sorry, pixie not found!');
            return $this->redirect('/tous-nos-pixies-locaux-france');
        }

        $pixies = $usersRepo->findRandomPixies();
        $cards = $cardsRepo->search(["pixie"=> $user->getId()], 1, 100);
        $totalLikes = $cardsRepo->findPixieCardsLikes($user->getId());
        if(empty($totalLikes)) $totalLikes = 0;

        $regions = $pagesCategoriesRepo->findAllActive($user->getPixie()->getRegions());
        $pixieRegions = $user->getPixie()->getRegions();

        $page = new Page();
        $page->setName($user);

        $metaTitle = $user;
        if(count($pixieRegions) > 0) {
            $metaTitle .= ", Pixie local originaire de ".$pixieRegions[0]->getName();
        }
        $metaTitle .= ". Retrouvez ses photos et avis. Pix.City - guide de voyage local";

        $page->setMetaTitle($metaTitle);
        $page->setMetaDescription(strip_tags($user->getPixie()->getLikeText()));
        $page->setIndexed(true);

        return $this->render('front/pixies/single.html.twig', [
            'page' => $page,
            'user' => $user,
            'pixies' => $pixies,
            'regions' => $regions,
            'cards' => $cards,
            'totalLikes' => $totalLikes
        ]);
    }


}