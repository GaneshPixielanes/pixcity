<?php

namespace App\Security;

use App\Constant\SessionName;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\HttpUtils;

class FacebookAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;
    private $httpUtils;
    private $userInfos;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router, HttpUtils $httpUtils)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->httpUtils = $httpUtils;
    }


    public function start(Request $request, AuthenticationException $authException = null){}

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'front_login_facebook_check';
    }

    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        $this->userInfos = $facebookUser;

        $email = $facebookUser->getEmail();

        //----------------------------------------------------
        // User already logged in with Facebook

        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['facebookId' => $facebookUser->getId()]);
        if ($existingUser) {
            return $existingUser;
        }

        //----------------------------------------------------
        // If the user is a client, login as a client
        $clientUser = $this->em->getRepository(Client::class)->findOneBy(['email' => $email]);
        if($clientUser)
        {
            return $clientUser;
        }    
        //----------------------------------------------------
        // User email exist - assign facebook ID

        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if($user) {
            $user->setFacebookId($facebookUser->getId());
            $this->em->persist($user);
            $this->em->flush();

            return $user;
        }


        return null;
    }

    /**
     * @return
     */
    private function getFacebookClient()
    {
        return $this->clientRegistry
            // "facebook" is the key used in config.yml
            ->getClient('facebook');
    }

    // ...

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $session = $request->getSession();

        if($exception instanceof UsernameNotFoundException && $this->userInfos){

            //----------------------------------------------------
            // If User does not exist - redirect to register

            return new RedirectResponse($this->router->generate($session->get(SessionName::LOGIN_REDIRECT_REGISTER), [
                "autofill" => urlencode(serialize($this->userInfos))
            ]));

        }
        else{
            $session->set(Security::AUTHENTICATION_ERROR, $exception);

            return new RedirectResponse($this->router->generate('front_login'));
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey){
        
        return new RedirectResponse($this->router->generate('front_homepage_check_user'));
    }

}
