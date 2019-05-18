<?php

namespace App\Controller\B2B;

use App\Entity\CommunityMeida;
use App\Entity\Page;
use App\Entity\UserMission;
use App\Entity\UserPackMedia;
use App\Entity\UserPacks;
use App\Form\B2B\PackType;
use App\Repository\CommunityMediaRepository;
use App\Repository\PackRepository;
use App\Repository\UserPackMediaRepository;
use App\Repository\UserPacksRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/b2b/pack", name="b2b_pack_")
 */
class PackController extends Controller
{


    /**
     * @Route("/list", name="list")
     */
    public function index(UserPacksRepository $packRepo)
    {
        $packs = $packRepo->findBy(['deleted' => null ],['id' => 'DESC']);

        return $this->render('b2b/pack/index.html.twig', [
            'packs' => $packs,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request,UserPacksRepository $packRepo,Filesystem $filesystem)
    {
        $user = $this->getUser();

        $pack = new UserPacks();

        $form = $this->createForm(PackType::class,$pack);

        $form->handleRequest($request);

        if($form->isSubmitted()){

            $em = $this->getDoctrine()->getManager();

            $pack->setUser($user);
            $pack->setBannerImage($request->get('banner'));

            $em->persist($pack);

            $em->flush();

            $files = explode(',',$request->get('attached_files'));

            foreach ($files as $file){

                $file = trim($file);

                $em = $this->getDoctrine()->getManager();

                $mediaEntity = new UserPackMedia();
                $mediaEntity->setName($file);
                $mediaEntity->setUserPack($pack);

                $em->persist($mediaEntity);
                $em->flush();

                if($filesystem->exists('uploads/pack/temp/'.$file))
                {
                    $filesystem->copy('uploads/pack/temp/'.$file,'uploads/pack/'.$pack->getId().'/'.$file);

                }


            }


            return $this->redirectToRoute('b2b_pack_list');
        }


        $page = new Page();
        $page->setName("Mes Cards en attente");
        $page->setMetaTitle("Mes Cards en attente");

        $page->setIndexed(false);

        $images = $user->getCommunityMedia();


        return $this->render('b2b/pack/form.html.twig',[
            'form' => $form->createView(),
            'page' => $page,
            'user' => $user,
            'images' => $images,
            'pack' => $pack
        ]);
    }


    /**
     * @Route("/edit/{id}",name="edit")
     */
    public function edit($id, Request $request,UserPacksRepository $packRepository,Filesystem $filesystem)
    {

        $user = $this->getUser();

        $pack = $packRepository->find($id);

        $form = $this->createForm(PackType::class, $pack);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {

            $em = $this->getDoctrine()->getManager();

            if($request->get('banner')){
                $pack->setBannerImage($request->get('banner'));
            }

            $em->persist($pack);
            $em->flush();

            $files = explode(',',$request->get('attached_files'));

            if($files[0] != ''){

                foreach ($files as $file){

                    $file = trim($file);

                    $em = $this->getDoctrine()->getManager();

                    $mediaEntity = new UserPackMedia();
                    $mediaEntity->setName($file);
                    $mediaEntity->setUserPack($pack);

                    $em->persist($mediaEntity);
                    $em->flush();

                    if($filesystem->exists('uploads/pack/temp/'.$file))
                    {
                        $filesystem->copy('uploads/pack/temp/'.$file,'uploads/pack/'.$pack->getId().'/'.$file);

                    }


                }

            }


            return $this->redirectToRoute('b2b_pack_list');
        }

        $page = new Page();
        $page->setName("Mes Cards en attente");
        $page->setMetaTitle("Mes Cards en attente");

        $page->setIndexed(false);

        $images = $user->getCommunityMedia();

        return $this->render('b2b/pack/form.html.twig',[
            'form' => $form->createView(),
            'pack' => $pack,
            'page' => $page,
            'user' => $user,
            'images' => $images
        ]);
    }

    /**
     * @Route("/upload", name="_upload");
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $user = $this->getUser();

        $file = $request->files->get('file');

        $fileName = $fileUploader->upload($file, 'pack/banner/', true);

        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
    }


    /**
     * @Route("/view/{id}",name="view")
     */
    public function view($id, UserPacksRepository $userPacksRepository)
    {
        $pack = $userPacksRepository->find($id);
        $images = $pack->getUserPackMedia();
        return $this->render('b2b/pack/view.html.twig',[
            'pack' => $pack,
            'images' => $images
        ]);
    }


    /**
     * @Route("/fileuploadhandler", name="fileuploadhandler")
     */
    public function fileUploadHandler(Request $request) {

        $user = $this->getUser();

        $output = array('uploaded' => false);

        $file = $request->files->get('file');

        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $uploadDir = $this->get('kernel')->getRootDir() . '/../public/uploads/pack/temp/';

        if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        if ($file->move($uploadDir, $fileName)) {

            $output['uploaded'] = true;
            $output['fileName'] = $fileName;
        }

        return new JsonResponse($output);
    }

    /**
     * @Route("/image-display/{id}",name="display_image")
     */
    public function showImages($id,Request $request,UserPacksRepository $userPacksRepository){

        $user = $this->getUser();

        $pack = $userPacksRepository->find($id);

        $result = [];

        if(count($pack->getUserPackMedia())){

            foreach($pack->getUserPackMedia() as $media)
            {
                $obj['name'] = $media->getName();
                $obj['size'] = filesize('uploads/pack/'.$pack->getId().'/'.$media->getName());
                $obj['path'] = '/uploads/pack/'.$pack->getid().'/'.$media->getName();
                $obj['id'] = $user->getId().'/'.$pack->getid();
                $result[] = $obj;
            }

        }

        return new JsonResponse($result);



    }

    /**
     * @Route("/image-delete",name="delete_image")
     */
    public function deleteImages(Request $request,UserPackMediaRepository $userPackMediaRepository){

        $em = $this->getDoctrine()->getEntityManager();

        $media = $userPackMediaRepository->findBy(['name' => $request->get('name')]);

        $pack = $media[0]->getUserPack();

        unlink('uploads/pack/'.$pack->getId().'/'.$request->get('name'));

        $em->remove($media[0]);

        $em->flush();


        exit;


    }

    /**
     * @Route("/delete-pack/{id}",name="delete")
     */
    public function delete($id,Request $request,UserPacksRepository $userPacksRepository){

        $entityManager = $this->getDoctrine()->getManager();
        $pack = $entityManager->getRepository(UserPacks::class)->find($id);

        if (!$pack) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $pack->setDeletedAt(new \DateTime('now'));
        $pack->setActive(0);
        $pack->setDeleted(0);
        $entityManager->flush();

        return $this->redirectToRoute('b2b_pack_list', [
            'id' => $pack->getId()
        ]);

    }



}
