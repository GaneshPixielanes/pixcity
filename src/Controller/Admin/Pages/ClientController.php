<?php

namespace App\Controller\Admin\Pages;

use App\Constant\ViewMode;
use App\Entity\Client;
use App\Entity\Option;
use App\Form\ClientType;
use App\Repository\ClientInfoRepository;
use App\Repository\ClientRepository;
use App\Service\FileUploader;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/client", name="admin_client_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class ClientController extends AbstractController
{
    /**
     * @Route("", name="index")
     * @Method({"GET"})
     */
    public function index(ClientRepository $clientRepository,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $em= $this->getDoctrine()->getManager();
                $query = $em->createQuery('SELECT pbc, COUNT(pbum.client) as missionCount FROM App:Client pbc LEFT JOIN App:UserMission pbum WITH pbum.client = pbc.id WHERE pbc.deleted IS Null OR pbc.deleted = 0 GROUP BY pbc.id ORDER BY pbc.id DESC');
                $result =  $query->getResult();

                return $this->render('admin/b2b/client/index.html.twig', [
//                    'clients' => $clientRepository->findBy(['deleted'=>null]),
                   'clients' => $result,
                ]);
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePhoto = $form['profilePhoto']->getData();
            if ($profilePhoto) {
                $originalFilename = pathinfo($profilePhoto->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$profilePhoto->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $profilePhoto->move(
                        'uploads/clients/',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $client->setProfilePhoto($newFilename);
            }


        if($client->getPlainPassword()) {
                $password = $passwordEncoder->encodePassword($client, $client->getPlainPassword());
                $client->setPassword($password);
                $client->setRoles(['ROLE_USER']);
            }
            if($client->getClientInfo()->getMangopayKycFile() == null){
                $client->getClientInfo()->setMangopayKycStatus('PENDING');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            $uploadedFile = $client->getProfilePhoto();

            if ($uploadedFile) {
                $srcPath = 'uploads/clients/'.$uploadedFile;
                $path = 'uploads/clients/'.$client->getId().'/';
                if (!file_exists($path)) {
                    mkdir($path, 0700);
                }

                rename($srcPath, 'uploads/clients/'.$client->getId().'/' . pathinfo($uploadedFile, PATHINFO_BASENAME));
            }
            return $this->redirectToRoute('admin_client_index');
        }

        return $this->render('admin/b2b/client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Client $client): Response
    {
        return $this->render('admin/b2b/client/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $client, UserPasswordEncoderInterface $passwordEncoder,AuthorizationCheckerInterface $authChecker): Response
    {
        $date = new \DateTime();
        $user = $this->getUser();
        $mangoPayFilePrevName = $client->getClientInfo()->getMangopayKycFile();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $form = $this->createForm(ClientType::class, $client);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    if($client->getPlainPassword()) {
                        $password = $passwordEncoder->encodePassword($client, $client->getPlainPassword());
                        $client->setPassword($password);
                    }
                    if($client->getClientInfo()->getMangopayKycFile() == null){
                        $client->getClientInfo()->setMangopayKycStatus('PENDING');
                    }
                    $profilePhoto = $form['profilePhoto']->getData();
                    if ($profilePhoto) {
                        $originalFilename = pathinfo($profilePhoto->getClientOriginalName(), PATHINFO_FILENAME);
                        $newFilename = $originalFilename.'-'.uniqid().'.'.$profilePhoto->guessExtension();

                        // Move the file to the directory where brochures are stored
                        try {
                            $profilePhoto->move(
                                'uploads/clients/'.$client->getId().'/',
                                $newFilename
                            );
                        } catch (FileException $e) {
                            // ... handle exception if something happens during file upload
                        }
                        $client->setProfilePhoto($newFilename);
                    }
                    /*******FILE RENAME START************/
//                    if($mangoPayFilePrevName != null){
//                        $mangopayKycFileName = 'uploads/mangopay_kyc/client/'.$client->getId().'/addr1/'.$mangoPayFilePrevName;
//                        $ext = pathinfo('uploads/mangopay_kyc/client/'.$client->getId().'/addr1/'.$mangoPayFilePrevName,PATHINFO_EXTENSION);
//                        rename($mangopayKycFileName,preg_replace('/\\.[^.\\s]{3,4}$/', '', $mangopayKycFileName).'_'.$date->format('Ymd').'.'.$ext);
//                        $client->getClientInfo()->setMangopayKycStatus('WAITING_FOR_SUBMISSION');
//                        $client->getClientInfo()->setMangopayKycCreated(new \DateTime());
//                    }
                    /*******FILE RENAME END************/
//                    $uploadedFile = $form['profilePhoto']->getData();
//
//                    $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
//                    if ($uploadedFile) {
//
//                        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
//                        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
//                        $uploadedFile->move(
//                            $destination,
//                            $newFilename
//                        );
//
//
//                        // instead of its contents
//                        $client->setProfilePhoto($newFilename);
//                    }
                    $this->getDoctrine()->getManager()->flush();

                    return $this->redirectToRoute('admin_client_index', [
                        'id' => $client->getId(),
                    ]);
                }

                return $this->render('admin/b2b/client/edit.html.twig', [
                    'client' => $client,
                    'form' => $form->createView(),
                ]);
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Client $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
           // $entityManager->remove($client);
            $clients = $entityManager->getRepository(Client::class)->find($client->getId());
            $testAccounts = $entityManager->getRepository(Option::class)->findOneBy(['slug'=>'dev-client-email']);
            if(strpos($testAccounts->getValue(),$clients->getEmail()) !== false) { //in
                $emailStore = explode('@', $clients->getEmail());
                $emailRename = $emailStore[0] . '_' . strtotime("now") . 'del@' . $emailStore[1];
                $clients->setEmail($emailRename);
            }
            $clients->setDeleted(1);
            $clients->setDeletedAt(new \DateTime());
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_client_index');
    }

    /**
     * @Route("upload", name="upload")
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $file = $request->files->get('file');
        $fileName = $fileUploader->upload($file, 'clients/'.$request->get('id'), true);
        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
    }
//    /**
//     * @Route("upload/mangopaykyc", name="mangopaykyc")
//     */
//    public function uploadMangopaykyc(Request $request, FileUploader $fileUploader)
//    {
//        $file = $request->files->get('file');
//        $fileName = $fileUploader->upload($file, 'mangopay_kyc/client/'.$request->get('id').'/addr1/', true);
//        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
//    }
//    /**
//     * @Route("upload/mangopayKycAddr", name="mangopayKycAddr")
//     */
//    public function uploadMangopayAddr(Request $request, FileUploader $fileUploader)
//    {
//        $file = $request->files->get('file');
//        $fileName = $fileUploader->upload($file, 'mangopay_kyc/client/'.$request->get('id').'/addr2/', true);
//        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
//    }
//    /**
//     * @Route("download/{id}/{fldName}",name="download")
//     */
//    public function download($id,$fldName, ClientInfoRepository $clientInfoRepository)
//    {
//        $userTbl = $clientInfoRepository->find($id);
//        $folderName = $fldName;
//        $kycFile = '';
//        if($folderName == 'addr1'){
//            $kycFile = $userTbl->getMangopayKycFile();
//        }else{
//            $kycFile = $userTbl->getMangopayKycAddr();
//        }
//
//        $date = new \DateTime();
//        $response = new BinaryFileResponse('uploads/mangopay_kyc/client/'.$userTbl->getId().'/'.$folderName.'/'.$kycFile);
//        //  $ext = pathinfo('uploads/mangopay_kyc/cm/'.$userTbl->getId().'/addr1/'.$userTbl->getMangopayKycFile(),PATHINFO_EXTENSION);
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
