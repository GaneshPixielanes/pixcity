<?php

namespace App\Controller\B2B;

use App\Entity\CommunityMeida;
use App\Entity\Option;
use App\Entity\Page;
use App\Entity\UserMission;
use App\Entity\UserPackMedia;
use App\Entity\UserPacks;
use App\Form\B2B\PackType;
use App\Repository\CommunityMediaRepository;
use App\Repository\OptionRepository;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/b2b/pack", name="b2b_pack_")
 * @Security("has_role('ROLE_PIXIE')")
 */
class PackController extends Controller
{


    /**
     * @Route("/list", name="list")
     */
    public function index(UserPacksRepository $packRepo)
    {
        $user = $this->getUser();

        $packs = $packRepo->findByUser($user);

        return $this->render('b2b/pack/index.html.twig', [
            'packs' => $packs,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request,UserPacksRepository $packRepo,Filesystem $filesystem,OptionRepository $optionRepository)
    {

        $user = $this->getUser();

        if($user->getB2bCmApproval() == 0){
            return $this->redirectToRoute('b2b_pack_list');
        }

        $pack = new UserPacks();

        $tax = $optionRepository->findBy(['slug' => 'tax']);

        $form = $this->createForm(PackType::class,$pack);

        $form->handleRequest($request);

        if($form->isSubmitted()){

            $em = $this->getDoctrine()->getManager();
            $base_price = $request->get('pack')['userBasePrice'];
            $tax = $tax[0]->getValue();
            $margin = $base_price * $tax / 100;

            $total_value = $margin + $base_price;
            $pack->setUser($user);
//            $pack->setBannerImage($request->get('banner'));
            $pack->setMarginPercentage($tax);
            $pack->setMarginValue($margin);
            $pack->setTotalPrice($total_value);

            $em->persist($pack);

            $em->flush();

            if ($request->get('attached_files')){

                $files = explode(',',$request->get('attached_files'));

                foreach ($files as $file){

                    $file = trim($file);

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




            if($request->get('cm_images')){

                foreach ($request->get('cm_images') as $key => $item){
                    $image = explode('/',$item);

                    $mediaEntity = new UserPackMedia();
                    $mediaEntity->setName($image[4]);
                    $mediaEntity->setUserPack($pack);

                    $em->persist($mediaEntity);
                    $em->flush();

                    if($filesystem->exists('uploads/community_media/'.$user->getId().'/'.$image[4]))
                    {
                        $filesystem->copy('uploads/community_media/'.$user->getId().'/'.$image[4],'uploads/pack/'.$pack->getId().'/'.$image[4]);

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
            'page' => $page,
            'user' => $user,
            'images' => $images,
            'pack' => $pack,
            'tax' => $tax[0]
        ]);
    }


    /**
     * @Route("/edit/{id}",name="edit")
     */
    public function edit($id, Request $request,UserPacksRepository $packRepository,Filesystem $filesystem,OptionRepository $optionRepository)
    {

        $user = $this->getUser();

        $pack = $packRepository->findByUserPack($user,$id);

        if($user->getB2bCmApproval() == 0 || empty($pack)){
            return $this->redirectToRoute('b2b_pack_list');
        }

        $tax = $optionRepository->findBy(['slug' => 'tax']);

        $form = $this->createForm(PackType::class, $pack);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            if($user->getB2bCmApproval() == 0 or $pack === null){

                $this->addFlash(
                    'error',
                    'You cant create or edit the pack!'
                );
                return $this->redirectToRoute('b2b_pack_list');
            }

            $em = $this->getDoctrine()->getManager();

            if($request->get('banner')){
                $pack->setBannerImage($request->get('banner'));
            }

            $base_price = $request->get('pack')['userBasePrice'];
            $tax = $tax[0]->getValue();

            $margin = $base_price * $tax / 100;

            $total_value = $margin + $base_price;
            $pack->setMarginPercentage($tax);
            $pack->setMarginValue($margin);
            $pack->setTotalPrice($total_value);

            $em->persist($pack);
            $em->flush();

            $files = explode(',',$request->get('attached_files'));

            if($files[0] != ''){

                foreach ($files as $file){

                    $file = trim($file);

                    $em = $this->getDoctrine()->getManager();

                    if($filesystem->exists('uploads/pack/temp/'.$file))
                    {
                        if(!$filesystem->exists('uploads/pack/'.$pack->getId().'/'.$file))
                        {
                            $mediaEntity = new UserPackMedia();
                            $mediaEntity->setName($file);
                            $mediaEntity->setUserPack($pack);

                            $em->persist($mediaEntity);
                            $em->flush();

                            $filesystem->copy('uploads/pack/temp/'.$file,'uploads/pack/'.$pack->getId().'/'.$file);

                        }
                    }


                }

            }

            if($request->get('cm_images')){

                foreach ($request->get('cm_images') as $key => $item){

                    $image = explode('/',$item);



                    if($filesystem->exists('uploads/community_media/'.$user->getId().'/'.$image[4]))
                    {
                        if(!$filesystem->exists('uploads/pack/'.$pack->getId().'/'.$image[4])){

                            $mediaEntity = new UserPackMedia();
                            $mediaEntity->setName($image[4]);
                            $mediaEntity->setUserPack($pack);

                            $em->persist($mediaEntity);
                            $em->flush();

                            $filesystem->copy('uploads/community_media/'.$user->getId().'/'.$image[4],'uploads/pack/'.$pack->getId().'/'.$image[4]);


                        }

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

        return $this->render('b2b/pack/form_backup.html.twig',[
            'form' => $form->createView(),
            'pack' => $pack,
            'page' => $page,
            'user' => $user,
            'images' => $images,
            'tax' => $tax[0]
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

        $pack = $userPacksRepository->findByUserPack($this->getUser(),$id);

        if($pack === null){
            return $this->redirectToRoute('b2b_pack_list', [], 301);
        }

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
                $obj['size'] = '1024';
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

        $em->remove($media[0]);

        $em->flush();

        unlink('uploads/pack/'.$pack->getId().'/'.$request->get('name'));

        exit;

    }

    /**
     * @Route("/delete-pack/{id}",name="delete")
     */
    public function delete($id,Request $request,UserPacksRepository $userPacksRepository){

        $entityManager = $this->getDoctrine()->getManager();
        $pack = $entityManager->getRepository(UserPacks::class)->find($id);

        $pack = $userPacksRepository->findByUserPack($this->getUser(),$id);

        if($pack === null){
            return new JsonResponse(false);
        }

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

    /**
     * @Route("/edit-pack/{id}",name="edit_pack")
     */
    public function editPack($id = null, UserPacksRepository $packRepo, Request $request, OptionRepository $optionRepository ,Filesystem $filesystem)
    {
        $pack = $packRepo->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);
        $tax = $optionRepository->findBy(['slug' => 'margin']);

        $user = $this->getUser();

        if(is_null($pack))
        {
            return JsonResponse::create(['You are not authorized for this action']);
        }
        $form = $this->createForm(PackType::class, $pack);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if ($user->getB2bCmApproval() == 0 or $pack === null) {

                $this->addFlash(
                    'error',
                    'You cant create or edit the pack!'
                );
                return $this->redirectToRoute('b2b_pack_list');
            }

            $em = $this->getDoctrine()->getManager();

//            if ($request->get('banner')) {
//                $pack->setBannerImage($request->get('banner'));
//            }
            $base_price = $request->get('pack')['userBasePrice'];
            $tax = $tax[0]->getValue();

            $margin = $base_price * $tax / 100;

            $total_value = $margin + $base_price;
            $pack->setMarginPercentage($tax);
            $pack->setMarginValue($margin);
            $pack->setTotalPrice($total_value);

            $em->persist($pack);
            $em->flush();

            $files = explode(',', $request->get('attached_files'));

            if ($files[0] != '') {

                foreach ($files as $file) {
                    $file = trim($file);

                    $em = $this->getDoctrine()->getManager();

                    if ($filesystem->exists('uploads/pack/temp/' . $file)) {
                        if (!$filesystem->exists('uploads/pack/' . $pack->getId() . '/' . $file)) {
                            $mediaEntity = new UserPackMedia();
                            $mediaEntity->setName($file);
                            $mediaEntity->setUserPack($pack);

                            $em->persist($mediaEntity);
                            $em->flush();

                            $filesystem->copy('uploads/pack/temp/' . $file, 'uploads/pack/' . $pack->getId() . '/' . $file);

                        }
                    }


                }

            }

            if ($request->get('cm_images')) {

                foreach ($request->get('cm_images') as $key => $item) {

                    $image = explode('/', $item);


                    if ($filesystem->exists('uploads/community_media/' . $user->getId() . '/' . $image[4])) {
                        if (!$filesystem->exists('uploads/pack/' . $pack->getId() . '/' . $image[4])) {

                            $mediaEntity = new UserPackMedia();
                            $mediaEntity->setName($image[4]);
                            $mediaEntity->setUserPack($pack);

                            $em->persist($mediaEntity);
                            $em->flush();

                            $filesystem->copy('uploads/community_media/' . $user->getId() . '/' . $image[4], 'uploads/pack/' . $pack->getId() . '/' . $image[4]);


                        }

                    }

                }

            }

            return new JsonResponse(['success' => true]);
        }

        return $this->render('b2b/pack/form.html.twig',
            [
               'form' => $form->createView(),
               'pack' => $pack
            ]);
    }


    /**
     * @Route("/success", name="success")
     */
    public function success()
    {
        return $this->render('b2b/pack/success.html.twig');
    }

}
