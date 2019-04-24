<?php

namespace App\Security;

use App\Constant\AfterLoginAction;
use App\Constant\SessionName;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Entity\User;

class LoginListener
{
    private $em;
    private $session;

    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {

        // Get the User entity.
        $user = $event->getAuthenticationToken()->getUser();

        if($user instanceof User){

            //----------------------------------------
            // After login actions
            //----------------------------------------

            $afterLoginAction = $this->session->get(SessionName::AFTER_LOGIN_ACTION_NAME);
            $afterLoginValue = $this->session->get(SessionName::AFTER_LOGIN_ACTION_VALUE);
            if($afterLoginAction){

                switch($afterLoginAction){
                    case AfterLoginAction::ADD_FAVORITE_PIXIE:

                        if($afterLoginValue) {
                            $pixie = $this->em->getRepository(User::class)->findOneBy(["id" => $afterLoginValue]);
                            if (!$user->hasFavoritePixie($pixie)) {
                                $user->addFavoritePixie($pixie);
                            }

                            if($this->session instanceof Session) {
                                $this->session->getFlashBag()->add(AfterLoginAction::ADD_FAVORITE_PIXIE, "Vous suivez maintenant le Pixie ".$pixie." !");
                            }
                        }

                        break;

                    case AfterLoginAction::ADD_FAVORITE_CARD:

                        if($afterLoginValue) {
                            $card = $this->em->getRepository(Card::class)->findOneBy(["id" => $afterLoginValue]);
                            if (!$user->hasFavorite($card)) {
                                $user->addFavorite($card);
                            }

                            if($this->session instanceof Session) {
                                $this->session->getFlashBag()->add(AfterLoginAction::ADD_FAVORITE_CARD, "La Card `".$card->getName()."` a bien été ajoutée à vos favoris !");
                            }
                        }

                        break;

                    case AfterLoginAction::ADD_LIKE_CARD:

                        if($afterLoginValue) {
                            $card = $this->em->getRepository(Card::class)->findOneBy(["id" => $afterLoginValue]);
                            if (!$user->hasLike($card)) {
                                $user->addLike($card);
                            }

                            if($this->session instanceof Session) {
                                $this->session->getFlashBag()->add(AfterLoginAction::ADD_LIKE_CARD, "Une mention `J'aime` a bien été ajoutée sur la Card `".$card->getName()."` !");
                            }
                        }

                        break;
                }

                $this->em->persist($user);
                $this->em->flush();

            }
        }
    }
}