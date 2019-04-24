<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Calendar;
use Google_Service_Calendar_Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GoogleCalendar
{

    private $_googleCalendarKeyFile;
    private $_router;
    private $_client;
    private $_user;
    private $_em;

    public function __construct($googleCalendarKey, RouterInterface $router, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->_googleCalendarKeyFile = $googleCalendarKey;
        $this->_router = $router;
        $this->_user = $tokenStorage->getToken()->getUser();
        $this->_em = $entityManager;
    }

    /**
     * Load Google Client instance
     */
    public function load(Request $request)
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Calendar API');
        $client->setScopes([Google_Service_Calendar::CALENDAR]);
        $client->setAuthConfig($this->_googleCalendarKeyFile);
        $client->setRedirectUri($this->_router->generate("front_user_account_agendas", [], UrlGeneratorInterface::ABSOLUTE_URL));
        $client->setAccessType('offline');

        $accessToken = $this->_user->getGoogleCalendarToken();

        if(empty($accessToken) || isset($accessToken["error"])) {

            if ($request->query->get("code")) {
                $accessToken = $client->fetchAccessTokenWithAuthCode($request->query->get("code"));
            }

            if(!$accessToken || isset($accessToken["error"])){
                $authUrl = $client->createAuthUrl();
                return $authUrl;
            }
            else{
                $client->setAccessToken($accessToken);
                $this->_saveToken($accessToken);
            }
        }

        $client->setAccessToken($accessToken);


        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            if($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $this->_saveToken($client->getAccessToken());
            }
            else{
                $this->_saveToken(null);
                $authUrl = $client->createAuthUrl();
                return $authUrl;
            }
        }

        $this->_client = $client;

        return true;
    }


    /**
     * Save token to the user account
     */
    private function _saveToken($token){
        if($this->_user instanceof User){
            $this->_user->setGoogleCalendarToken(!empty($token)?json_encode($token):$token);
            $this->_em->persist($this->_user);
            $this->_em->flush();
        }
    }

    /**
     * Get calendars
     *
     * @return Google_Client
     */
    public function getClient(){
        return $this->_client;
    }


    /**
     * Get calendars
     *
     * @return array
     */
    public function getCalendars(){
        if($this->_client) {
            $service = new Google_Service_Calendar($this->_client);
            $calendars = $service->calendarList->listCalendarList(["minAccessRole" => "owner"])->getItems();
        }
        else{
            $calendars = [];
        }
        return $calendars;
    }


    /**
     * Get events from a calendar
     *
     * @param string $calendar
     * @param array $range
     *
     * @return array
     */
    public function getEvents($calendar = null, $range){
        $service = new Google_Service_Calendar($this->_client);

        $calendars = [];

        if($calendar) {
            $calendars = [["calendar" => $service->calendarList->get($calendar), "events" => []]];
        }
        else{
            foreach($service->calendarList->listCalendarList(["minAccessRole" => "owner"])->getItems() as $calendar){
                $calendars[] = ["calendar" => $calendar, "events" => []];
            }
        }

        foreach($calendars as &$calendar) {

            $dateFrom = new DateTime($range[0]);
            $dateTo = new DateTime($range[1]);

            $optParams = array(
                "singleEvents" => true,
                "timeMin" => $dateFrom->format(\DateTime::RFC3339),
                "timeMax" => $dateTo->format(\DateTime::RFC3339)
            );

            $results = $service->events->listEvents($calendar["calendar"]["id"], $optParams);

            if($results) {
                $events = $results->getItems();

                if (empty($events)) {

                } else {
                    foreach ($events as $event) {
                        $start = $event->start->dateTime;
                        if (empty($start)) {
                            $start = $event->start->date;
                        }

                        $calendar["events"][] = $event;
                    }
                }
            }
        }

        return $calendars;
    }


    /**
     * Create new calendar
     *
     * @param string $name
     *
     * @return Google_Service_Calendar_Calendar
     */
    public function createCalendar($name){
        $service = new Google_Service_Calendar($this->_client);

        $calendar = new Google_Service_Calendar_Calendar();
        $calendar->setSummary($name);

        $newCalendar = $service->calendars->insert($calendar);

        return $newCalendar;
    }

    /**
     * Create new events and save them to a calendar
     *
     * @param string $calendarId
     * @param Card[] $cards
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return Google_Service_Calendar_Event[]
     */
    public function addEvents($calendarId, $cards, $from, $to){

        $service = new Google_Service_Calendar($this->_client);

        $events = [];
        foreach($cards as $card){

            $events[] = $service->events->insert($calendarId, new Google_Service_Calendar_Event([
                'summary' => $card->getName(),
                'description' => $this->_router->generate("front_card_single", ["slug" => $card->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
                'start' => [
                    'date' => $from->format("Y-m-d")
                ],
                'end' => [
                    'date' => $to->format("Y-m-d")
                ],
                'source' => [
                    "title" => $card->getName(),
                    "url" => $this->_router->generate("front_card_single", ["slug" => $card->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL)
                ]
            ]));

        }

        return $events;
    }

    /**
     * Update dates of an existing event
     *
     * @param string $calendarId
     * @param string $eventId
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return Google_Service_Calendar_Event
     */
    public function updateEventDate($calendarId, $eventId, $from, $to){

        $service = new Google_Service_Calendar($this->_client);

        $event = new Google_Service_Calendar_Event([
            'start' => [
                'date' => $from->format("Y-m-d")
            ],
            'end' => [
                'date' => $to->format("Y-m-d")
            ]
        ]);

        $updatedEvent = $service->events->patch($calendarId, $eventId, $event);

        return $updatedEvent;
    }

    /**
     * Delete an existing event
     *
     * @param string $calendarId
     * @param string $eventId
     *
     */
    public function deleteEvent($calendarId, $eventId){

        $service = new Google_Service_Calendar($this->_client);
        $service->events->delete($calendarId, $eventId);

    }

    /**
     * Delete calendar
     *
     * @param string $calendarId
     *
     */
    public function deleteCalendar($calendarId){

        $service = new Google_Service_Calendar($this->_client);

        try {
            $res = $service->calendarList->delete($calendarId);
        }
        catch(\Exception $e){
            $res = ["error" => "true"];
        }

        return $res;
    }
}