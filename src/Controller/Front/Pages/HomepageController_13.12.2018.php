<?php

namespace App\Controller\Front\Pages;

use App\Repository\CardCategoryRepository;
use App\Repository\CardRepository;
use App\Repository\PageCategoryRepository;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/", name="front_homepage_")
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
        CardCategoryRepository $categoriesRepo
    ){
        $page = $pagesRepo->findOneBySlug("accueil");

        $regions = $pagesCategoriesRepo->findAllActive();

        $pixies = $usersRepo->findRandomPixies();
        $cards = $cardsRepo->search([],1,10,'newest');
        $categories = $categoriesRepo->findAllActive();

        return $this->render('front/homepage/index.html.twig', [
            'page' => $page,
            'regions' => $regions,
            'categories' => $categories,
            'pixies' => $pixies,
            'cards' => $cards,
        ]);
    }


}