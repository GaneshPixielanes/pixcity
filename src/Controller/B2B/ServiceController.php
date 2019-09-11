<?php

namespace App\Controller\B2B;

use App\Entity\Contact;
use App\Entity\Page;
use App\Form\B2B\ContactType;
use App\Repository\OptionRepository;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/freelance", name="prestations_de_service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(Request $request,RegionRepository $regionRepo,UserRepository $userRepo,
                          OptionRepository $optionRepository)
    {
        $testAccountsAsClient = $optionRepository->findOneBy(['slug'=>'dev-client-email']);
        $testAccountsAsCm = $optionRepository->findOneBy(['slug'=>'dev-cm-email']);
        $searchParams['regions'] = null;
        $searchParams['skills'] = null;
        $searchParams['text'] = null;

        $limit = 6;

        $page = is_null($request->get('page'))?1:$request->get('page');



        $filters = [
            'regions' => $searchParams['regions'],
            'skills' => $searchParams['skills'],
            'text' => $searchParams['text'],
            'roles' => 'ROLE_CM',
            'page' => $page
        ];
        $loggedUserSession = $this->get('session')->get('login_by');
        $loggedUser = $loggedUserSession['entity'];
        $loggedType = $loggedUserSession['type'];

        if($loggedUser){
            if(strpos($testAccountsAsClient->getValue(),$loggedUser->getEmail()) !== false){ //in
                $users = $userRepo->searchClients($filters, $limit, $page,'', $testAccountsAsCm->getValue(),$loggedType);
            }elseif(strpos($testAccountsAsCm->getValue(),$loggedUser->getEmail()) !== false){ //in
                $users = $userRepo->searchClients($filters, $limit, $page,'', $testAccountsAsCm->getValue(),$loggedType);
            }
            else{
                $users = $userRepo->searchClients($filters, $limit, $page,'', $testAccountsAsCm->getValue());
            }
        }
        else{
            $users = $userRepo->searchClients($filters, $limit, 1, true,$testAccountsAsCm->getValue());
        }


        $regions = $regionRepo->findAll();
        #SEO
        $page = new Page();
        $page->setMetaTitle('Pix.city services : trouvez votre community manager ou influenceur local freelance');
        $page->setMetaDescription('Un community manager ou influenceur local, basé près de chez vous, vous accompagne pour gérer la visibilité locale de votre commerce, enseigne et franchise.');

        return $this->render('b2b/service/index.html.twig',[
            'regions' => $regions,
            'users'   => $users,
            'page' => $page
        ]);
    }
}
