<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Entity\Page;
use App\Repository\NotificationsRepository;
use App\Repository\OptionRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserPacksRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/city-maker/profil", name="b2b_community_manager_")
 * @Security("has_role('ROLE_CM')")
 */
class CityMakerController extends AbstractController{

    /**
     * @Route("", name="index")
     */
    public function index(NotificationsRepository $notificationsRepository,
                          UserPacksRepository $packRepo,
                          UserMissionRepository $missionRepo,
                          OptionRepository $optionRepo,
                            UserRepository $userRepo)
    {

        #Logged in User
        $user = $this->getUser();


        if($user->getB2bCmApproval() != 1)
        {
            return $this->redirectToRoute('front_homepage_index');
        }
        #Unread Notifications of the user
        $notifications = $notificationsRepository->findBy(['user'=>$this->getUser(), 'unread' => 1],['id' => 'DESC']);

        #Packs listed by the user
        $packs = $packRepo->findByUser($user);

        #Missions listed by the user
        $missions = $missionRepo->findBy(['user' => $this->getUser()],['id' => 'DESC']);

        #SEO
        $page = new Page();
        $page->setMetaTitle('Pix.city Services : profil city-maker');
        $page->setMetaDescription('Retrouvez dans cet espace votre profil city-maker');

        return $this->render('b2b/index.html.twig',[
            'notifications' => $notifications,
            'packs' => $packs,
            'missions' => $missions,
            'tax' =>  $optionRepo->findBy(['slug' => 'margin'])[0],
            'page' => $page
        ]);
    }

}