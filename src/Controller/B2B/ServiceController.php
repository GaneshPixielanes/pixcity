<?php

namespace App\Controller\B2B;

use App\Entity\Contact;
use App\Form\B2B\ContactType;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/prestations-de-service", name="prestations_de_service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request,RegionRepository $regionRepo,UserRepository $userRepo)
    {

        $searchParams['regions'] = null;
        $searchParams['skills'] = null;
        $searchParams['text'] = null;

        $limit = 20;

        $page = is_null($request->get('page'))?1:$request->get('page');

        $filters = [
            'regions' => $searchParams['regions'],
            'skills' => $searchParams['skills'],
            'text' => $searchParams['text'],
            'roles' => 'ROLE_CM',
            'page' => $page
        ];

        $users = $userRepo->searchClients($filters, $limit, $page);

        $regions = $regionRepo->findAll();


        return $this->render('b2b/service/index.html.twig',[
            'regions' => $regions,
            'users'   => $users
        ]);
    }
}
