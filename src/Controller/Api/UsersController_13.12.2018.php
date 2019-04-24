<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\UserOptin;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use App\Service\GoogleCalendar;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/users", name="api_users_")
 */

class UsersController extends Controller
{

    /**
     * AJAX
     * @Route("/pixies", name="pixies_list")
     * @Method({"GET"})
     */
    public function pixies(Request $request, UserRepository $users)
    {
        $query = $users->createQueryBuilder("u");

        $query = $query->innerJoin('u.pixie', 'p');
        $query = $query->innerJoin('p.regions', 'r');

        if($request->query->get('regionId')){
            $query = $query->andWhere('r.id = :regionId')->setParameter('regionId', $request->query->get('regionId'));
        }

        if($request->query->get('search')){
            $query = $query->andWhere('CONCAT(u.firstname, \' \', u.lastname) LIKE :search')->setParameter('search', "%".$request->query->get('search')."%");
        }

        //$query = $query->orderBy('u.createdAt', 'DESC');
        $query = $query->andWhere('u.visible = 1');
        $pixies = $query->getQuery()->getResult();


        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array($normalizer), array($encoder));

        return new Response($serializer->serialize($pixies, 'json', [
            'attributes' => [
                'id',
                'firstname',
                'lastname',
                'pixie' => [
                    'regions'=>[
                        'name'
                    ]
                ]
            ]
        ]),
        Response::HTTP_OK,
        ['Content-type' => 'application/json']);
    }


    /**
     * AJAX
     * @Route("/list", name="list")
     * @Method({"GET"})
     */
    public function users(Request $request, UserRepository $users)
    {
        $query = $users->createQueryBuilder("u");

        if($request->query->get('q')){
            $query = $query->andWhere('CONCAT(u.firstname, \' \', u.lastname) LIKE :search')->setParameter('search', "%".$request->query->get('q')."%");
        }

        $results = $query->getQuery()->setMaxResults(10)->getResult();

        $json = [
            "results" => []
        ];
        foreach($results as $user){
            $json["results"][] = [
                "id" => $user->getId(),
                "text" => $user->getFirstname() . ' ' . $user->getLastname()
            ];
        }

        return new JsonResponse($json);
    }


    /**
     * AJAX
     * @Route("/update/optin", name="update_optin")
     * @Method({"PUT"})
     */
    public function update_optin(Request $request)
    {
        $user = $this->getUser();

        if($user){
            $optin = $request->request->get("optin", false)?true:false;

            if(!$user->getOptin()){
                $user->setOptin(new UserOptin());
            }

            $user->getOptin()->setNewsletter($optin);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return new JsonResponse();
    }


    /**
     * AJAX
     * @Route("/follow/pixie", name="follow_pixie")
     * @Method({"POST"})
     */
    public function toggle_follow_pixie(Request $request, UserRepository $userRepo)
    {
        $user = $this->getUser();

        if($user){
            $pixieId = $request->request->get("pixie");
            $pixie = $userRepo->findOneBy(["id" => $pixieId]);

            if($pixie) {
                if(!$user->hasFavoritePixie($pixie)) {
                    $user->addFavoritePixie($pixie);
                }
                else{
                    $user->removeFavoritePixie($pixie);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }

        }

        return new JsonResponse();
    }

    /**
     * AJAX
     * @Route("/like/card", name="like_card")
     * @Method({"POST"})
     */
    public function toggle_like_card(Request $request, CardRepository $cardRepo)
    {
        $user = $this->getUser();

        if($user instanceof User){
            $cardId = $request->request->get("card");
            $card = $cardRepo->findOneBy(["id" => $cardId]);

            if($card) {
                if(!$user->hasLike($card)) {
                    $user->addLike($card);
                }
                else{
                    $user->removeLike($card);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }

        }

        return new JsonResponse();
    }

    /**
     * AJAX
     * @Route("/favorite/card", name="favorite_card")
     * @Method({"POST"})
     */
    public function toggle_favorite_card(Request $request, CardRepository $cardRepo)
    {
        $user = $this->getUser();

        if($user instanceof User){
            $cardId = $request->request->get("card");
            $card = $cardRepo->findOneBy(["id" => $cardId]);

            if($card) {
                if(!$user->hasFavorite($card)) {
                    $user->addFavorite($card);
                }
                else{
                    $user->removeFavorite($card);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }

        }

        return new JsonResponse();
    }



    /**
     * AJAX get calendars
     * @Route("/calendars", name="calendars")
     * @Method({"GET"})
     */
    public function calendars(Request $request, GoogleCalendar $googleCalendar)
    {
        $res = $googleCalendar->load($request);

        if(true !== $res){
            return $this->redirect($res);
        }

        $calendars = $googleCalendar->getEvents($request->query->get("calendar", null), [$request->query->get("from"), $request->query->get("to")]);

        return new JsonResponse($calendars);
    }

    /**
     * AJAX create calendar
     * @Route("/calendar/new", name="new_calendar")
     * @Method({"POST"})
     */
    public function new_calendar(Request $request, GoogleCalendar $googleCalendar)
    {
        $res = $googleCalendar->load($request);
        if(true !== $res){return false;}

        $newCalendar = $googleCalendar->createCalendar($request->request->get("name"));

        return new JsonResponse($newCalendar);
    }

    /**
     * AJAX delete calendar
     * @Route("/calendar/delete", name="delete_calendar")
     * @Method({"POST"})
     */
    public function delete_calendar(Request $request, GoogleCalendar $googleCalendar)
    {
        $res = $googleCalendar->load($request);
        if(true !== $res){return false;}

        $res = $googleCalendar->deleteCalendar($request->request->get("calendar"));

        return new JsonResponse($res);
    }

    /**
     * AJAX create new events
     * @Route("/calendar/event/new", name="new_calendar_event")
     * @Method({"POST"})
     */
    public function new_calendar_event(Request $request, GoogleCalendar $googleCalendar, CardRepository $cardRepo)
    {
        $res = $googleCalendar->load($request);
        if(true !== $res){return false;}

        $cardIds = $request->request->get("cardIds", null);
        $cards = $cardRepo->search(["cards" => $cardIds]);

        $newEvents = [];
        if(!empty($cards)) {

            $from = new DateTime($request->request->get("dateFrom", null));
            $to = new DateTime($request->request->get("dateTo", null));

            $newEvents = $googleCalendar->addEvents($request->request->get("calendar", null), $cards, $from, $to);
        }

        return new JsonResponse($newEvents);
    }

    /**
     * AJAX update event
     * @Route("/calendar/event/update", name="update_calendar_event")
     * @Method({"POST"})
     */
    public function update_calendar_event(Request $request, GoogleCalendar $googleCalendar)
    {
        $res = $googleCalendar->load($request);
        if(true !== $res){return false;}

        $from = new DateTime($request->request->get("dateFrom", null));
        $to = new DateTime($request->request->get("dateTo", null));

        $newEvent = $googleCalendar->updateEventDate($request->request->get("calendar"), $request->request->get("event"), $from, $to);

        return new JsonResponse($newEvent);
    }

    /**
     * AJAX delete event
     * @Route("/calendar/event/delete", name="delete_calendar_event")
     * @Method({"POST"})
     */
    public function delete_calendar_event(Request $request, GoogleCalendar $googleCalendar)
    {
        $res = $googleCalendar->load($request);
        if(true !== $res){return false;}

        $googleCalendar->deleteEvent($request->request->get("calendar"), $request->request->get("event"));

        return new JsonResponse();
    }

}