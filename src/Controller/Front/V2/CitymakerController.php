<?php

namespace App\Controller\Front\V2;

use App\Constant\CardStatus;
use App\Entity\Card;
use App\Entity\Page;
use App\Repository\CardRepository;
use App\Repository\CardCategoryRepository;
use App\Repository\OptionRepository;
use App\Repository\PageCategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Front\SearchPageController;
/**
 * @Route("", name="front_pixies_")
 */
class CitymakerController extends SearchPageController
{

    private function _groupPixiesByRegions($pixies){
        $this->regions = [];
        foreach($pixies as $user){

            $userRegion = $user->getPixie()->getRegions();

            $found = false;
            foreach($this->regions as &$region){
                if($userRegion[0]->getId() === $region['infos']->getId()){
                    $region['pixies'][] = $user;
                    $found = true;
                }
            }

            if(!$found){
                $this->regions[] = [
                    'infos' => $userRegion[0],
                    'pixies' => [$user]
                ];
            }

        }
    }
    
    /**
     * @Route("/profil-pixie-local/{slug}/{id}", name="single")
     */
    public function cityMaker(
        Request $request,
        UserRepository $usersRepo,
        PageCategoryRepository $pagesCategoriesRepo,
        CardRepository $cardsRepo,
        CardCategoryRepository $categoryRepo,
        OptionRepository $optionRepository
    )
    {
        $testAccountsAsCm = $optionRepository->findOneBy(['slug'=>'dev-cm-email']);
        $user = $usersRepo->searchUserById($request->attributes->get("id"));
        if(!$user->getActive() || !$user->getVisible())
        {
            $this->addFlash('error','Désolé ce pixie ne fait plus partie de l\'équipe Pix.city');
            return $this->redirect('/tous-nos-pixies-locaux-france');
        }

        $pixies = $usersRepo->findRandomPixies();
//        $cards = $cardsRepo->search(["pixie"=> $user->getId()], 1, 100);
        $cards = $cardsRepo->findBy(["pixie" => $user->getId(), "status" => CardStatus::VALIDATED]);
				
        $totalLikes = $cardsRepo->findPixieCardsLikes($user->getId());
        if(empty($totalLikes)) $totalLikes = 0;

//        $regions = $pagesCategoriesRepo->findAllActive($user->getPixie()->getRegions());
        $regions = $user->getPixie()->getRegions();
        $categories = $categoryRepo->findAllActive();
        $pixieRegions = $user->getPixie()->getRegions();
        // Get the number of cards per category per pixie
        $em = $this->getDoctrine()->getManager();
        $cardsPerCategory = $em->getRepository(Card::class)->createQueryBuilder('c')
                                ->select(["count(c) AS count, cat.name, cat.slug, cat.icon"])
                                ->join("c.categories","cat")
                                ->andWhere('c.status = :status')
                                ->andWhere('c.pixie = :cityMaker')
                                ->setParameter('status',CardStatus::VALIDATED)
                                ->setParameter('cityMaker',$user->getId())
                                ->groupBy('cat.id')
                                ->getQuery()
                                ->getResult();
        $cardsPerRegion = $em->getRepository(Card::class)->createQueryBuilder('c')
                            ->select(["count(c) as count, r.name, r.slug, r.id"])
                            ->join("c.region","r")
                            ->andWhere('c.pixie = :cityMaker')
                            ->andWhere('c.status = :status')
                            ->setParameter('cityMaker',$user->getId())
                            ->setParameter('status',CardStatus::VALIDATED)
                            ->groupBy('r.id')
                            ->getQuery()
                            ->getResult();

        $page = new Page();
        $page->setName($user);

        $metaTitle = $user;
        if(count($pixieRegions) > 0) {
            $metaTitle .= ", City-Maker local originaire de ".$pixieRegions[0]->getName();
        }
        $metaTitle .= ". Retrouvez ses photos et avis. Pix.City - guide de voyage local";

        $page->setMetaTitle($metaTitle);
        $page->setMetaDescription("Découvrez le profil de ".$user->getFirstname()." ".$user->getLastname().", city-maker sur Pix.city. ");
        $page->setIndexed(true);
        $metaRobot = '';
        if(strpos($testAccountsAsCm->getValue(),$user->getEmail()) !== false) {
            $metaRobot = 'NOINDEX, NOFOLLOW';
        }else{
            $metaRobot = 'index,follow';
        }
        $categories = $categoryRepo->findCategoriesByCityMaker($request->attributes->get("id"));
        return $this->render('v2/front/citymaker/index.html.twig', [
            'page' => $page,
            'user' => $user,
            'pixies' => $pixies,
            'regions' => $regions,
            'cards' => $cards,
            'totalLikes' => $totalLikes,
            'cardsPerCategory' => $cardsPerCategory,
            'cardsPerRegion' => $cardsPerRegion,
            'categories'=>$categories,
            'metaRobot' =>$metaRobot
        ]);
    }

        /**
     * @Route("/tous-nos-city-makers-locaux-{slug}", defaults={"slug"=null}, name="index")
     */
    public function index(
        Request $request,
        UserRepository $usersRepo,
        PageCategoryRepository $pagesCategoryRepo,
        OptionRepository $optionRepository
    ){
        ini_set('memory_limit','1024M');
        $testAccounts = $optionRepository->findOneBy(['slug'=>'dev-cm-email']);
        $searchParams = $this->getSearchParams($request);

        $pageCategory = null;
        $loggedUser = $this->getUser();
        if($request->attributes->get("slug") && $request->attributes->get("slug") !== "france") {
            $filters = [
                "regions" => [$request->attributes->get("slug")],
                "categories" => $searchParams["categories"],
                "text" => $searchParams["text"]
            ];

            if($loggedUser){
                if(strpos($testAccounts->getValue(),$loggedUser->getEmail()) !== false){ //in
                    $pixies = $usersRepo->searchPixies($filters);
                }
                else{
                    $pixies = $usersRepo->searchPixies($filters,$testAccounts->getValue());
                }
            }
            else{
                $pixies = $usersRepo->searchPixies($filters,$testAccounts->getValue());
            }
            $pageCategory = $pagesCategoryRepo->findOneBySlug($request->attributes->get("slug"));
        }
        else{
            $filters = [
                "regions" => $searchParams["regions"],
                "categories" => $searchParams["categories"],
                "text" => $searchParams["text"]
            ];

            if($loggedUser){
                if(strpos($testAccounts->getValue(),$loggedUser->getEmail()) !== false){ //in
                    $pixies = $usersRepo->searchPixies($filters);
                }
                else{
                    $pixies = $usersRepo->searchPixies($filters,$testAccounts->getValue());
                }
            }
            else{
                $pixies = $usersRepo->searchPixies($filters,$testAccounts->getValue());
            }
        }
        $this->_groupPixiesByRegions($pixies);


        //--------------------------------------------
        // Default page

        if(!isset($page)) {
            $page = new Page();
            $page->setName("Pixies");

            if($pageCategory){
                $page->setMetaTitle("Retrouvez la liste de tous nos Pixies locaux en ".$pageCategory->getName()." découvrez leurs meilleures adresses et photos");
            }
            else{
                $page->setMetaTitle("Retrouvez la liste de tous nos Pixies locaux - découvrez leurs meilleures adresses et photos");
            }

            $page->setMetaDescription("Liste des Pixies - influenceurs locaux par région. Accédez à leurs avis région par région, leurs bons plans près de chez eux: bars, restaurants, sorties, vie nocturne, musées, sport, sorties en famille, sorties avec enfant...");
            $page->setIndexed(true);
        }

//dd($this->regions);
        return $this->render('front/pixies/index.html.twig', [
            'page' => $page,
            'filters' => $searchParams,
            'pixies' => $pixies,
            'regions' => $this->regions,
            'pageCategory' => $pageCategory
        ]);

    }
}
