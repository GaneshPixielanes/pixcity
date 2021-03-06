<?php

namespace App\Controller\Front\Pages;

use App\Entity\CardCollection;
use App\Entity\Page;
use App\Repository\CardCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/collection/", name="front_collection_")
 */
class CollectionController extends Controller
{

    /**
     * @Route("{slug}", defaults={"slug"=null}, name="index")
     */
    public function index(Request $request, CardCollectionRepository $collectionRepo){

        $slug = $request->get("slug");
        $collection = $collectionRepo->findBySlugOrUuid($slug);

        if(!$collection instanceof CardCollection) {
            throw new NotFoundHttpException('error.not_found');
        }

        $page = new Page();
        $page->setName($collection->getName());
        $page->setMetaTitle(!empty($collection->getMetaTitle())?$collection->getMetaTitle():$collection->getName());
        $page->setMetaDescription(!empty($collection->getMetaDescription())?$collection->getMetaDescription():$collection->getDescription());
        $page->setIndexed($collection->getPublic());

        return $this->render('front/search/collection.html.twig', [
            'page' => $page,
            'collection' => $collection
        ]);

    }


}