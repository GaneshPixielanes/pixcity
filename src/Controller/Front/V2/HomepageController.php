<?php

namespace App\Controller\Front\V2;

use App\Constant\CardStatus;
use App\Entity\Card;
use App\Repository\CardCategoryRepository;
use App\Repository\CardProjectRepository;
use App\Repository\CardRepository;
use App\Repository\PageCategoryRepository;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * @Route("/", name="front_homepage_")
*/
class HomepageController extends Controller
{

    /**
     * @Route("", name="index")
    */
    public function homepage(        Request $request,
                                     PageRepository $pagesRepo,
                                     PageCategoryRepository $pagesCategoriesRepo,
                                     UserRepository $usersRepo,
                                     CardRepository $cardsRepo,
                                     CardCategoryRepository $categoriesRepo,
                                     CardProjectRepository $cardProjectRepository
    ){
//        YEah! commit is here , click it DO you want me to commit it? yes Ok!
        $page = $pagesRepo->findOneBySlug("accueil");
        $regions = $pagesCategoriesRepo->findAllActive();
        $coordinates = [];
        $center = [];
        // $coordinates = '';
        $noOfCards = [];
        foreach($regions as $region)
        {
            $coordinates []= trim($region[0]->getRegion()->getCoordinates());
            $noOfCards[] = $region["totalCards"];


        }
        // $coordinates = str_replace('],[',',',$coordinates);
        // $coordinates = rtrim($coordinates,",");
        $pixies = $usersRepo->findRandomPixies();
        $cards = $cardsRepo->search([],1,10,'newest');
        $popularCards = $cardsRepo->search([],1,    6,'popular');
        $categories = $categoriesRepo->findAllActiveWithCards();
        $user = $this->getUser();
        $isCardFavoritedFirstTime = false;

        if(!is_null($user))
        {
            $filters = ["userFavorite"=>$user->getId()];

            if(count($cardsRepo->search($filters)) == 0) {
                $isCardFavoritedFirstTime = true;
            }
        }

        // Get the count of cards
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(Card::class)->createQuerybuilder('c')
            ->select(["count(c.id) as card_count, r.id, r.slug"])
            ->join("c.region","r")
            ->where('c.status = :status')
            ->setParameter("status",CardStatus::VALIDATED)
            ->groupBy("r.id")
            ->getQuery()
            ->getResult();
        $cardPerRegion = array_combine(array_column($result,'slug'),array_column($result,'card_count'));
        $country = @unserialize(file_get_contents('http://ip-api.com/php/'.$_SERVER['REMOTE_ADDR']));
        
        return $this->render('v2/front/homepage/new.html.twig', [
            'page' => $page,
            'regions' => $regions,
            'categories' => $categories,
            'pixies' => $pixies,
            'cards' => $cards,
            'popularCards' => $popularCards,
            'isCardFavoritedFirstTime' => $isCardFavoritedFirstTime,
            'coordinates' => str_replace(['\r','\n'],'',str_replace('"',"",json_encode($coordinates))),
            'cardsPerRegion' => $cardPerRegion,
            'country' => 'FR'
//            'country' => $country['countryCode']
        ]);
    }

    /**
     * @Route("pixie/{slug1}/{slug2}",name="pixie_redirect");
     */

    public function redirectPixieToCityMaker(Request $request)
    {
        return $this->redirect('/city-maker/'.$request->attributes->get('slug1').'/'.$request->attributes->get('slug2').'/');
    }


    /**
     * @Route("pixie/{slug1}/{slug2}/{slug3}",name="pixie_redirect_2");
     */

    public function redirectPixieToCityMaker2(Request $request)
    {
        return $this->redirect('/city-maker/'.$request->attributes->get('slug1').'/'.$request->attributes->get('slug2').'/'.$request->attributes->get('slug3').'/');
    }

    private function clearCookies()
    {
         $response = new Response();
         $response->headers->clearCookie('intercom-session-iswx8vq4');
         return $response->send();
    }

    /**
     * @Route("/add-cm-to-avatar",name="add_cm_to_avatar")
     */
    public function addCmToAvatar(UserRepository $userRepo)
    {
        $user = $userRepo->findBy(['active' => 1]);
        $entityManager = $this->getDoctrine()->getManager();
        foreach($user as $usr)
        {
            if(!is_null($usr->getAvatar()))
            {
                if(is_null($usr->getAvatar()->getUser()))
                {
                    $usr->getAvatar()->setUser($usr);
                    $entityManager->persist($usr);
                    $entityManager->flush();

                }
            }
        }
        dd($user);
    }
}
