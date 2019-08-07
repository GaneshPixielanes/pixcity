<?php

namespace App\Controller\B2B\Client;

use App\Controller\Front\SearchPageController;
use App\Entity\Page;
use App\Form\B2B\CitymakerSearchType;
use App\Repository\RegionRepository;
use App\Repository\SkillRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/freelance/search", name="b2b_client_search_")
 */
class SearchController extends SearchPageController
{
    /**
     * @Route("", name="index")
     */
    public function index(Request $request, UserRepository $userRepo, RegionRepository $regionRepo, SkillRepository $skillRepo)
    {
        $searchParams = $this->getSearchParams($request);
        $limit = 12;
        $page = is_null($request->get('page'))?1:$request->get('page');

        if($searchParams['text']!="")
        {
            $filters = [
                'regions' => $searchParams['regions'],
                'skills' => $searchParams['skills'],
                'text' => $searchParams['text'],
//                'roles' => 'ROLE_CM',
                'page' => $page
            ];
        }
        else
        {
           $filters = [
                'regions' => $searchParams['regions'],
                'skills' => $searchParams['skills'],
                //'text' => $searchParams['text'],
//                'roles' => 'ROLE_CM',
                'page' => $page
            ];
        }

        $users = $userRepo->searchClients($filters, $limit, $page);

        $filters['cm_count'] = $userRepo->searchCommunityManagerCount($filters, $limit, $page);
        $filters['total_pages'] = ceil($filters['cm_count']/$limit);

        $regions = $regionRepo->findAll();
        $skills = $skillRepo->findAll();

        #SEO
        $page = new Page();
        $page->setMetaTitle('Pix.city Services : Recherchez votre community manager ou influenceur local freelance');
        $page->setMetaDescription('Trouver un community manager ou influenceur local, basé près de chez vous');

        return $this->render('b2b/client/search/new_search.html.twig', [
            'users' => $users,
            'regions' => $regions,
            'skills' => $skills,
            'filters' => $filters,
            'page' => $page
        ]);
    }
}
