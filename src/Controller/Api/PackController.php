<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\ClientMissionProposal;
use App\Entity\UserMission;
use App\Entity\UserPackMedia;
use App\Entity\UserPacks;
use App\Form\B2B\ClientMissionProposalType;
use App\Form\B2B\CommunityMediaType;
use App\Form\B2B\PackType;
use App\Repository\CommunityMediaRepository;
use App\Repository\OptionRepository;
use App\Repository\UserPacksRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/pack/", name="api_pack_")
 */
class PackController extends Controller
{
    /**
     * @Route("import-images/{id}",name="import_images")
     */
    public function images($id, UserPacksRepository $packRepo)
    {
        $pack = $packRepo->find($id);

        return $this->render('b2b/shared/pack-images.html.twig',[
            'images' => $pack->getUserPackMedia()
        ]);
    }

    /**
     * @Route("import-image-to-mission", name="import_to_mission")
     */
    public function importToMission(Request $request, Filesystem $filesystem)
    {
        $file = $request->get('filename');
        $filesystem->copy('uploads/packs/'.$this->getUser()->getId().'/'.$file,'uploads/'.UserMission::tempFolder().'/'.basename($file));

        return JsonResponse::create(['success' => true]);
    }

    /**
     * @Route("load-packs/{page}",name="_load_packs")
     */
    public function loadPacks($page)
    {
        $limit = 2;

//        $packs =
    }

    /**
     * @Route("view-pack/{id}",name="view")
     */
    public function viewPack($id, UserPacksRepository $userPacksRepo)
    {
        $pack = $userPacksRepo->find($id);


        if(is_null($pack))
        {
            return JsonResponse::create(['success' => false, 'response' => '<strong>Pack not found </strong>']);
        }

        return $response = $this->render('b2b/pack/_view.html.twig',[
            'pack' => $pack
        ]);
    }

    /**
     * @Route("create",name="create")
     */
    public function create(Request $request, Filesystem $filesystem, OptionRepository $optionRepository)
    {
        $pack = new UserPacks();
        $user = $this->getUser();
        $regions = $user->getUserRegion();
        $form = $this->createForm(PackType::class, $pack,['regions' => $regions]);

        $form->handleRequest($request);

        $tax = $optionRepository->findBy(['slug' => 'margin']);

        if($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $base_price = $request->get('pack')['userBasePrice'];
            $tax = $tax[0]->getValue();
            $margin = $base_price * $tax / 100;

//            $total_value = $margin + $base_price;
            $total_value = (100 * $base_price)/(100 - $tax);
            $pack->setUser($user);
            $pack->setMarginPercentage($tax);
            $pack->setMarginValue($margin);
            $pack->setTotalPrice($total_value);

            $em->persist($pack);

            $em->flush();

            foreach($pack->getUserPackMedia() as $media)
            {
                if(file_exists('uploads/pack/temp/'.$media->getName()))
                {
                    $filesystem->copy('uploads/pack/temp/' . $media->getName(), 'uploads/pack/' . $pack->getId() . '/' . $media->getName());
                    @unlink('uploads/pack/temp/' . $media->getName());
                }
                else
                {
                    $filesystem->copy('uploads/community_media/' . $user->getId() . '/' . $media->getName(), 'uploads/pack/' . $pack->getId() . '/' . $media->getName());
                }
            }

            return new JsonResponse(['success' => true, 'message' => 'Pack has been created successfully']);
        }
        return $this->render('b2b/pack/form.html.twig',
            [
                'pack' => $pack,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("edit/{id}",name="edit_pack")
     */
    public function edit($id = null, UserPacksRepository $packRepo, Request $request, OptionRepository $optionRepository ,Filesystem $filesystem)
    {
        $pack = $packRepo->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);
        $tax = $optionRepository->findBy(['slug' => 'margin']);
        $user = $this->getUser();
        $regions = $user->getUserRegion();
        if(is_null($pack))
        {
            return JsonResponse::create(['You are not authorized for this action']);
        }
        $form = $this->createForm(PackType::class, $pack,['regions' => $regions]);

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
            $margin = $base_price * ($tax / 100);
//            $total_value = $margin + $base_price;
            $total_value = (100 * $base_price)/(100 - $tax);

            $pack->setMarginPercentage($tax);
            $pack->setMarginValue($margin);
            $pack->setTotalPrice($total_value);

            $em->persist($pack);
            $em->flush();

            foreach($pack->getUserPackMedia() as $media)
            {
                if($filesystem->exists('uploads/pack/temp/'.$media->getName()))
                {
                    $filesystem->copy('uploads/pack/temp/'.$media->getName(),'uploads/pack/'.$pack->getId().'/'.$media->getName());
                }
            }
//            $files = explode(',', $request->get('attached_files'));
//
//            if ($files[0] != '') {
//
//                foreach ($files as $file) {
//                    $file = trim($file);
//
//                    $em = $this->getDoctrine()->getManager();
//
//                    if ($filesystem->exists('uploads/pack/temp/' . $file)) {
//                        if (!$filesystem->exists('uploads/pack/' . $pack->getId() . '/' . $file)) {
//                            $mediaEntity = new UserPackMedia();
//                            $mediaEntity->setName($file);
//                            $mediaEntity->setUserPack($pack);
//
//                            $em->persist($mediaEntity);
//                            $em->flush();
//
//                            $filesystem->copy('uploads/pack/temp/' . $file, 'uploads/pack/' . $pack->getId() . '/' . $file);
//
//                        }
//                    }
//
//
//                }
//
//            }

//            if ($request->get('cm_images')) {
//
//                foreach ($request->get('cm_images') as $key => $item) {
//
//                    $image = explode('/', $item);
//
//
//                    if ($filesystem->exists('uploads/community_media/' . $user->getId() . '/' . $image[4])) {
//                        if (!$filesystem->exists('uploads/pack/' . $pack->getId() . '/' . $image[4])) {
//
//                            $mediaEntity = new UserPackMedia();
//                            $mediaEntity->setName($image[4]);
//                            $mediaEntity->setUserPack($pack);
//
//                            $em->persist($mediaEntity);
//                            $em->flush();
//
//                            $filesystem->copy('uploads/community_media/' . $user->getId() . '/' . $image[4], 'uploads/pack/' . $pack->getId() . '/' . $image[4]);
//
//
//                        }
//
//                    }
//
//                }
//
//            }

            return new JsonResponse(['success' => true, 'url' => $this->generateUrl('b2b_community_manager_index')]);
        }

        return $this->render('b2b/pack/form.html.twig',
            [
                'form' => $form->createView(),
                'pack' => $pack
            ]);
    }

    /**
     * @Route("details/{id}",name="details")
     */
    public function details($id, UserPacksRepository $packRepo)
    {
        $pack = $packRepo->find($id);
        $images = [];
        $regions = [];
        if(!is_null($pack))
        {
            foreach($pack->getUserPackMedia() as $media)
            {
                $images[] = [
                    'name' => $media->getName(),
                    'id' => $media->getId()
                ];
            }

            foreach($pack->getPackRegions() as $region)
            {
                $regions[$region->getId()] = $region->getName();
            }
            return new JsonResponse(['success' => true, 'data' => ['id' => $pack->getId(),
                'price' => $pack->getUserBasePrice(),
                'images' => $images,
                'skill' => strtoupper($pack->getPackSkill()->getName()),
                'title' => $pack->getTitle(),
                'regions' => $regions,
                'description' => $pack->getDescription()]]);
        }
    }

    /**
     * @Route("images/{id}",name="images")
     */
    public function imagesPack($id, UserPacksRepository $packRepo)
    {
        $pack = $packRepo->find($id);

        foreach($pack->getUserPackMedia() as $media)
        {
            $obj['name'] = $media->getName();
            $obj['size'] = '1024';
            $obj['path'] = '/uploads/pack/'.$pack->getId().'/'.$media->getName();
            $obj['id'] = $this->getUser()->getId().'/'.$pack->getid();
            $result[] = $obj;
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("images-community-media",name="images_community_media")
     */
    public function imagesCommunityMedia(CommunityMediaRepository $mediaRepository)
    {
        $user = $this->getUser();
        $medias = $mediaRepository->findBy(['user' => $user]);

        foreach($medias as $media)
        {
            $obj['name'] = $media->getName();
            $obj['size'] = '1024';
            $obj['path'] = '/uploads/community_media/'.$user->getId().'/'.$media->getName();
            $obj['id'] = $this->getUser()->getId().'/'.$user->getid();
            $result[] = $obj;
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("image-display/{id}",name="display_image")
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
     * @Route("/file-upload", name="file_upload")
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
     * @Route("upload", name="upload");
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $user = $this->getUser();

        $file = $request->files->get('file');

        $fileName = $fileUploader->upload($file, 'pack/banner/', true);


        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
    }
}
