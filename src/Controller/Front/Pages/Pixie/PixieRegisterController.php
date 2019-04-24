<?php

namespace App\Controller\Front\Pages\Pixie;

use App\Constant\ViewMode;
use App\Entity\Page;
use App\Entity\User;
use App\Entity\UserMedia;
use App\Entity\UserOptin;
use App\Form\Front\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\Mailer;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @Route("/city-maker/inscription", name="front_pixie_")
 */

class PixieRegisterController extends Controller
{

    /**
     * @Route("", name="register")
     */
    public function index(Request $request, Mailer $mailer, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader)
    {
        if($this->getUser()){
            // If the user is already logged in
            return $this->redirectToRoute('front_pixie_register_user');
        }

        $user = new User();

        //-----------------------------------------------
        // Get datas if user come from social login

        $socialLoginInfos = null;

        if($request->query->get("autofill")){
            $socialLoginInfos = unserialize(urldecode($request->query->get("autofill")));

            if($socialLoginInfos instanceof GoogleUser){
                $user->setFirstname($socialLoginInfos->getFirstName());
                $user->setLastname($socialLoginInfos->getLastName());
                $user->setEmail($socialLoginInfos->getEmail());
                $user->setGoogleId($socialLoginInfos->getId());
                $user->setPlainPassword(md5(random_bytes(8)));
            }
            elseif($socialLoginInfos instanceof FacebookUser){
                $user->setFirstname($socialLoginInfos->getFirstName());
                $user->setLastname($socialLoginInfos->getLastName());
                $user->setEmail($socialLoginInfos->getEmail());
                $user->setFacebookId($socialLoginInfos->getId());
                $user->setPlainPassword(md5(random_bytes(8)));
            }
        }

        //-----------------------------------------------
        // Create the form

        $form = $this->createForm(UserType::class, $user, ["pixie" => true,"type" => ($socialLoginInfos)?"social":"classic"]);
        $form->handleRequest($request);

        //-----------------------------------------------
        // On submit

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(["ROLE_USER", "ROLE_PIXIE"]);

            // Encode the password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->setActive(false); // User will have to confirm email to active the account
            $user->setViewMode(ViewMode::PIXIE);

            if(!$user->getOptin()){
                $user->setOptin(new UserOptin());
            }

            $user->getOptin()->setPixieCardProjectReceived(true);;
            $user->getOptin()->setPixieCardStatusUpdated(true);;

            $registerToken = md5(random_bytes(10));
            $user->setRegisterToken($registerToken); // Create a token for email confirmation

            // Upload RIB file
            if($user->getPixie() && $user->getPixie()->getBilling()) {
                if ($user->getPixie()->getBilling()->getRib() instanceof UploadedFile) {
                    $file = $user->getPixie()->getBilling()->getRib();
                    $fileName = $fileUploader->upload($file, "rib");
                    $user->getPixie()->getBilling()->setRib($fileName);
                }
            }

            // Save the user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('account_creation', '');

            //----------------------------------------
            // Send validation email

            $mailer->send($user->getEmail(), 'Bienvenue dans la communauté Pix.City', 'emails/pixie-account-validation.html.twig', [
                'firstname' => $user->getFirstname(),
                'token' => $registerToken
            ]);

        }

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setIndexed(false);

        return $this->render('front/account/pixie/register.html.twig', array(
            'page' => $page,
            'form' => $form->createView(),
            'socialLogin' => $socialLoginInfos
        ));
    }


    /**
     * Register an already logged in user
     * @Route("/utilisateur", name="register_user")
     */
    public function user(Request $request, FileUploader $fileUploader)
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('front_pixie_register');
        }

        //-----------------------------------------------
        // Create the form

        $form = $this->createForm(UserType::class, $user, ["pixie" => true,"type" => "loggedin"]);
        $form->handleRequest($request);

        //-----------------------------------------------
        // On submit

        if ($form->isSubmitted() && $form->isValid()) {

            $user->addRole("ROLE_PIXIE");
            $user->setViewMode(ViewMode::PIXIE);

            // Upload RIB file
            if($user->getPixie() && $user->getPixie()->getBilling()) {
                if ($user->getPixie()->getBilling()->getRib() instanceof UploadedFile) {
                    $file = $user->getPixie()->getBilling()->getRib();
                    $fileName = $fileUploader->upload($file, "rib");
                    $user->getPixie()->getBilling()->setRib($fileName);
                }
            }

            // Save the user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Update session token with the new role
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            // Add the flash message
            $this->addFlash('account_pixie_creation', '');

        }

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setIndexed(false);

        return $this->render('front/account/pixie/register.html.twig', array(
            'page' => $page,
            'form' => $form->createView(),
            'socialLogin' => null
        ));
    }


    /**
     * @Route("/mode", name="register_mode")
     */
    public function mode(Request $request)
    {
        $page = new Page();
        $page->setIndexed(false);

        return $this->render('front/account/register-mode.html.twig', array(
            'page' => $page,
            'type' => "pixie"
        ));
    }


  /**
     * @Route("/new",name="add_new_pixie");
     */
    public function pixie(Request $request, UserRepository $userRepository, Mailer $mailer)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted())
        {

            $dupCheck = $userRepository->findBy(['email' => $form->get('email')->getData()]);
            if(!empty($dupCheck))
            {
                echo "Email is already taken";
                exit;
            }
            $registerToken = md5(random_bytes(10));
            $user->setFirstname(' ');
            $user->setLastname(' ');
            $user->setBirthdate(new \DateTime('-16 years'));
            $user->setCurrentLocation(' ');
            $user->setGender('male');
//            $user->setEmail($form->get('email'));
            $user->addRole('ROLE_PIXIE');
            $user->setCreatedAt(new \DateTime());
//            $user->setBirthLocation(0);
            $user->setVisible(0);
            $user->setViewMode(ViewMode::PIXIE);
            $user->setRegisterToken($registerToken); // Create a token for email confirmation
//            $user->setPixie($user);
//            dd($form->get('email'));

//            dd($user);
            // Save the user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Update session token with the new role
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            // Add the flash message
            $this->addFlash('account_pixie_creation', '');

            //Send mail
            $mailer->send($user->getEmail(), 'Bienvenue dans la communauté Pix.City', 'emails/pixie-account-validation.html.twig', [
                'firstname' => 'Pixie',
                'token' => $registerToken
            ]);
            // Add the flash message
            return $this->redirectToRoute('front_logout');
//            echo "User has been created";
//            exit;


        }
        return $this->render('front/account/pixie/new.html.twig',[
            'form'=> $form->createView()
        ]);

    }    

}