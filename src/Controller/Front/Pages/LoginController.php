<?php

namespace App\Controller\Front\Pages;

use App\Constant\AfterLoginAction;
use App\Constant\SessionName;
use App\Entity\Client;
use App\Entity\Page;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @Route("/connexion", name="front_")
 */

class LoginController extends Controller
{

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    //-----------------------------------------------------------------
    // BY EMAIL
    //-----------------------------------------------------------------

    /**
     * @Route("", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {

        //------------------------------------
        // Save action for after login
        //------------------------------------

        $session = $request->getSession();

        // Reset actual values
        $session->remove(SessionName::AFTER_LOGIN_ACTION_NAME);
        $session->remove(SessionName::AFTER_LOGIN_ACTION_NAME);

        if($session->has('login_by')){
            return $this->redirect('/');
        }


        // Retrieve last visited page
        $referer = $request->headers->get("referer");
        if(
            $referer && strtolower(parse_url($referer, PHP_URL_HOST)) === strtolower($request->getHost())
            && $referer !== $this->generateUrl("front_login", [], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_forgot_password", [], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_pixie_register", [], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_pixie_register_mode", [], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_user_register", [], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_user_register_mode", [], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_login_facebook", ["type" => "user"], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_login_facebook", ["type" => "pixie"], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_login_facebook_check", [], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_login_google", ["type" => "user"], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_login_google", ["type" => "pixie"], UrlGeneratorInterface::ABSOLUTE_URL)
            && $referer !== $this->generateUrl("front_login_google_check", [], UrlGeneratorInterface::ABSOLUTE_URL)
        ){
            $session->set(SessionName::LOGIN_REDIRECT, $referer);

        }else{

            $referer = $session->get(SessionName::LOGIN_REDIRECT)?$session->get(SessionName::LOGIN_REDIRECT):null;

        }

        $addFavoritePixie = $request->query->get("addFavoritePixie");
        if($addFavoritePixie){
            $session->set(SessionName::AFTER_LOGIN_ACTION_NAME, AfterLoginAction::ADD_FAVORITE_PIXIE);
            $session->set(SessionName::AFTER_LOGIN_ACTION_VALUE, $addFavoritePixie);
        }

        $addFavoriteCard = $request->query->get("addFavoriteCard");
        if($addFavoriteCard){
            $session->set(SessionName::AFTER_LOGIN_ACTION_NAME, AfterLoginAction::ADD_FAVORITE_CARD);
            $session->set(SessionName::AFTER_LOGIN_ACTION_VALUE, $addFavoriteCard);
        }

        $addLikeCard = $request->query->get("addLikeCard");
        if($addLikeCard){
            $session->set(SessionName::AFTER_LOGIN_ACTION_NAME, AfterLoginAction::ADD_LIKE_CARD);
            $session->set(SessionName::AFTER_LOGIN_ACTION_VALUE, $addLikeCard);
        }

        //------------------------------------
        // Login

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        $page = new Page();
        $page->setIndexed(false);

        return $this->render('front/account/login.html.twig', array(
            'page' => $page,
            'last_username' => $lastUsername,
            'error'         => $error,
            'redirect'      => $referer,
        ));
    }

    /**
     * @Route("/mot-de-passe", name="forgot_password")
     */
    public function password(Request $request, UserRepository $userRepo, Mailer $mailer)
    {
        $email = $request->request->get("email", null);
        $error = false;
        $success = false;

        $page = new Page();
        $page->setIndexed(false);

        if(isset($email)){
            $user = $userRepo->findOneBy(["email" => $email]);

            if($user){

                //----------------------------------------
                // Generate new password token

                $token = md5(random_bytes(10));
                $user->setResetPasswordToken($token);
                $user->setResetPasswordTokenExpire(new DateTime('+1 day'));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                //----------------------------------------
                // Send reset password email

                $mailer->send($email, 'Réinitialisation de votre mot de passe', 'emails/forgot-password.html.twig', [
                    'token' => $token
                ]);

                $success = true;
            }
            else{

                $error = "L'email indiquée est introuvable.";

            }
        }
        //

        return $this->render('front/account/forgot-password.html.twig', array(
            'page' => $page,
            'error' => $error,
            'success' => $success,
        ));
    }

    /**
     * @Route("/reset-password", name="change_password")
     */
    public function changePassword(Request $request, UserRepository $userRepo, UserPasswordEncoderInterface $passwordEncoder)
    {
        $expired = false;
        $error = false;
        $success = false;

        $token = $request->query->get('token', null);
        if($token){
            $user = $userRepo->createQueryBuilder("u")
                ->select("u")
                ->where("u.resetPasswordToken = :token")->setParameter("token", $token)
                ->andWhere("u.resetPasswordTokenExpire > :now")->setParameter("now", new DateTime())
                ->getQuery()
                ->getOneOrNullResult();

            if($user){

                $password = $request->request->get("password", null);
                $confirm = $request->request->get("confirm", null);

                if($password && $confirm){
                    if($password === $confirm){

                        $password = $passwordEncoder->encodePassword($user, $password);
                        $user->setPassword($password);
                        $user->setResetPasswordToken("");
                        $user->setResetPasswordTokenExpire(null);

                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();

                        $success = true;

                    }
                    else{
                        $error = "les deux mots de passe ne sont pas identiques.";
                    }

                }

            }
            else{
                $expired = true;
            }
        }
        else{
            $expired = true;
        }

        $page = new Page();
        $page->setIndexed(false);


        return $this->render('front/account/reset-password.html.twig', array(
            'page' => $page,
            'error' => $error,
            'success' => $success,
            'expired' => $expired,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }


    //-----------------------------------------------------------------
    // WITH GOOGLE
    //-----------------------------------------------------------------

    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/google/type/{type}", name="login_google")
     */
    public function googleLogin(Request $request)
    {
        $type = $request->attributes->get("type");
        switch($type){
            case "pixie": $registerPath = "front_pixie_register"; break;
            default: $registerPath = "front_user_register"; break;
        }

        $session = $request->getSession();
        $session->set(SessionName::LOGIN_REDIRECT_REGISTER, $registerPath);

        // will redirect to Google!
        return $this->get('oauth2.registry')
            ->getClient('google') // key used in config.yml
            ->redirect();
    }

    /**
     * After going to Google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config.yml
     *
     * @Route("/google/check", name="login_google_check")
     */
    public function googleLoginCheck(Request $request)
    {
        $session = $request->getSession();
        return ($session->get(SessionName::LOGIN_REDIRECT))?$this->redirect($session->get(SessionName::LOGIN_REDIRECT)):$this->redirectToRoute('front_homepage_index');
    }


    //-----------------------------------------------------------------
    // WITH FACEBOOK
    //-----------------------------------------------------------------

    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/facebook/type/{type}", name="login_facebook")
     */
    public function facebookLogin(Request $request)
    {
        $type = $request->attributes->get("type");
        switch($type){
            case "pixie": $registerPath = "front_pixie_register"; break;
            default: $registerPath = "front_user_register"; break;
        }

        $session = $request->getSession();
        $session->set(SessionName::LOGIN_REDIRECT_REGISTER, $registerPath);

        // will redirect to Facebook!
        return $this->get('oauth2.registry')
            ->getClient('facebook') // key used in config.yml
            ->redirect();
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config.yml
     *
     * @Route("/facebook/check", name="login_facebook_check")
     */
    public function facebookLoginCheck(Request $request)
    {
        $session = $request->getSession();
        return ($session->get(SessionName::LOGIN_REDIRECT))?$this->redirect($session->get(SessionName::LOGIN_REDIRECT)):$this->redirectToRoute('front_homepage_index');
    }

    /**
     * @Route("/signout", name="signout")
     */
    public function manualLougout()
    {
        // $response = new Response();
        // $response->headers->clearCookie('PHPSESSID');
        // $date = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime(new date())));
        // $date = new DateTime();
        
        // $response->headers->setExpires($date->modify('-1 days')->format('Y-m-d H:i:s'));
        // $response->send();

        $user = $this->getUser();
        if($user instanceof User){
            if($user->getPixie())
            {
                $user->setViewMode('pixie');
            }
        }


        // Save the user
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->session->remove('login_by');

        return $this->redirectToRoute('front_logout');
    }


    /**
     * @Route("/{id}/auto-login", name="auto_login")
     */
    public function autoLogin(User $user){

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->container->get('security.token_storage')->setToken($token);
        $this->container->get('session')->set('_security_main_area', serialize($token));

        $this->session->set('login_by',['type' => 'login_cm','entity' => $user,'image' => $user->getAvatar()->getName(),'view_mode' => $user->getViewMode()]);

        return $this->redirect('/');

    }

    /**
     * @Route("/{id}/auto-login-client", name="auto_login_client")
     */
    public function clientAutoLogin(Client $client,Request $request){

        $this->session->set('login_by',['type' => 'login_client','entity' => $client]);

        $token = new UsernamePasswordToken($client, null, 'main', $client->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
        $this->container->get('session')->set('_security_client_area', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

        return $this->redirect('/');

    }

    /**
     * @Route("/check-user",name="_check_user");
     */
    public function checkUser(Request $request, UserRepository $userRepo, ClientRepository $clientRepo)
    {
        $user = $userRepo->findOneBy([
            'email' => $request->get('email')
        ]);

        if(is_null($user))
        {
            $client = $clientRepo->findOneBy([
                'email' => $request->get('email')
            ]);

            if(is_null($client))
            {
                return new JsonResponse(['success' => false]);
            }

            return new JsonResponse(['success' => true, 'url' => $this->generateUrl('b2b_client_login')]);
        }

        return new JsonResponse((['success' => true, 'url' => $this->generateUrl('front_login')]));
    }
}