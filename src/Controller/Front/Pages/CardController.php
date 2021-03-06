<?php

namespace App\Controller\Front\Pages;

use App\Entity\Card;
use App\Entity\Page;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\JpegResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v2", name="front_card_v2_")
 */

class CardController extends Controller
{

    /**
     * @Route("/{slug}.html", name="single")
     */
     public function single(
        Request $request,
        UserRepository $usersRepo,
        CardRepository $cardsRepo
    )
    {
        $slug = $request->attributes->get("slug");
        if(strpos($slug,'-cards'))
        {
            $isCard = true;
            $slug = str_replace('-cards','',$slug);
        }
        else
        {
            $isCard = false;
        }

        $isCardFavoritedFirstTime = null;
        $card = $cardsRepo->findCardBySlug($slug);

        if(!$card) throw new NotFoundHttpException('error.not_found');

        $pixies = $usersRepo->findRandomPixies();
        $cards = $cardsRepo->search(["pixie"=> $card->getPixie()->getId()]);

        $page = new Page();
        $page->setName($card->getName());

        $metaTitle = $card->getName();
        if($card->getAddress()) {
            $metaTitle .= " - ".$card->getAddress()->getCity();
        }
        $metaTitle .= " - L’avis d’un influenceur de ".$card->getRegion()->getName();
        $metaTitle .= " - ".$card->getPixie();

        $page->setMetaTitle(!empty($card->getMetaTitle())?$card->getMetaTitle():$metaTitle);
        $page->setMetaDescription(!empty($card->getMetaDescription())?$card->getMetaDescription():substr(strip_tags($card->getContent()), 0, 250));
        $page->setIndexed($card->getIndexed());

        $user = $this->getUser();
        if(!is_null($user))
        {
            $filters = ["userFavorite"=>$user->getId()];

            if(count($cardsRepo->search($filters)) == 0) {
                $isCardFavoritedFirstTime = true;
            }
        }
        //If the card is an interview, provide a seperate URL for card with images
        $cardSlug = $card->getSlug().'-cards';
        return $this->render('front/card/single.html.twig', [
            'page' => $page,
            'pixies' => $pixies,
            'cards' => $cards,
            'card' => $card,
            'isCardFavoritedFirstTime' => $isCardFavoritedFirstTime,
            'cardSlug' => $cardSlug,
            'isCard' => $isCard
        ]);
    }

    /**
     * @Route("/load-cards",name="load_cards");
     */
    public function loadCards(Request $request,CardRepository $cardRepository)
    {
        $cards = $cardRepository->search([],$request->get('page'),20,'newest');

        return $this->render('front/homepage/cards.html.twig', [
            'cards' => $cards,
            'count' => count($cards)
        ]);
    }//End of loadCards


}