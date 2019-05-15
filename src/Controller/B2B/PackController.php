<?php

namespace App\Controller\B2B;

use App\Entity\CommunityMeida;
use App\Entity\Page;
use App\Entity\UserPacks;
use App\Form\B2B\PackType;
use App\Repository\CommunityMediaRepository;
use App\Repository\UserPacksRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/b2b/pack", name="b2b_pack_")
 */
class PackController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(UserPacksRepository $packRepo)
    {
        $packs = $packRepo->findAll();

        return $this->render('b2b/pack/index.html.twig', [
            'packs' => $packs,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request,UserPacksRepository $packRepo)
    {
        $user = $this->getUser();

        $pack = new UserPacks();

        $form = $this->createForm(PackType::class,$pack);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $pack->setTitle($request->get(''));

        }

        // Create the page
        $page = new Page();
        $page->setName("Mes Cards en attente");
        $page->setMetaTitle("Mes Cards en attente");
        $page->setIndexed(false);

        return $this->render('b2b/pack/form.html.twig',[
            'form' => $form->createView(),
            'page' => $page,
            'user' => $user
        ]);
    }

    /**
     * @Route("/fileuploadhandler", name="fileuploadhandler")
     */
    public function fileUploadHandler(Request $request) {

        $output = array('uploaded' => false);

        $file = $request->files->get('file');

        $fileName = md5(uniqid()).'.'.$file->guessExtension();


        $uploadDir = $this->get('kernel')->getRootDir() . '/../public/uploads/community_media';


        $user = $this->getUser();

        if ($file->move($uploadDir, $fileName)) {
            $em = $this->getDoctrine()->getManager();

            $mediaEntity = new CommunityMeida();
            $mediaEntity->setName($fileName);
            $mediaEntity->setUser($user);

            $this->getUser()->addCommunityMeida($mediaEntity);

            $em->persist($user);
            $em->flush();

            $output['uploaded'] = true;
            $output['fileName'] = $fileName;
        }
        return new JsonResponse($output);
    }

    /**
     * @Route("/image-display",name="display_image")
     */
    public function showImages(Request $request){

        $user = $this->getUser();
        $result = [];
        foreach($user->getCommunityMedia() as $media)
        {
            $obj['name'] = $media->getName();
            $obj['size'] = '1024 ';
            $obj['path'] = 'uploads/community_media/'.$media->getName();
            $result[] = $obj;
        }

        return new JsonResponse($result);



    }

    /**
     * @Route("/image-delete",name="delete_image")
     */
    public function deleteImages(Request $request,CommunityMediaRepository $communityMediaRepository){

        $em = $this->getDoctrine()->getEntityManager();

        $media = $communityMediaRepository->findBy(['name' => $request->get('name')]);
        unlink('/uploads/community_media/'.$media->getName());
        $em->remove($media[0]);
        $em->flush();

        exit;


    }
}
