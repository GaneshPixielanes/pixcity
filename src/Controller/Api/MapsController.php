<?php

namespace App\Controller\Api;

use App\Constant\CardStatus;
use App\Entity\Card;
use App\Entity\CardDetailsApi;
use App\Entity\User;
use App\Entity\UserInstagramDetailsApi;
use App\Entity\InstagramTrends;
use App\Repository\CardRepository;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * @Route("/api/maps", name="api_maps_")
 */
class MapsController extends AbstractController
{

    // private $key = 'AIzaSyDDFjaLU8H-TpATXcUcDzKQ4w05pCQ5mAg';
    private $key = 'AIzaSyAk8YGvxDz6pEyaGQE2sfVvbCGREgi_2Qk';

    /**
     * @Route("/api/maps", name="api_maps")
     */
    public function index()
    {
        return $this->render('api/maps/index.html.twig', [
            'controller_name' => 'MapsController',
        ]);
    }

    /**
     * @Route("/profile/{id}",name="profile")
     */
    public function profile($id, CardRepository $cardRepo, UserRepository $userRepo)
    {
        $result = [];
        //Get the details of the validated cards corresponding to the user
        $cards = $cardRepo->findBy([
            'pixie' => $userRepo->find($id),
            'status' => CardStatus::VALIDATED
        ]);
        if(!empty($cards))
        {
            foreach($cards as $card)
            {
                $result[] = [
                  'latitude' => trim($card->getAddress()->getLatitude()),
                  'longitude' => trim($card->getAddress()->getLongitude()),
                  'icon' => $card->getCategories()->first()->getIcon(),
                  'id' => $card->getId()
                ];
            }
        }
        return new JsonResponse(json_encode($result));
    }

    /**
     * @Route("/region-cards/{id}",name="region-cards")
     */
    public function regionCards($id, RegionRepository $regionRepo, CardRepository $cardRepo)
    {
        $result = [];

        $searchParams["regions"] = [$regionRepo->find($id)->getSlug()];
        $cards = $cardRepo->search($searchParams, 1, 10, 'createdAt');
        if(!empty($cards))
        {
            foreach($cards as $card)
            {
                if($card->getStatus() == CardStatus::VALIDATED)
                {
                    if($card->getCategories()->count() != 0)
                    {
                        $result[] = [
                            'latitude' => trim($card->getAddress()->getLatitude()),
                            'longitude' => trim($card->getAddress()->getLongitude()),
                            'icon' => $card->getCategories()->first()->getIcon(),
                            'id' => $card->getId()
                        ];
                    }
                }

            }
        }
        return new JsonResponse(json_encode($result));

    }


    /**
     * @Route("/place-info/{id}")
     */
    public function saveCardDetails($id, CardRepository $cardRepo)
    {

        $card = $cardRepo->find($id);
        $cards = $cardRepo->findBy(['status' => CardStatus::VALIDATED,'gmbFlag' => 0],['createdAt' => 'ASC'],9);
                $message = [];
        foreach($cards as $card){
            sleep(2);
                        // dd($card->getCardDetailsApi());
            if(is_null($card->getCardDetailsApi()))
            {
                $response = json_decode($this->getPlaceDetails($card));
                if($response->status == "OK")
                {

                    $result = $response->result;
                    $details = new CardDetailsApi();


                    $details->setAddress($result->formatted_address);
                    $details->setName($result->name);
                    if(isset($result->international_phone_number))
                    {
                        $details->setPhone($result->international_phone_number);
                    }
                    if(isset($result->website))
                    {
                        $details->setWebsite($result->website);
                    }
                    if(isset($result->rating))
                    {
                      $details->setRating($result->rating);
                    }
                    $details->setCategory(json_encode($result->types));
                    $details->setFullResponse(json_encode($result));
                    $details->setStatus(1);
                    $details->setCreatedAt(new \DateTime());
                    $details->setCard($card);

                                        $card->setGmbFlag('1');

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($card);
                    $entityManager->persist($details);
                    $entityManager->flush();

                    $message[] = JsonResponse::fromJsonString(json_encode(['success' => true, 'message' => 'Card Details have been updated']));

                }
                else
                {
                                    $card->setGmbFlag('2');

                                    $entityManager = $this->getDoctrine()->getManager();
                                    $entityManager->persist($card);
                                    $entityManager->flush();

                  $message[] = JsonResponse::fromJsonString(json_encode(['success' => false, 'message' => 'API did not return any result, please try again','description' => json_encode($response)]));
                }
            }
        }
        // echo "Here";
        $message[] = JsonResponse::fromJsonString(json_encode(['success' => true, 'message' => 'Card Details have been updated']));

        dd($message);
    }

    private function getPlaceDetails($card)
    {

        // Get the key randomly from the list of array
        $api = $this->key;

        // sleep(5);
        $place = $this->getPlaceId($card->getAddress()->getLatitude(), $card->getAddress()->getLongitude(), $card->getName());
//        $place = $this->getPlaceId(48.8281556, 2.3505935, $card->getName());
        sleep(1);
        $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$place."&fields=name,rating,formatted_address,international_phone_number,reviews,types,vicinity,website,opening_hours,international_phone_number,photos&key=".$api;
//        dd($url);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function getPlaceId($lat, $lng, $address)
    {

        // Get the key randomly from the list of array
        $api = $this->key;
        $data['lat'] = $lat;
        $data['lng'] = $lng;
        $data['address'] = $address;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input='.urlencode($address).'&inputtype=textquery&fields=place_id&key='.$api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($ch);
        curl_close($ch);
        $results = json_decode($response);
        if(!empty($results->candidates))
        {
            return $results->candidates[0]->place_id;
        }
    }

    private function _getGooglePhoto($photoId)
    {
        $ch = curl_init();
         $url = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=$photoId&key=AIzaSyCMpiZh32qaAGMVSlc2XAptENMQKt-WY6c";
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        dd($response);
    }

    /**
     * @Route("/curl-test")
     */
    public function curl_test()
    {
        $ch = curl_init();
        $data = ['social_media' => ['users' => '#gtodanesh']];
        $url = "http://172.104.240.209:4000/apis/social_medias/instagram_user_from_id";
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        dd(json_decode($response));
        echo $response;exit;
    }

    /**
    * @Route("/update-instagram-flag", name="_update_instagram_flag")
    */
    public function updateUserInstagramFlag(UserRepository $userRepo)
    {
        $users = $userRepo->findBy(['visible' => 1, 'igFlag' => 0]);
        $list = [];
        $entityManager = $this->getDoctrine()->getManager();
        
        foreach($users as $user)
        {
            if(!is_null($user->getUserInstagramDetailsApi()))
            {
                $list[] = $user->getId();
                $user->setIgFlag(1);
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }
        dd($list);
    }

    /**
    *@Route("/instagram-cron/",name="_instagram_info")
    */
    public function saveInstagramCron(UserRepository $userRepo)
    {
        $users = $userRepo->findBy(['igFlag' => 0]);
        // dd($users);
        foreach($users as $user)
        {
            $count = 0;
            if($count < 30 && is_null($user->getUserInstagramDetailsApi()))
            {
                $result[] = $this->_saveUserInstagramInfo($user);
                $count++;
            }
        }
        if(isset($result))
        {
            dd($result);
        }
        else
        {
            echo "No users left"; exit;
        }
    }
    private function _saveUserInstagramInfo($user)
    {
//        $user = $userRepo->find($id);
        $igFlag = false;
        // Get the Instagram ID of the user
        if(!is_null($user))
        {
            foreach($user->getLinks() as $socialMedia)
            {
                if($socialMedia->getType() == "instagram")
                {
                    $igFlag = true;
                    if(isset(explode('/', $socialMedia->getUrl())[3]))
                    {
                        $instagram = explode('/', $socialMedia->getUrl())[3];
                        $details = $this->_getInstagramInfo($instagram);
                        $details = stripslashes($details);
                        $details = str_replace('{data: ["','',$details);
                        $details = str_replace('"]}','',$details);
                        $details = json_decode($details,true);
                        $instagramDetails = $user->getUserInstagramDetailsApi();
                        if(is_null($instagramDetails))
                        {
                            $instagramDetails = new UserInstagramDetailsApi();
                            $instagramLog = new InstagramTrends();

                            $instagramDetails->setNoOfFollowed($details['following']);
                            $instagramDetails->setNoOfFollowers($details['followers']);
                            $instagramDetails->setNoOfPosts($details['posts']);
                            $instagramDetails->setName($details['user']);
                            $instagramDetails->setResponse(json_encode($details));
                            $instagramDetails->setUser($user);
                            $instagramDetails->setCreatedAt(new \DateTime());
                            $user->setIgFlag(1);

                            $instagramLog->setName($details['user']);
                            $instagramLog->setNoOfPosts($details['followers']);
                            $instagramLog->setNoOfFollowers($details['followers']);
                            $instagramLog->setNoOfFollowed($details['following']);
                            $instagramLog->setDescription($details['posturl']);
                            $instagramLog->setUser($user);
                            $instagramLog->setCreatedAt(new \DateTime());
                            $instagramLog->setResponse(json_encode($details));

                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($instagramDetails);
                            $entityManager->persist($instagramLog);
                            $entityManager->persist($user);
                            $entityManager->flush();
                        }
                    }
                    else
                    {
                        $user->setIgFlag(2);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();
                        return JsonResponse::fromJsonString(json_encode(['success'=>false,'message' => 'Instagram account not found']));
                    }

//                    dd($instagramDetails);
                }

            }

            if($igFlag == false)
            {
                $user->setIgFlag(2);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return JsonResponse::fromJsonString(json_encode(['success'=>false,'message' => 'Instagram account not found']));
            }
            return JsonResponse::fromJsonString(json_encode(['success'=>true,'message' => 'Instagram details udpated']));
        }



        return JsonResponse::fromJsonString(json_encode(['success' => false, 'message' => 'User does not exist']));
    }

    private function _getInstagramInfo($instagram)
    {
        $ch = curl_init();
        $data = ['social_media' => ['users' => '#'.$instagram]];
        $url = "http://172.104.240.209:4000/apis/social_medias/instagram_user_from_id";
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
    /**
     * @Route("/card-details/{id}",name="card_details")
     */
    public function cardDetails($id, CardRepository $cardRepo, UserRepository $userRepo, RouterInterface $router)
    {
        $card = $cardRepo->find($id);
        $user = $userRepo->find($card->getPixie()->getId());


        $cardDetails = $card->getCardDetailsApi();
        $website = '';
        $cat = [];
        $icons = [];
        $instagram = $card->getPixie()->getUserInstagramDetailsApi();
        $pixieCards = $cardRepo->findBy(['pixie' => $card->getPixie(), 'status' => CardStatus::VALIDATED]);
//        dd($instagram);
        foreach($card->getCategories() as $category)
        {
            $cat[] = $category->getName();
            $icons[] = $category->getIcon();
        }
        foreach($card->getInfos() as $info)
        {
            if($info->getType() == 'link')
            {
                $website = $info->getValue();
            }
        }

        $details = array();
        $date = new \DateTime('today');


        if(!is_null($cardDetails))
        {
            $week = isset(json_decode($cardDetails->getFullResponse(),true)['opening_hours'])?json_decode($cardDetails->getFullResponse(),true)['opening_hours']['weekday_text']:'';
            $details['name'] = $cardDetails->getName();
            $details['address'] = $cardDetails->getAddress();
            $details['phone'] = $cardDetails->getPhone();
            $details['rating'] = $cardDetails->getRating();
            $details['website'] = $cardDetails->getWebsite();
            $details['category'] = json_decode($cardDetails->getCategory());
                        if($date->format('w') == 0)
                        {
                            $details['open_status'] = ($week !== '')?$week[6]:'';
                        }
                        else
                        {
                            $details['open_status'] = ($week !== '')?$week[($date->format('w')-1)]:'';
                        }

        }
        else
        {
            $details['name'] = 'NA';
            $details['address'] = 'NA';
            $details['phone'] = 'NA';
            $details['rating'] = 'NA';
            $details['website'] = 'NA';
            $details['category'] = ['NA'];
            $details['open_status'] = 'NA';
        }

        $details['card_id'] = $card->getId();
        $details['card_name'] = $card->getName();
        $details['card_address'] = $card->getAddress()->getAddress();
        $details['card_phone'] = '';
        $details['card_website'] = $website;
        $details['card_category'] = json_encode($cat);
        $details['card_main_category'] = $cat[0];
        $details['card_city'] = $card->getAddress()->getCity();
        $details['card_icon'] = $icons[0];
        $details['pixie_id'] = $card->getPixie()->getId();
        $details['pixie_url'] = $card->getPixie()->getAvatar()->getUrl();
        $details['pixie_profile_url'] = $router->generate('front_pixies_single', ['slug'=> $card->getPixie()->getSlug(), 'id'=>$card->getPixie()->getId()]);
        // dd($instagram)
        if(!is_null($instagram))
        {
            $details['insta_name'] = $instagram->getName();
            $details['insta_followers'] = $instagram->getNoOfFollowers();
            $details['insta_followed'] = $instagram->getNoOfFollowed();
            $details['insta_posts'] = $instagram->getNoOfPosts();
            $details['total_likes'] = $cardRepo->findPixieCardsLikes($card->getPixie()->getId());
            foreach($card->getPixie()->getLinks() as $socialMedia)
            {
                if($socialMedia->getType() == "instagram")
                {
                    $details['insta_url'] = $socialMedia->getUrl();
                }
            }

        }
        else
        {
          $details['insta_name'] = '';
          $details['insta_followers'] = '';
          $details['insta_followed'] = '';
          $details['insta_posts'] = '';
          $details['total_likes'] = '0';
          $details['insta_url'] = '';
        }

        foreach($card->getMedias() as $media)
        {
            $details['banners'][] = $media->getUrl();
        }

        return $this->render('v2/_shared/sidebar.html.twig', [
            'details' => $details,
            'pixieCards' => $pixieCards,
            'card' => $card,
            'user' => $user
        ]);
        // return JsonResponse::fromJsonString(json_encode($details));
    }

    /**
     * @Route("/user-cards",name="_user_cards")
     */
    public function user_cards(CardRepository $cardRepo)
    {
        $user = $this->getUser();
        $result = [];

        $filters = ["userFavorite"=>$user->getId()];

        $cards = $cardRepo->search($filters);
        foreach($cards as $card)
        {
            if($card->getStatus() == CardStatus::VALIDATED) {
                if ($card->getCategories()->count() != 0) {
                    $result[] = [
                        'latitude' => trim($card->getAddress()->getLatitude()),
                        'longitude' => trim($card->getAddress()->getLongitude()),
                        'icon' => $card->getCategories()->first()->getIcon(),
                        'id' => $card->getId()
                    ];
                }
            }
        }

        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * @Route("/find-region-card/{id}/{slug}/{category}",defaults={"slug"=null,"category" = null},name="_find_card")
     */
    public function findRegionCard($id,$slug, CardRepository $cardRepo, Request $request)
    {
    //        $card = $cardRepo->findBy(['name' => urldecode($slug)]);
        $em = $this->getDoctrine()->getManager();

            $result = $em->getRepository(Card::class)->createQuerybuilder('c')
                ->select(["a.latitude, a.longitude, c.id, t.icon"])
                ->join('c.address','a')
                ->join('c.categories','t')
                ->join('c.region','r')
                ->andWhere('c.status = :status')->setParameter('status',CardStatus::VALIDATED)
                ->andWhere('c.region = :region')->setParameter('region',$id);

                if(!is_null($request->get('category'))  && $request->get('category') != 'all')
                {
                  $result = $result->andWhere('t.id = :category')->setParameter('category',$request->get('category'));
                }
                if(!is_null($slug) && 'na' != $slug && '' != trim($slug))
                {
                  $result = $result->andWhere('c.name LIKE :name')->setParameter('name','%'.$slug.'%');
                }
                $result = $result->getQuery()
                          ->getResult();


        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * @Route("/find-all-card/{slug}/{category}",defaults={"slug"=null, "category" = null},name="_find_all_card")
     */
    public function findAllCard(CardRepository $cardRepo, Request $request)
    {
        $slug = $request->get('slug');
        $category = $request->get('category');

        $em = $this->getDoctrine()->getManager();

        $result = $em->getRepository(Card::class)->createQuerybuilder('c')
                ->select(["a.latitude, a.longitude, c.id, t.icon"])
                ->join('c.address','a')
                ->join('c.categories','t')
                ->join('c.region','r');
        if(null !== $request->get('category') && 'all' !== $request->get('category'))
        {
          $result= $result->andWhere('t.id = :category')->setParameter('category',$request->get('category'));
        }
        if(!is_null($slug) && 'na' !== $slug && '' !== trim($slug))
        {
          $result = $result->andWhere('c.name LIKE :name')->setParameter('name','%'.$slug.'%');
        }


        $result = $result->andWhere('c.status = :status')->setParameter('status',CardStatus::VALIDATED)
                  ->getQuery()
                  ->getResult();

        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
    * @Route("/all-cards",name="_all_card")
    */
    public function allCards(Request $request, CardRepository $cardRepo)
    {
       # Get the corresponding filters
        
       $filters['regions'] = $request->get('regions');
       $filters['text'] = trim($request->get('text'));
       $filters['content'] = trim($request->get('text'));
       if(!is_null($request->get('categories')) && !in_array(0,$request->get('categories')))
       {
            $filters['categories'] = $request->get('categories');
       }

       # Get the markers w.r.t the filters

       return JsonResponse::fromJsonString(json_encode($cardRepo->findAllCardsValidated($filters)));
    }

    /**
    * @Route("/search-cards",name="_search_card")
    */
    public function searchCards(Request $request, CardRepository $cardRepo)
    {
       $em = $this->getDoctrine()->getManager();
             $filters["regions"] = $request->get('regions');

       $result = $em->getRepository(Card::class)->createQuerybuilder('c')
                   ->select(["a.latitude, a.longitude, c.id, t.icon"])
                    ->join('c.address','a')
                    ->leftJoin('c.categories','t')
                    ->join('c.region','r')
                    ->andWhere('c.status = :status')
                    ->andWhere('c.name LIKE :search OR c.content LIKE :search OR r.name LIKE :search')
                    ->setParameter('status',CardStatus::VALIDATED)
                    ->setParameter('search','%'.$request->get('search').'%');
                                        if (!empty($filters["regions"])) {
                                                $result = $result->andWhere("r IN (:regions) OR r.slug IN (:regions)")->setParameter("regions", $filters["regions"]);
                                        }
            $result = $result->groupBy('c.id')
                    ->getQuery()
                    ->getResult();

        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * @Route("/fav-cards",name="_fav_card")
     */
    public function favCards(CardRepository $cardRepo)
    {
        $user = $this->getUser();
        $result = [];
        $filters = ["userFavorite"=>$user->getId()];

        $filters = ["userFavorite"=>$user->getId()];
        $cards = $cardRepo->search($filters);
        if(!empty($cards))
        {
            foreach($cards as $card)
            {
                if($card->getStatus() == CardStatus::VALIDATED)
                {
                  // dd($card->getCategories());
                    if($card->getCategories()->count() != 0)
                    {
                        $result[] = [
                            'latitude' => trim($card->getAddress()->getLatitude()),
                            'longitude' => trim($card->getAddress()->getLongitude()),
                            'icon' => $card->getCategories()->first()->getIcon(),
                            'id' => $card->getId()
                        ];
                    }
                    else
                    {
                      $result[] = [
                          'latitude' => trim($card->getAddress()->getLatitude()),
                          'longitude' => trim($card->getAddress()->getLongitude()),
                          'icon' => 'fa-tree',
                          'id' => $card->getId()
                      ];
                    }
                }

            }
        }
        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * @Route("/city-maker-card/{id}",name="cityMakerCard")
     */
    public function cityMakerCard($id, CardRepository $cardRepo, UserRepository $user)
    {
        $cards = $cardRepo->findBy(['pixie' => $user->find($id), 'status' => CardStatus::VALIDATED]);
        if(!empty($cards))
        {
            foreach($cards as $card)
            {
                if($card->getStatus() == CardStatus::VALIDATED)
                {
                    if($card->getCategories()->count() != 0)
                    {
                        $result[] = [
                            'latitude' => trim($card->getAddress()->getLatitude()),
                            'longitude' => trim($card->getAddress()->getLongitude()),
                            'icon' => $card->getCategories()->first()->getIcon(),
                            'id' => $card->getId()
                        ];
                    }
                }

            }
        }
        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * @Route("/city-maker-card-search/{id}/{slug}/{category}",name="cityMakerCardSearch", defaults={"category"=null, "slug" = null})
     */
    public function cityMakerCardSearch($id, $slug, CardRepository $cardRepo, UserRepository $user, Request $request)
    {
//        $cards = $cardRepo->findBy(['pixie' => $user->find($id), 'status' => CardStatus::VALIDATED]);
        $filters = [
            "pixie" => $user->find($id),
            "text" => $slug
        ];
        $em = $this->getDoctrine()->getManager();

        $result = $em->getRepository(Card::class)->createQuerybuilder('c')
                    ->select(["a.latitude, a.longitude, c.id, t.icon"])
                     ->join('c.address','a')
                     ->join('c.categories','t')
                     ->join('c.region','r')
                     ->join('c.pixie','p')
                     ->andWhere('c.status = :status')->setParameter('status',CardStatus::VALIDATED)
                     ->andWhere('p.id = :user')->setParameter('user',$id);
                     if(!is_null($request->get('slug')) && 'na' !== $request->get('slug') && '' !== trim($slug))
                     {
                       $result = $result->andWhere('c.name LIKE :search OR c.content LIKE :search OR r.name LIKE :search')->setParameter('search','%'.$slug.'%');
                     }
                     if(!is_null($request->get('category')) && $request->get('category') != 'all')
                     {
                       $result = $result->andWhere('t.id = :category')->setParameter('category',$request->get('category'));
                     }


                     $result = $result->getQuery()
                     ->getResult();

         return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * @Route("/find-all-result-card/{search}/{slug}/{category}",defaults={"search"=null,"slug"=null, "category" = null},name="_find_all_result_card")
     */
    public function findAllResultCard(CardRepository $cardRepo, Request $request)
    {
        $slug = $request->get('slug');
        if($request->get('search') == 'search-all')
        {
            $search = null;
        }
        else
        {
            $search = trim($request->get('search'));
        }
        $category = $request->get('category');

        $em = $this->getDoctrine()->getManager();

        $result = $em->getRepository(Card::class)->createQuerybuilder('c')
                ->select(["a.latitude, a.longitude, c.id, t.icon"])
                ->join('c.address','a')
                ->join('c.categories','t')
                ->join('c.region','r')
                ->andWhere('c.name LIKE :search OR c.content LIKE :search OR r.name LIKE :search')->setParameter('search','%'.$search.'%');
        if(null !== $request->get('category') && 'all' !== $request->get('category'))
        {
          $result= $result->andWhere('t.id = :category')->setParameter('category',$request->get('category'));
        }
        if(!is_null($slug) && 'na' !== $slug && '' !== trim($slug))
        {
          $result = $result->andWhere('c.name LIKE :name')->setParameter('name','%'.$slug.'%');
        }


        $result = $result->andWhere('c.status = :status')->setParameter('status',CardStatus::VALIDATED)
                  ->getQuery()
                  ->getResult();

        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * @Route("/fav-cards-search/{slug}/{category}",defaults={"slug"=null, "category" = null},name="_fav_search_card")
     */
    public function favCardSearch(CardRepository $cardRepo, Request $request)
    {
        $slug = $request->get('slug');
        $user = $this->getUser();
        // dd($slug);
        $category = $request->get('category');

        $em = $this->getDoctrine()->getManager();

        $result = $em->getRepository(Card::class)->createQuerybuilder('c')
                ->select(["a.latitude, a.longitude, c.id, t.icon"])
                ->join('c.address','a')
                ->join('c.categories','t')
                ->join('c.region','r')
                ->join('c.favoriteUsers','f')
                ->andWhere('f.id = :user')->setParameter('user',$user->getId());

        if(null !== $request->get('category') && $request->get('category') != 'all')
        {
          $result= $result->andWhere('t.id = :category')->setParameter('category',$request->get('category'));
        }
        if(!is_null($slug) && 'na' !== $slug)
        {
          $result = $result->andWhere('c.name LIKE :search OR c.content LIKE :search OR r.name LIKE :name')->setParameter('name','%'.$slug.'%');
        }


        $result = $result->andWhere('c.status = :status')->setParameter('status',CardStatus::VALIDATED)
                  ->getQuery()
                  ->getResult();

        return JsonResponse::fromJsonString(json_encode($result));
    }

    /**
     * @Route("/card-single/{id}",name="_card_single")
     */
    public function cardSingle($id, CardRepository $cardRepo)
    {
        $card = $cardRepo->find($id);

        return JsonResponse::fromJsonString(json_encode([['latitude' => $card->getAddress()->getLatitude(), 'longitude' => $card->getAddress()->getLongitude(), 'id' => $card->getId(), 'icon' => $card->getCategories()->first()->getIcon()]]));
    }

}
