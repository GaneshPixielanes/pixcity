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
 * @Route("", name="front_card_")
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
        $card = $cardsRepo->findCardBySlug($request->attributes->get("slug"));

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

        return $this->render('front/card/single.html.twig', [
            'page' => $page,
            'pixies' => $pixies,
            'cards' => $cards,
            'card' => $card
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