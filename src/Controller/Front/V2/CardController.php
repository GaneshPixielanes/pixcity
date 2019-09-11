<?php

namespace App\Controller\Front\V2;

use App\Entity\Card;
use App\Entity\Page;
use App\Repository\CardCategoryRepository;
use App\Repository\CardRepository;
use App\Repository\OptionRepository;
use App\Repository\UserRepository;
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
        CardRepository $cardsRepo,
        CardCategoryRepository $cardCategoryRepo
    )
    {
        $isCardFavoritedFirstTime = null;
        $card = $cardsRepo->findCardBySlug($request->attributes->get("slug"));
        $nextCard = $cardsRepo->findNextCard($card);
        $prevCard = $cardsRepo->findPrevCard($card);

        if(!$card) throw new NotFoundHttpException('error.not_found');

        $pixies = $usersRepo->findRandomPixies();
        $cards = $cardsRepo->search(["pixie"=> $card->getPixie()->getId()]);

        $totalLikes = $cardsRepo->findPixieCardsLikes($card->getPixie()->getId());
        if(empty($totalLikes)) $totalLikes = 0;

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


        foreach($cardCategoryRepo->findAllActiveWithCardsDesc() as $cardCategoryWithCount)
        {
            $categories[$cardCategoryWithCount[0]->getSlug()] = $cardCategoryWithCount[1];
        }

        return $this->render('v2/front/card/index.html.twig', [
            'page' => $page,
            'pixies' => $pixies,
            'cards' => $cards,
            'card' => $card,
            'isCardFavoritedFirstTime' => $isCardFavoritedFirstTime,
            'totalLikes' => $totalLikes,
            'nextCard' => $nextCard,
            'prevCard' => $prevCard,
            'categoryList' => $categories
        ]);
    }

    /**
     * @Route("/load-cards",name="load_cards");
     */
    public function loadCards(Request $request,CardRepository $cardRepository,OptionRepository $optionRepository)
    {
        $testAccounts = $optionRepository->findOneBy(['slug'=>'dev-cm-email']);
			// echo $request->get('categories');exit;
        $filters = [];

        if(null != $request->get('categories'))
        {
            if(true == in_array(0,json_decode($request->get('categories'),true)))
            {
                unset($filters['categories']);
            }
            else {
                $filters['categories'] = json_decode($request->get('categories'),true);
            }
        }
        $filters['text'] = $request->get('text');
        $filters['regions'] = json_decode($request->get('regions'), true);
        if(!is_null($request->get('cityMaker')))
        {
            $filters['pixie'] = $request->get('cityMaker');
        }
        $loggedUser = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if($loggedUser){
            if(strpos($testAccounts->getValue(),$loggedUser->getEmail()) !== false){ //in
                $cards = $cardRepository->search($filters, $request->get('page'), 10, 'newest');
            }
            else{
                $cards = $cardRepository->search($filters, $request->get('page'), 10, 'newest',$testAccounts->getValue());
            }
        }
        else{
            $cards = $cardRepository->search($filters, $request->get('page'), 10, 'newest',$testAccounts->getValue());
        }

      return $this->render('v2/_shared/cards.html.twig', [
            'cards' => $cards,
            'count' => count($cards),
            'filters' => $filters,
            'isEditable' => $request->get('isEditable'),
            'cmFlag' => $request->get('cmFlag')
        ]);
    }//End of loadCards

    /**
     * @Route("/load-card/{id}",name="load_single_card")
     */
    public function loadCard($id, CardRepository $cardRepository)
    {
        $card = $cardRepository->find($id);
        return $this->render('v2/_shared/card_single.html.twig',[
            'card' => $card
        ]);
    }

    /**
     * @Route("/load-map-card/{id}",name="load_map_card")
     */
    public function loadMapCard($id, CardRepository $cardRepo, UserRepository $userRepo)
    {
        $cards = $cardRepo->findBy(['pixie' => $userRepo->find($id)]);

        return $this->render('v2/_shared/map-sidebar-footer.html.twig',[
           'pixieCards' => $cards
        ]);
    }

    /**
     * @Route("/load-region-cards",name="load_more-cards");
     */
    public function loadRegionCards(Request $request,CardRepository $cardRepository)
    {
        $cards = $cardRepository->search([],$request->get('page'),20,'newest');

        return $this->render('front/homepage/cards.html.twig', [
            'cards' => $cards,
            'count' => count($cards)
        ]);
    }//End of loadCards
}
