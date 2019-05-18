<?php

namespace App\Controller\B2B\Client;

use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/search/", name="b2b_client_search_")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(Request $request, UserRepository $userRepo, RegionRepository $regionRepo)
    {
        $filters['roles'] = 'ROLE_CM';
        $users = $userRepo->searchUsers($filters, 1, 12);

        $regions = $regionRepo->findAll();
        return $this->render('b2b/client/search/index.html.twig', [
            'users' => $users,
            'regions' => $regions
        ]);
    }
}
