<?php

namespace App\Controller\Front\Pages;

use App\Repository\CardCategoryRepository;
use App\Repository\CardProjectRepository;
use App\Repository\CardRepository;
use App\Repository\NotificationsRepository;
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
        CardCategoryRepository $categoriesRepo,
        CardProjectRepository $cardProjectRepository,
        NotificationsRepository $notificationsRepository
    ){
        $page = $pagesRepo->findOneBySlug("accueil");
        $notifications = null;
        $regions = $pagesCategoriesRepo->findAllActive();

        $pixies = $usersRepo->findRandomPixies();
        $cards = $cardsRepo->search([],1,10,'newest');
        $categories = $categoriesRepo->findAllActive();
        $user = $this->getUser();
        if(!is_null($user))
        {
            if(!is_null($user->getPixie()))
            {
                $notifications = $notificationsRepository->search('pixie',$user->getId());
            }
            else
            {
                $notifications = $notificationsRepository->search('voyager',$user->getId());
            }
        }

        return $this->render('front/homepage/index.html.twig', [
            'page' => $page,
            'regions' => $regions,
            'categories' => $categories,
            'pixies' => $pixies,
            'cards' => $cards,
            'notifications' => $notifications
        ]);
    }


}