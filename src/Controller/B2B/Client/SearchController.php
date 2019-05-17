<?php

namespace App\Controller\B2B\Client;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/search/", name="b2_b_client_search_")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(Request $request, UserRepository $userRepo)
    {
         $users = $userRepo->findBy(['roles' => ['ROLE_CM']]);
         dd($users);
        return $this->render('b2b/client/search_/index.html.twig', [
            'controller_name' => 'SearchControllerPhpController',
        ]);
    }
}
