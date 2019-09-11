<?php

namespace App\Controller\B2B\Client;

use App\Controller\Front\SearchPageController;
use App\Entity\Page;
use App\Form\B2B\CitymakerSearchType;
use App\Repository\OptionRepository;
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
    public function index(Request $request, UserRepository $userRepo, RegionRepository $regionRepo,
                          SkillRepository $skillRepo,
                          OptionRepository $optionRepository
    )
    {
        $testAccountsAsClient = $optionRepository->findOneBy(['slug'=>'dev-client-email']);
        $testAccountsAsCm = $optionRepository->findOneBy(['slug'=>'dev-cm-email']);
        $searchParams = $this->getSearchParams($request);
        $limit = 12;
        $page = is_null($request->get('page'))?1:(int)$request->get('page');

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
        //$loggedUser = $this->getUser();
        $loggedUserSession = $this->get('session')->get('login_by');
        $loggedUser = $loggedUserSession['entity'];
        $loggedType = $loggedUserSession['type'];

        if($loggedUser){
            if(strpos($testAccountsAsClient->getValue(),$loggedUser->getEmail()) !== false){ //in
                $users = $userRepo->searchClients($filters, $limit, $page,'', $testAccountsAsCm->getValue(),$loggedType);
                $filters['cm_count'] = $userRepo->searchCommunityManagerCount($filters, $limit, $page,$testAccountsAsCm->getValue(),$loggedType);
            }elseif(strpos($testAccountsAsCm->getValue(),$loggedUser->getEmail()) !== false){ //in
                $users = $userRepo->searchClients($filters, $limit, $page,'', $testAccountsAsCm->getValue(),$loggedType);
                $filters['cm_count'] = $userRepo->searchCommunityManagerCount($filters, $limit, $page,$testAccountsAsCm->getValue(),$loggedType);
            }
            else{
                $users = $userRepo->searchClients($filters, $limit, $page,'', $testAccountsAsCm->getValue());
                $filters['cm_count'] = $userRepo->searchCommunityManagerCount($filters, $limit, $page,$testAccountsAsCm->getValue());
            }
        }
        else{

            $users = $userRepo->searchClients($filters, $limit, $page,'', $testAccountsAsCm->getValue());

            $filters['cm_count'] = $userRepo->searchCommunityManagerCount($filters, $limit, $page,$testAccountsAsCm->getValue());
        }


        $filters['total_pages'] = ceil($filters['cm_count']/$limit);

        $regions = $regionRepo->findAll();
        $skills = $skillRepo->findAll();

        $text = $searchParams['text'];

        #SEO
        $page = new Page();
        $page->setMetaTitle('Pix.city Services : Recherchez votre community manager ou influenceur local freelance');
        $page->setMetaDescription('Trouver un community manager ou influenceur local, basé près de chez vous');

        return $this->render('b2b/client/search/new_search.html.twig', [
            'users' => $users,
            'regions' => $regions,
            'skills' => $skills,
            'filters' => $filters,
            'page' => $page,
            'text' => $text
        ]);
    }
}
