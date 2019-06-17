<?php

namespace App\Controller\B2B\Client;

use App\Repository\PackRepository;
use App\Repository\SkillRepository;
use App\Repository\UserPacksRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/profil-community-manager/", name="b2b_front_community_manager_")
 */
class CommunityManagerController extends AbstractController
{
    /**
     * @Route("{name}/{id}", name="view")
     */
    public function index($id, UserRepository $userRepo, UserPacksRepository $packRepo,SkillRepository $skillRepository)
    {
        $user = $userRepo->find($id);
        // Check if the user exists
        if(is_null($user) && !in_array('ROLE_CM', $user->getRoles()))
        {
            return $this->redirect('/client/search');
        }

        $packs = $packRepo->findBy([
            'user' => $user,
            'active' => null,
            'deleted' => null
        ]);

        $skills = $skillRepository->findAll();

        return $this->render('b2b/client/community_manager/index.html.twig', [
            'user' => $user,
            'packs' => $packs,
            'skills' => $skills
        ]);
    }
}
