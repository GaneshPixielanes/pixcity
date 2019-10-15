<?php

namespace App\Controller\Admin\Pages;

use App\Constant\ViewMode;
use App\Entity\Card;
use App\Entity\User;
use App\Entity\UserMedia;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\MangoPayService;
use MangoPay\UserNatural;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/users", name="admin_users_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class UsersController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(UserRepository $users)
    {
        $list = $users->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/users/index.html.twig', ['list' => $list]);
    }


    /**
     * @Route("/ajax", name="ajax")
     * @Method({"GET", "POST"})
     */
    public function ajax(Request $request, UserRepository $usersRepo)
    {
        $columns = ["id", "firstname", "lastname", "email", "roles", "createdAt","registrationCheck", "actions","Auto-login"];

        $orders = $request->query->get("order");
        $orderBy = [];

        foreach($orders as $order){
            switch($columns[$order["column"]]){
                case "id":
                case "firstname":
                case "lastname":
                case "email":
                case "roles":
//                case "Auto-login":
                case "createdAt":
                    $orderBy[] = [$columns[$order["column"]], $order["dir"]];
                    break;
                case "registrationCheck":
                default:
                    $orderBy[] = ["id", "DESC"];
            }
        }

        $searchText = $request->query->get("search");

        $start = $request->query->get("start", 0);
        $length = $request->query->get("length", 0);
        $page = (($start > 0)?$start / $length:0)+1;

        $filters = [];
        if($searchText && $searchText["value"]){
            $filters["text"] = $searchText["value"];
        }

        $users = $usersRepo->searchUsers($filters, $page, $length, $orderBy);
        $total = $usersRepo->countUsers($filters);

        $json = [
            "draw" => intval($request->query->get('draw')),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => []
        ];
        foreach($users as $user){
//            dd();
            if($user->getUserRegistrationCheck() == null)
            {
                $userType = "Naturel";
            }
            else
            {
                if($user->getUserRegistrationCheck()->getManualRegistration() == null)
                {
                    $userType = "Naturel";
                }
                else
                {
                    $userType = "Demarchage";
                }
            }
            $json["data"][] = [
                "id" => $user->getId(),
                "firstname" => $user->getFirstname(),
                "lastname" => $user->getLastname(),
                "email" => $this->render('admin/users/columns/email.html.twig', ['item' => $user])->getContent(),
                "roles" => $this->render('admin/users/columns/roles.html.twig', ['item' => $user])->getContent(),
                "created_at" => $this->render('admin/users/columns/createdAt.html.twig', ['item' => $user])->getContent(),
                "visible" => $this->render('admin/users/columns/visible.html.twig', ['item' => $user])->getContent(),
//                "Auto-login" => $this->render('admin/users/columns/autologin.html.twig', ['item' => $user])->getContent(),
                "actions" => $this->render('admin/users/columns/actions.html.twig', ['item' => $user])->getContent(),
                "userRegistrationCheck" => $userType,
                "deleted" => $user->getDeleted(),
            ];
        }

        return new JsonResponse($json);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader,AuthorizationCheckerInterface $authChecker)
    {
        $user = new User();
        $userLogged = $this->getUser();
        $roleSet = 'b2c';
        if($userLogged->getViewMode() == ViewMode::B2B) {
            if ($authChecker->isGranted('ROLE_B2C')) {
                $roleSet = 'b2b';
            }
        }else{
            $roleSet = 'b2c';
        }


        $form = $this->createForm(UserType::class, $user,["type"=>"editFromAdmin","roleSet"=>$roleSet]);

        //-------------------------------------------------------
        // User it's not a Pixie, remove the datas

        $form->handleRequest($request);

        if(!in_array("ROLE_PIXIE", $user->getRoles())){
            $user->setPixie(null);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            // Encode the password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // Upload RIB file
            if($user->getPixie() && $user->getPixie()->getBilling()) {
                if ($user->getPixie()->getBilling()->getRib() instanceof UploadedFile) {
                    $file = $user->getPixie()->getBilling()->getRib();
                    $fileName = $fileUploader->upload($file, "rib");
                    $user->getPixie()->getBilling()->setRib($fileName);
                }
            }
            /************ACTIVE CODE TO B2B APPROVAL AND REJECT DATE**********************************/
            $date = new \DateTime();
            if($userLogged->getViewMode() == ViewMode::B2B) {
                if ($authChecker->isGranted('ROLE_B2C')) {
                    if ($user->getB2bCmApproval() == 1) {
                        //ACTIVATED DATE
                        $user->setCmApprovalDate($date);
                        $user->setCmUpgradeB2bDate($date);
                    }
                    elseif ($user->getB2bCmApproval() == 0){
                        //REJECTED DATE
                        $user->setCmRejectedDate($date);
                        $user->setCmUpgradeB2bDate($date);
                    }
                    else{
                        // ON WAITING
                        $user->setCmUpgradeB2bDate($date);
                    }
                }
            }
            /************END CODE TO TO B2B APPROVAL AND REJECT DATE**********************************/

            // Save the user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_users_list');
        }


        return $this->render('admin/users/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, User $editedUser, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader,AuthorizationCheckerInterface $authChecker,MangoPayService $mangoPayService)
    {

        //-------------------------------------------------------
        // PIXIE RIB
        // Create a file from the string stored in database
        $date = new \DateTime();
        if($editedUser->getPixie() && $editedUser->getPixie()->getBilling()) {
            $previousRib = $editedUser->getPixie()->getBilling()->getRib();
            if ($previousRib) {
                $editedUser->getPixie()->getBilling()->setRib(
                    new File($fileUploader->getUploadDirectory() . "/rib/" . $previousRib)
                );
            }
        }

        $user = $this->getUser();
        $roleSet = 'b2c';
        if($user->getViewMode() == ViewMode::B2B) {
            if ($authChecker->isGranted('ROLE_B2C')) {
                $roleSet = 'b2b';
            }
        }else{
            $roleSet = 'b2c';
        }
        $mangoPayFilePrevName = $editedUser->getMangopayKycFile();
        $form = $this->createForm(UserType::class, $editedUser, ["type"=>"editFromAdmin","roleSet"=>$roleSet]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //-------------------------------------------------------
            // User it's not a Pixie, remove the datas

            if(!in_array("ROLE_PIXIE", $editedUser->getRoles())){
                $editedUser->setLinks(null);
                $editedUser->setPixie(null);
            }


            // Encode the password
            if($editedUser->getPlainPassword()) {
                $password = $passwordEncoder->encodePassword($editedUser, $editedUser->getPlainPassword());
                $editedUser->setPassword($password);
            }

            // Upload RIB file
            if($editedUser->getPixie() && $editedUser->getPixie()->getBilling()) {
                if ($editedUser->getPixie()->getBilling()->getRib() instanceof UploadedFile) {
                    $file = $editedUser->getPixie()->getBilling()->getRib();
                    $fileName = $fileUploader->upload($file, "rib");
                    $editedUser->getPixie()->getBilling()->setRib($fileName);
                } else {
                    if (isset($previousRib))
                        $editedUser->getPixie()->getBilling()->setRib($previousRib);
                }
            }
            /************ACTIVE CODE TO B2B APPROVAL AND REJECT DATE**********************************/
            if($user->getViewMode() == ViewMode::B2B) {
                if ($authChecker->isGranted('ROLE_B2C')) {
                    if ($editedUser->getB2bCmApproval() == 1) {
                        //ACTIVATED DATE
                        $editedUser->setCmApprovalDate($date);
                    }
                    elseif ($editedUser->getB2bCmApproval() == 0){
                        //REJECTED DATE
                        $editedUser->setCmRejectedDate($date);
                    }
                    else{
                        // ON WAITING
                    }
                }
            }
            /************END CODE TO TO B2B APPROVAL AND REJECT DATE**********************************/

            /************ACTIVE CODE TO CREATE MANGOPAY  AND KYC**********************************/
//            if($user->getViewMode() == ViewMode::B2B) {
//                if ($authChecker->isGranted('ROLE_B2C')) {
//                    if($editedUser->getB2bCmApproval() == 1) {
//                        // Create a mango pay user
//                        if($editedUser->getMangopayUserId() == null){
//                            $mangoUser = new UserNatural();
//
//                            $mangoUser->PersonType = "NATURAL";
//                            $mangoUser->FirstName = $editedUser->getFirstname();
//                            $mangoUser->LastName = $editedUser->getLastname();
//                            $mangoUser->Birthday = 1409735187;
//                            $mangoUser->Nationality = "FR";
//                            $mangoUser->CountryOfResidence = "FR";
//                            $mangoUser->Email = $editedUser->getEmail();
//                            $mangoUser = $mangoPayService->createUser($mangoUser);
//                            //Create a wallet
//                            $wallet = $mangoPayService->getWallet($mangoUser->Id);
//
//                            $editedUser->setMangopayUserId($mangoUser->Id);
//                            $editedUser->setMangopayWalletId($wallet->Id);
//                            $editedUser->setMangopayCreatedAt(new \DateTime());
//
//                        }
//                        if($editedUser->getMangopayKycFile() == null){
//                            $editedUser->setMangopayKycStatus('PENDING');
//                        }
//                        //FILE RENAME AS PER DATE
//                        if($mangoPayFilePrevName != null){
//                            $mangopayKycFileName = 'uploads/mangopay_kyc/cm/'.$editedUser->getId().'/addr1/'.$mangoPayFilePrevName;
//                            $ext = pathinfo('uploads/mangopay_kyc/cm/'.$editedUser->getId().'/addr1/'.$mangoPayFilePrevName,PATHINFO_EXTENSION);
//                            rename($mangopayKycFileName,preg_replace('/\\.[^.\\s]{3,4}$/', '', $mangopayKycFileName).'_'.$date->format('Ymd').'.'.$ext);
//                        }
//
//                        if($editedUser->getMangopayKycFile() != null){
//                            $editedUser->setMangopayKycStatus('WAITING_FOR_SUBMISSION');
//                            $editedUser->setMangopayKycCreated(new \DateTime());
//                        }
//                    }
//                }
//
//            }


            // Save the user
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');
            //return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/users/form.html.twig', [
            'user' => $editedUser,
            'form' => $form->createView(),
        ]);
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
    
    /**
     * SOFT DELETE
     * @Route("/{id}/soft-delete", name="soft_delete")
     * @Method("POST")
     */
    public function soft_delete(Request $request, User $user)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_users_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $user->setDeleted(true);

            foreach($user->getCards() as $card){
                if($card instanceof Card) {
                    $card->setDeleted(true);
                }
            }

            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_users_list');
    }

    /**
     * RESTORE FROM SOFT DELETE
     * @Route("/{id}/restore-soft-delete", name="restore_soft_delete")
     * @Method("POST")
     */
    public function restore_soft_delete(Request $request, User $user)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_users_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $user->setDeleted(false);

            foreach($user->getCards() as $card){
                if($card instanceof Card) {
                    $card->setDeleted(false);
                }
            }

            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }


        return $this->redirectToRoute('admin_users_list');
    }


    /**
     * HARD DELETE
     * @Route("/{id}/hard-delete", name="hard_delete")
     * @Method("POST")
     */
    public function hard_delete(Request $request, User $user)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_users_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();

            $em->remove($user);

            /*foreach($user->getCards() as $card){
                if($card instanceof Card) {
                    $em->remove($card);
                }
            }*/

            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_users_list');
    }

//    /**
//     * @Route("upload/mangopaykyc", name="mangopaykyc")
//     */
//    public function uploadMangopaykyc(Request $request, FileUploader $fileUploader)
//    {
//        $file = $request->files->get('file');
//        $fileName = $fileUploader->upload($file, 'mangopay_kyc/cm/'.$request->get('id').'/addr1/', true);
//        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
//    }
//    /**
//     * @Route("upload/mangopayKycAddr", name="mangopayKycAddr")
//     */
//    public function uploadMangopayAddr(Request $request, FileUploader $fileUploader)
//    {
//        $file = $request->files->get('file');
//        $fileName = $fileUploader->upload($file, 'mangopay_kyc/cm/'.$request->get('id').'/addr2/', true);
//        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
//    }
//    /**
//     * @Route("download/{id}/{fldName}",name="download")
//     */
//    public function download($id,$fldName, UserRepository $userRepository)
//    {
//        $userTbl = $userRepository->find($id);
//        $folderName = $fldName;
//        $kycFile = '';
//        if($folderName == 'addr1'){
//            $kycFile = $userTbl->getMangopayKycFile();
//        }else{
//            $kycFile = $userTbl->getMangopayKycAddr();
//        }
//
//        $date = new \DateTime();
//        $response = new BinaryFileResponse('uploads/mangopay_kyc/cm/'.$userTbl->getId().'/'.$folderName.'/'.$kycFile);
//      //  $ext = pathinfo('uploads/mangopay_kyc/cm/'.$userTbl->getId().'/addr1/'.$userTbl->getMangopayKycFile(),PATHINFO_EXTENSION);
//
//        $response->headers->set('Content-Type','text/plain');
//        $response->setContentDisposition(
//            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
//            //$kycFile.'_'.$date->format('Ymd').'.'.$ext
//            $kycFile
//        );
//
//        return $response;
//
//    }
}