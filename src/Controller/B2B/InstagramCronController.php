<?php

namespace App\Controller\B2B;

use App\Entity\UserInstagramDetailsApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
set_time_limit(0);
/**
 * @Route("instagram/cron", name="b2b_instagram_")
 */
class InstagramCronController extends Controller
{

    /**
     * @Route("/process-cron-instagram/", name="process_insta")
     */
    public function cronInstagramProcess()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT g
                    FROM App:UserLink AS g
                    INNER JOIN App:UserInstagramDetailsApi as uig WITH g.user = uig.user
                    WHERE g.type='instagram' AND uig.processed = 0 ORDER BY g.id ASC");
        $query->setMaxResults(2);
        $result =  $query->getResult();

        foreach ($result as &$value)
        {
            $parts = parse_url($value->getUrl());
            if(isset($parts['path'])) {
                dd($parts['path']);
                $userNameId = str_replace("/", "", $parts['path']);
                $curl = curl_init('https://www.instagram.com/' . trim($userNameId) . '/');

                $filename = "profile_" . trim($userNameId);
                $this->curlFunction($curl, $filename);

                $profileDataArr = $this->scrape_insta($filename);

                if ($profileDataArr != "") {
                    $full_name = $profileDataArr['entry_data']['ProfilePage'][0]['graphql']['user']['full_name'];
                    $followers = $profileDataArr['entry_data']['ProfilePage'][0]['graphql']['user']['edge_followed_by']['count'];
                    $following = $profileDataArr['entry_data']['ProfilePage'][0]['graphql']['user']['edge_follow']['count'];
                    $totalPost = $profileDataArr['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['count'];

                    //
                    //$userInstagram = $em->createQuery("SELECT uida FROM App:UserInstagramDetailsApi AS uida WHERE uida.user= ".$value->getUser());
                    $userInstagramTbl = $this->getDoctrine()->getRepository(UserInstagramDetailsApi::class);
                    $userInstagram = $userInstagramTbl->findOneBy(['user' => $value->getUser()]);

                    if($userInstagram == null){
                        $userInstagram = new UserInstagramDetailsApi();
                        $userInstagram->setNoOfPosts($totalPost);
                        $userInstagram->setNoOfFollowers($followers);
                        $userInstagram->setNoOfFollowed($following);
                        $userInstagram->setUpdatedAt(new \DateTime());
                        $userInstagram->setName($full_name);
                        $userInstagram->setProcessed(1);
                    }else{
                        $userInstagram->setNoOfPosts($totalPost);
                        $userInstagram->setNoOfFollowers($followers);
                        $userInstagram->setNoOfFollowed($following);
                        $userInstagram->setUpdatedAt(new \DateTime());
                        $userInstagram->setName($full_name);
                        $userInstagram->setProcessed(1);
                    }
                    $em->persist($userInstagram);
                    $em->flush();
                }
            }
        }

        return JsonResponse::create(['success' => true]);
    }
    public function curlFunction($curl,$filename){
        $fp = fopen($filename.'.html', 'w+');
        curl_setopt($curl,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_FILE, $fp);

        curl_setopt($curl, CURLOPT_PROXY, 'http://zproxy.lum-superproxy.io:22225');
        curl_setopt($curl, CURLOPT_PROXYUSERPWD, 'lum-customer-hl_2a132b80-zone-zone1:4lljv0g38qv2');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $curl_scraped_page=curl_exec($curl);
        fclose($fp);
        curl_close($curl);
        //echo $curl_scraped_page
    }

    //returns a big old hunk of JSON from a non-private IG account page.
    function scrape_insta($filename) {
        $insta_source = file_get_contents($filename.".html");
        $shards = explode('window._sharedData = ', $insta_source);
        if(! isset($shards[1])){
            return "";
        }else {
            $insta_json = explode(';</script>', $shards[1]);
            $insta_array = json_decode($insta_json[0], TRUE);
            return $insta_array;
        }
    }
}
