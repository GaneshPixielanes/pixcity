<?php

namespace App\Controller\B2B;

use App\Entity\Contact;
use App\Entity\Page;
use App\Form\B2B\ContactType;
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
    public function index(Request $request,RegionRepository $regionRepo,UserRepository $userRepo)
    {

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

        $users = $userRepo->searchClients($filters, $limit, 1, true);

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
