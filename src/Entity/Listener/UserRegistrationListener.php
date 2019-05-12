<?php

namespace App\Entity\Listener;

use App\Entity\User;
use App\Entity\UserInstagramDetailsApi;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

class UserRegistrationListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postFlush(User $user, LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();

        $uow = $em->getUnitOfWork();

        $newUser = $uow->getOriginalEntityData($user);

        $user = $this->tokenStorage->getToken()->getUser();
        $user->setIgFlag(3);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if(!is_null($newUser->getInstaram()) && isset(explode('/', $newUser->getInstaram())[3]))
        {
            $instagram = explode('/', $newUser->getInstaram())[3];
            $details = $this->_extractInstagramUserDetails($this->_getInstagramInfo($instagram));

            #Store IG details
            $instagramDetails = new UserInstagramDetailsApi();

            #Update the instagram information to be shown to the users
//            $instagramDetails->setNoOfFollowed($details['following']);
//            $instagramDetails->setNoOfFollowers($details['followers']);
//            $instagramDetails->setNoOfPosts((int)$details['posts']);
//            $instagramDetails->setName($details['user']);
//            $instagramDetails->setUser($newUser);
//            $instagramDetails->setResponse(json_encode($details));
//            $instagramDetails->setCreatedAt(new \DateTime());

        }
    }

    private function _getInstagramInfo($instagram)
    {

        $ch = curl_init();
        $data = ['social_media' => ['users' => '#'.$instagram]];
        $url = "http://172.104.240.209:4000/apis/social_medias/instagram_user_from_id";
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:') );
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function _extractInstagramUserDetails($details)
    {
        $details = stripslashes($details);
        $details = str_replace('{data: ["','',$details);
        $details = str_replace('"]}','',$details);
        $details = json_decode($details,true);

        return $details;
    }
}