<?php

namespace App\Controller\Admin\Pages;

use App\Constant\ViewMode;
use App\Entity\UserPacks;
use App\Form\UserPacksType;
use App\Repository\UserPacksRepository;
use App\Service\FileUploader;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/admin/user/packs", name="admin_user_packs_")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserPacksController extends Controller
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UserPacksRepository $userPacksRepository,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $filename = 'uploads/pack/';
                return $this->render('admin/b2b/user_packs/index.html.twig', [
                    'user_packs' => $userPacksRepository->findBy(['deletedAt'=>array(null,0)],['id'=>'DESC']),
                    'filename' => $filename
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
    public function new(Request $request): Response
    {
        $userPack = new UserPacks();
        $form = $this->createForm(UserPacksType::class, $userPack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userPack);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_packs_index');
        }

        return $this->render('admin/b2b/user_packs/new.html.twig', [
            'user_pack' => $userPack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Request $request, UserPacks $userPack): Response
    {
        $userId = $request->attributes->get('id');
       // $selectedUserRelated = $userPacksRepository->findBy(['user'=>$userId]);
        return $this->render('admin/b2b/user_packs/show.html.twig', [
            'user_pack' => $userPack,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserPacks $userPack, AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $form = $this->createForm(UserPacksType::class, $userPack);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

//                    $uploadedFile = $form['bannerImage']->getData();
//                    $packPhotos = $form['packPhotos']->getData();
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
//                        $originalFilenamePhotoPacks = pathinfo($packPhotos->getClientOriginalName(), PATHINFO_FILENAME);
//                        $newFilenamePhotoPacks = Urlizer::urlize($originalFilenamePhotoPacks).'-'.uniqid().'.'.$packPhotos->guessExtension();
//                        $packPhotos->move(
//                            $destination,
//                            $newFilenamePhotoPacks
//                        );
//                        // instead of its contents
//                        $userPack->setBannerImage($newFilename);
//                        $userPack->setPackPhotos($newFilenamePhotoPacks);
//                    }

                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('admin_user_packs_index', [
                        'id' => $userPack->getId(),
                    ]);
                }

                return $this->render('admin/b2b/user_packs/edit.html.twig', [
                    'user_pack' => $userPack,
                    'form' => $form->createView(),
                ]); }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserPacks $userPack): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userPack->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->remove($userPack);
            $userPacks = $entityManager->getRepository(UserPacks::class)->find($userPack->getId());
            $userPacks->setDeleted(1);
            $userPacks->setDeletedAt(new \DateTime());
            $userPacks->setActive(0);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_packs_index');
    }
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
    /**
     * @Route("/view/{id}", name="view", methods={"GET"})
     */
    public function viewByUser(Request $request,UserPacksRepository $userPacksRepository): Response
    {
        $userId = $request->attributes->get('id');
        $selectedUserRelated = $userPacksRepository->findBy(['user'=>$userId]);


        return $this->render('admin/b2b/user_packs/index.html.twig', [
            'user_packs' => $selectedUserRelated,
        ]);
    }
    /**
     * @Route("upload", name="upload")
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $file = $request->files->get('file');
        $fileName = $fileUploader->upload($file, 'pack/banner/', true);
        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
    }
    /**
     * @Route("upload/packs", name="upload_pack")
     */
    public function uploadPacks(Request $request, FileUploader $fileUploader)
    {
        $file = $request->files->get('file');
        $fileName = $fileUploader->upload($file, 'pack/'.$request->get('id'), true);
        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
    }
    /**
     * @Route("download/{id}",name="download")
     */
    public function download($id, UserPacksRepository $documentRepo)
    {
        $document = $documentRepo->find($id);
        $packname = $document->getId();
        $date = new \DateTime();
        $filename = 'uploads/pack/'.$document->getId().'/'.$document->getId().'.pdf';
        if (!file_exists($filename)) {
            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/shared/_pack_agreement.html.twig',
                    array(
                        'pack' => $document
                    )
                ), 'uploads/pack/'.$document->getId().'/'.$document->getId().'.pdf',
                [
                    'encoding' => 'utf-8'
                ]
            );


        }
        $response = new BinaryFileResponse($filename);
        // $ext = pathinfo('upload/pack/'.$document->getId().'/'.$document->getTitle(),PATHINFO_EXTENSION);

        $response->headers->set('Content-Type','text/plain');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $packname.'_'.$date->format('YmdHis').'.pdf'
        );


        return $response;

    }
}
