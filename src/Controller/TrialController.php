<?php

namespace App\Controller;

use App\Constant\CardStatus;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trial", name="trialpage_")
 */
class TrialController extends AbstractController
{
    /**
     * @Route("/new-session", name="trial")
     */
    public function index()
    {
        $pdo = new \PDO();
        $sessionStorage = new NativeSessionStorage(array(),new PdoSessionHandler($pdo));
        $session = new Session($sessionStorage);

        dd($session->getMetadataBag()->getLifetime());
        return $this->render('trial/index.html.twig', [
            'controller_name' => 'TrialController',
        ]);
    }

    /**
     * @Route("/maps-test", name="trial-map")
     */
    public function maps()
    {
        return $this->render('Front/Trial/maps.html.twig');
    }

    /**
     * @Route("/map-profile/{id}", name="map_profile")
     */
    public function profile($id, CardRepository $cardsRepo, UserRepository $userRepo)
    {
        $user = $userRepo->findOneBy(['id' => $id]);
        $cards = $cardsRepo->findBy([
            'pixie' => $user,
            'status' => CardStatus::VALIDATED
            ]);

        $totalLikes = $cardsRepo->findPixieCardsLikes($user->getId());
        $pixieRegions = $user->getPixie()->getRegions();
        if(empty($totalLikes)) $totalLikes = 0;

        return $this->render('Front/Trial/map-profile.html.twig',[
            'cards' => $cards,
            'user' => $user,
            'totalLikes' => $totalLikes,
            'regions' => $pixieRegions
        ]);
    }
    /**
     * @Route("/region/{slug}", name="region")
     */
    public function cards($slug)
    {

    }

}
