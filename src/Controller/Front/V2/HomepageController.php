<?php

namespace App\Controller\Front\V2;

use App\Constant\CardStatus;
use App\Entity\Card;
use App\Repository\CardCategoryRepository;
use App\Repository\CardRepository;
use App\Repository\ClientRepository;
use App\Repository\OptionRepository;
use App\Repository\PageCategoryRepository;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @Route("/", name="front_homepage_")
*/
class HomepageController extends Controller
{

    /**
     * @Route("", name="index")
    */
    public function homepage(
                                     PageRepository $pagesRepo,
                                     PageCategoryRepository $pagesCategoriesRepo,
                                     UserRepository $usersRepo,
                                     CardRepository $cardsRepo,
                                     CardCategoryRepository $categoriesRepo,
                                     OptionRepository $optionRepository
    ){
        $testAccounts = $optionRepository->findOneBy(['slug'=>'dev-cm-email']);
        $testClientAccounts = $optionRepository->findOneBy(['slug'=>'dev-client-email']);
        //dd($testAccounts->getValue());
       // $loggedUser = $this->getUser();
        $loggedUserSession = $this->get('session')->get('login_by');
        $loggedUser = $loggedUserSession['entity'];
        $em = $this->getDoctrine()->getManager();
        if($loggedUser){
            if(strpos($testAccounts->getValue(),$loggedUser->getEmail()) !== false || strpos($testClientAccounts->getValue(),$loggedUser->getEmail()) !== false){ //in
                $regions = $pagesCategoriesRepo->findAllActive([],$testAccounts->getValue());
                // Get the count of cards

                $result = $em->getRepository(Card::class)->createQuerybuilder('c')
                    ->select(["count(c.id) as card_count, r.id, r.slug"])
                    ->join("c.region","r")
                    ->leftJoin("c.pixie","uid")
                    ->where('c.status = :status')
//                    ->andWhere("uid.email IN ('ganesh@pix.city','bsingh@pix.cityy')")
                    ->setParameter("status",CardStatus::VALIDATED)
                    ->groupBy("r.id")
                    ->getQuery()
                    ->getResult();

                $cards = $cardsRepo->search([],1,10,'newest');
                $popularCards = $cardsRepo->search([],1,    6,'popular');
                $categories = $categoriesRepo->findAllActiveWithCards();
                $pixies = $usersRepo->findRandomPixies();
            }
            else{
                $regions = $pagesCategoriesRepo->findAllActive();
                $result = $em->getRepository(Card::class)->createQuerybuilder('c')
                    ->select(["count(c.id) as card_count, r.id, r.slug"])
                    ->join("c.region","r")
                    ->leftJoin("c.pixie","uid")
                    ->where('c.status = :status')
                    ->andWhere("uid.email NOT IN (".$testAccounts->getValue().")")
                    ->setParameter("status",CardStatus::VALIDATED)
                    ->groupBy("r.id")
                    ->getQuery()
                    ->getResult();
                $cards = $cardsRepo->search([],1,10,'newest',$testAccounts->getValue());
                $popularCards = $cardsRepo->search([],1,    6,'popular',$testAccounts->getValue());
                $categories = $categoriesRepo->findAllActiveWithCards($testAccounts->getValue());
                $pixies = $usersRepo->findRandomPixies('',$testAccounts->getValue());
            }
        }else{
            $regions = $pagesCategoriesRepo->findAllActive();
            $result = $em->getRepository(Card::class)->createQuerybuilder('c')
                ->select(["count(c.id) as card_count, r.id, r.slug"])
                ->join("c.region","r")
                ->leftJoin("c.pixie","uid")
                ->where('c.status = :status')
                ->andWhere("uid.email NOT IN (".$testAccounts->getValue().")")
                ->setParameter("status",CardStatus::VALIDATED)
                ->groupBy("r.id")
                ->getQuery()
                ->getResult();
            $cards = $cardsRepo->search([],1,10,'newest',$testAccounts->getValue());
            $popularCards = $cardsRepo->search([],1,    6,'popular',$testAccounts->getValue());
            $categories = $categoriesRepo->findAllActiveWithCards($testAccounts->getValue());
            $pixies = $usersRepo->findRandomPixies('',$testAccounts->getValue());
        }

        $page = $pagesRepo->findOneBySlug("accueil");

        $coordinates = array_column($regions,'coordinates');
        $center = [];
        // $coordinates = '';
        $noOfCards = [];
//        foreach($regions as $region)
//        {
//            $coordinates []= trim($region[0]->getRegion()->getCoordinates());
//            $noOfCards[] = $region["totalCards"];
//
//
//        }
//        dd($coordinates);
        // $coordinates = str_replace('],[',',',$coordinates);
        // $coordinates = rtrim($coordinates,",");


        $user = $this->getUser();
        $isCardFavoritedFirstTime = false;

        $session  = new Session();
        $card = 0;
        if(!is_null($user)){

            if(!$session->has('card_count_'.$user->getId())){

                $card = count($cardsRepo->findPixieCards($user->getId()));

                $session->set('card_count_'.$user->getId(), $card);

            }else{

                $card = $session->get('card_count_'.$user->getId());
            }

        }else{

            $card = 0;

        }


        if(!is_null($user))
        {
            $filters = ["userFavorite"=>$user->getId()];

            if($cardsRepo->countSearchResult($filters) == 0) {
                $isCardFavoritedFirstTime = true;
            }
        }

        $cardPerRegion = array_combine(array_column($result,'slug'),array_column($result,'card_count'));

        $country = @unserialize(file_get_contents('http://ip-api.com/php/'.$_SERVER['REMOTE_ADDR']));
        
        

        return $this->render('v2/front/homepage/new.html.twig', [
            'page' => $page,
            'regions' => $regions,
            'categories' => $categories,
            'pixies' => $pixies,
            'cards' => $cards,
            'card' => $card,
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

    /**
     * @Route("load-header",name="load_header")
     */
    public function loadHeader()
    {
        return $this->render('v2/_shared/header_ajax.html.twig');
    }


    private function loginClient(Request $request,$client) :void
    {
        $session = new Session();

        $session->set('login_by',['type' => 'login_client','entity' => $client]);

        $token = new UsernamePasswordToken($client, null, 'main', $client->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
        $this->container->get('session')->set('_security_client_area', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
    }

    /**
     * @Route("check-user",name="check_user")
     */
    public function checkUser(Request $request, ClientRepository $clientRepo)
    {
        $user = $this->getUser();

        $client = $clientRepo->findOneBy(['email' => $user->getEmail()]);    
        if($client)
        {
            $this->container->get('security.token_storage')->setToken(null);
            $this->container->get('session')->invalidate();
            $this->loginClient($request, $user);
            return $this->redirectToRoute('b2b_client_main_index');
        }

        $roles = $user->getRoles();

        if(in_array('ROLE_PIXIE', $roles))
        {
            return $this->redirectToRoute('front_pixie_account_homepage');
        }
        else
        {
            return $this->redirectToRoute('front_homepage_index');
        }
    }
}

