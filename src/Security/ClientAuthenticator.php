<?php

namespace App\Security;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class ClientAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return ('front_login' === $request->attributes->get('_route') || 'b2b_client_login' === $request->attributes->get('_route')) && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $credentials = [
            'email' => $data['_username'],
            'password' => $data['_password'],
            'csrf_token' => $data['_csrf_token'],
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
//        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
//        if (!$this->csrfTokenManager->isTokenValid($token)) {
//            throw new InvalidCsrfTokenException();
//        }

        $user = $this->entityManager->getRepository(Client::class)->findOneBy(['email' => $credentials['email']]);
        if (!$user || is_null($user)) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Identifiants invalides.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Check the user's password or other credentials and return true or false
        // If there are no credentials to check, you can just return true
        if($user->getDeleted() == 1)
        {
            $ex = new DisabledException('Client account is disabled.');
            $ex->setUser($user);
            throw $ex;
        }

        if(!$this->passwordEncoder->isPasswordValid($user, $credentials['password']))
        {
            $ex = new DisabledException('Identifiants invalides.');
            $ex->setUser($user);
            throw $ex;
        }
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

        // if ($request->getSession()->has('chosen_pack_url')){

        //     $targetPath = $request->getSession()->get('chosen_pack_url');
        //     return new RedirectResponse($targetPath);

        // }elseif($targetPath = $this->getTargetPath($request->getSession(), $providerKey)){
        //     return new RedirectResponse($targetPath);
        // }


        // For example : return new RedirectResponse($this->router->generate('some_route'));
        return new JsonResponse(['success' => true, 'url' => $this->router->generate('b2b_client_main_index')]);
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {

        return new JsonResponse(['success' => false,'message' => $exception->getMessage()]);
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('front_login');
    }
}
