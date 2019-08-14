<?php

namespace App\Controller\Front\Pages;

use App\Constant\ViewMode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("user", name="front_user_")
 */

class UserController extends Controller
{

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/switch", name="switch_mode")
     */
    public function index(
        Request $request,
        AuthorizationCheckerInterface $authChecker
    ){

        $user = $this->getUser();

        if($user){

            if($user->getViewMode() === ViewMode::USER) {
                if($authChecker->isGranted('ROLE_PIXIE')) {
                    $user->setViewMode(ViewMode::PIXIE);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                }
            }
            elseif($user->getViewMode() === ViewMode::PIXIE){
                if($authChecker->isGranted('ROLE_USER')) {
                    $user->setViewMode(ViewMode::USER);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                }
            }


        }
        $this->session->set('login_by',['type' => 'login_cm','entity' => $user,'image' => $user->getAvatar()->getName(),'view_mode' => $user->getViewMode()]);
        $referer = $request->headers->get('referer');
        if ($referer == NULL) {
            return $this->redirectToRoute('front_homepage_index');
        } else {
            return $this->redirect($referer);
        }

    }



}