<?php

namespace App\Controller\Admin\Pages;

use App\Constant\CardStatus;
use App\Repository\CardProjectRepository;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use Google_Client;
use Google_Service_Analytics;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/admin/dashboard", name="admin_dashboard_")
 */

class DashboardController extends Controller
{
    private $_analyticsKeyFile;
    private $_cache;

    public function __construct($analyticsKey, CacheItemPoolInterface $cache)
    {
        $this->_analyticsKeyFile = $analyticsKey;
        $this->_cache = $cache;
    }

    /**
     * @Route("", name="index")
     */
    public function index(Request $request, AuthenticationUtils $authUtils, UserRepository $users, CardProjectRepository $projects, CardRepository $cards)
    {

        //------------------------------------------
        // Get users stats
        //------------------------------------------

        $totalPixies = $users->createQueryBuilder("u")
            ->select('COUNT(u)')
            ->where("u.pixie IS NOT NULL")
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $totalUsers = $users->createQueryBuilder("u")
            ->select('COUNT(u)')
            ->where("u.pixie IS NULL")
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $totalCards = $cards->createQueryBuilder("c")
            ->select('COUNT(c)')
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $cardsPending = $cards->createQueryBuilder("c")
            ->andWhere('c.status = :status')->setParameter('status', CardStatus::SUBMITTED)
            ->getQuery()
            ->getResult()
        ;


        //------------------------------------------
        // Google Analytics
        //------------------------------------------

        $cachedReportLastWeek = $this->_cache->getItem("ga.reportLastWeek");
        $cachedVisitsPerDay = $this->_cache->getItem("ga.visitsPerDay");
        $cachedPagesMostViewed = $this->_cache->getItem("ga.pagesMostViewed");
        $cachedTopRegions = $this->_cache->getItem("ga.topRegions");

        $reportLastWeek = (!empty($cachedReportLastWeek->get()))?$cachedReportLastWeek->get():null;
        $visitsPerDay = (!empty($cachedVisitsPerDay->get()))?$cachedVisitsPerDay->get():null;
        $pagesMostViewed = (!empty($cachedPagesMostViewed->get()))?$cachedPagesMostViewed->get():null;
        $topRegions = (!empty($cachedTopRegions->get()))?$cachedTopRegions->get():null;

        if(!isset($reportLastWeek) && !isset($visitsPerDay) && !isset($pagesMostViewed) && !isset($topRegions)) {

            $client = new Google_Client();
            $client->setApplicationName("Analytics Reporting");
            $client->setAuthConfig($this->get('kernel')->getRootDir() . '/' . $this->_analyticsKeyFile);
            $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
            $analytics = new Google_Service_Analytics($client);


            $reportLastWeek = $analytics->data_ga->get(
                'ga:183255002',
                '7daysAgo',
                'today',
                'ga:sessions, ga:bounceRate, ga:avgTimeOnPage, ga:pageviewsPerSession, ga:percentNewVisits, ga:pageviews')->getRows()[0];

            $visitsPerDay = $analytics->data_ga->get(
                'ga:183255002',
                '30daysAgo',
                'today',
                'ga:sessions',
                ['dimensions' => 'ga:date, ga:day, ga:month, ga:year'])->getRows();

            $pagesMostViewed = $analytics->data_ga->get(
                'ga:183255002',
                '30daysAgo',
                'today',
                'ga:pageviews',
                ['dimensions' => 'ga:pageTitle', 'sort' => '-ga:pageviews'])->getRows();

            $topRegions = $analytics->data_ga->get(
                'ga:183255002',
                '30daysAgo',
                'today',
                'ga:pageviews',
                ['dimensions' => 'ga:region, ga:country', 'sort' => '-ga:pageviews'])->getRows();


            $cachedReportLastWeek->set($reportLastWeek);
            $cachedVisitsPerDay->set($visitsPerDay);
            $cachedPagesMostViewed->set($pagesMostViewed);
            $cachedTopRegions->set($topRegions);

            $cachedReportLastWeek->expiresAfter(60 * 60 * 12);
            $cachedVisitsPerDay->expiresAfter(60 * 60 * 12);
            $cachedPagesMostViewed->expiresAfter(60 * 60 * 12);
            $cachedTopRegions->expiresAfter(60 * 60 * 12);

            $this->_cache->save($cachedReportLastWeek);
            $this->_cache->save($cachedVisitsPerDay);
            $this->_cache->save($cachedPagesMostViewed);
            $this->_cache->save($cachedTopRegions);

        }

        return $this->render('admin/dashboard/index.html.twig', array(
            'stats' => [
                'pixies' => $totalPixies,
                'users' => $totalUsers,
                'cards' => $totalCards,
            ],
            'content' => [
                'cardsPending' => $cardsPending
            ],
            'analytics' => [
                'lastWeek' => $reportLastWeek,
                'visitsPerDay' => $visitsPerDay,
                'pagesMostViewed' => $pagesMostViewed,
                'topRegions' => $topRegions
            ]
        ));
    }


}