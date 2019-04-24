<?php

namespace App\Controller\Front\Pages\User;

use App\Entity\Page;
use App\Entity\User;
use App\Entity\UserMedia;
use App\Form\Front\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\Mailer;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @Route("/utilisateur/inscription", name="front_user_")
 */

class UserRegisterController extends Controller
{

    /**
     * @Route("", name="register")
     */
    public function index(Request $request, Mailer $mailer, UserPasswordEncoderInterface $passwordEncoder)
    {


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
                //$user->setPlainPassword(md5(random_bytes(8)));
            }
            elseif($socialLoginInfos instanceof FacebookUser){
                $user->setFirstname($socialLoginInfos->getFirstName());
                $user->setLastname($socialLoginInfos->getLastName());
                $user->setEmail($socialLoginInfos->getEmail());
                $user->setFacebookId($socialLoginInfos->getId());
                //$user->setPlainPassword(md5(random_bytes(8)));
            }
        }

        //-----------------------------------------------
        // Create the form

        $form = $this->createForm(UserType::class, $user, ["type" => ($socialLoginInfos)?"social":"classic"]);
        $form->handleRequest($request);

        //-----------------------------------------------
        // On submit

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(["ROLE_USER"]);

            // Encode the password
            if($user->getPlainPassword()) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }

            $user->setActive(false); // User will have to confirm email to active the account

            $registerToken = md5(random_bytes(10));
            $user->setRegisterToken($registerToken); // Create a token for email confirmation

            // Save the user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('account_creation', '');

            //----------------------------------------
            // Send validation email

            $mailer->send($user->getEmail(), 'Bienvenue sur Pix.City', 'emails/user-account-validation.html.twig', [
                'firstname' => $user->getFirstname(),
                'token' => $registerToken
            ]);
        }

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setIndexed(false);

        return $this->render('front/account/user/register.html.twig', array(
            'page' => $page,
            'form' => $form->createView(),
            'socialLogin' => $socialLoginInfos,
            'type' => 'user'
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
            'type' => "user"
        ));
    }

    /**
     * @Route("/confirmation", name="register_confirm")
     */
    public function confirm(Request $request, UserRepository $userRepo)
    {
        $token = $request->query->get('token', null);
        if($token){
            $user = $userRepo->createQueryBuilder("u")
                ->select("u")
                ->where("u.registerToken = :token")->setParameter("token", $token)
                ->getQuery()
                ->getOneOrNullResult();
                // dd($user);

            if($user){
                //----------------------------------------------
                // Activate the user

                $user->setActive(true);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                //----------------------------------------------
                // Automatically log in the user

                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $this->get('session')->set('_security_main', serialize($token));
                $event = new InteractiveLoginEvent($request, $token);
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);


                //----------------------------------------------
                // Set the flash message to be displayed

                $this->addFlash('account_validation', $user->getFirstname());
            }
        }
        if($user->getPixie() != null)
        {
            return $this->redirectToRoute('front_pixie_account_cards_projects');
        }  
        if($user->getPlainPassword() == ''&& $user->getPixie() != null)
        {
            return $this->redirectToRoute('front_pixie_account_card_address');
        }      
        return $this->redirectToRoute('front_homepage_index');
    }

    /**
     * @Route("/upload-avatar", name="ajax_upload_avatar")
     * @Method({"POST"})
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $attachment = null;
        foreach ($request->files as $uploadedFile) {
            if($uploadedFile->isValid()) {
                $fileName = $fileUploader->upload($uploadedFile, UserMedia::uploadFolder(), true);
                $attachment = new UserMedia();
                $attachment->setName($fileName);
                $attachment->setUpdatedAt(new \DateTime());
            }
        }

        if($attachment){
            $response = new JsonResponse(['success' => true, 'name' => $attachment->getName(), 'url'=>$attachment->getUrl(), 'type'=>$attachment->getType()]);
        }
        else{
            $response = new JsonResponse(['error' => true], 400);
        }

        return $response;
    }


}