<?php

namespace App\Controller\B2B\Client;

use App\Entity\Page;
use App\Repository\PackRepository;
use App\Repository\SkillRepository;
use App\Repository\UserPacksRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/freelance/", name="b2b_front_community_manager_")
 */
class CommunityManagerController extends AbstractController
{
    /**
     * @Route("community-manager-{city}/{name}/{id}", name="view")
     */
    public function index($id, UserRepository $userRepo, UserPacksRepository $packRepo,SkillRepository $skillRepository)
    {

        $user = $userRepo->find($id);
        // Check if the user exists
        if(is_null($user) && !in_array('ROLE_CM', $user->getRoles()))
        {
            return $this->redirect('/client/search');
        }

        #SEO
        $page = new Page();
        $page->setMetaTitle($user.":".$user->getUserSkill()->first()." local à ".$user->getPixie()->getBilling()->getAddress()->getCity());
        $page->setMetaDescription('Retrouvez toutes les offres de '.$user.' pour des missions de '.$user->getUserSkill()->first().' près de chez vous à '.$user->getPixie()->getBilling()->getAddress()->getCity());


        $packs = $packRepo->findBy([
            'user' => $user,
            'active' => null,
            'deleted' => null
        ]);

        $skills = $skillRepository->findAll();

        return $this->render('b2b/client/community_manager/index.html.twig', [
            'user' => $user,
            'packs' => $packs,
            'skills' => $skills,
            'page' => $page
        ]);
    }

    /**
         * @Route("{slug}/{id}",name="pack_view")
     */
    public function viewPack($id, UserPacksRepository $userPacksRepo)
    {

        $pack = $userPacksRepo->find($id);


        if(is_null($pack))
        {
            return JsonResponse::create(['success' => false, 'response' => '<strong>Pack not found </strong>']);
        }

        $session  = new Session();
        $session->set('chosen_pack_url', '/client/pack/'.$pack->getId());

        #SEO
        $page = new Page();
        $page->setMetaTitle($pack->getUser()." : ".$pack->getPackSkill()." local à ".$pack->getUser()->getPixie()->getBilling()->getAddress()->getCity());
        $page->setMetaDescription('Trouver un community manager ou influenceur local, basé près de chez vous');


        return $this->render('b2b/client/community_manager/pack_detail.html.twig',[
            'pack' => $pack,
            'page' => $page
        ]);
    }




}
