<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\ClientMissionProposal;
use App\Entity\UserMission;
use App\Form\B2B\ClientMissionProposalType;
use App\Repository\UserPacksRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/pack/", name="api_pack_")
 */
class PackController extends AbstractController
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
}
