<?php

namespace App\Controller\Admin\Pages;

use App\Entity\CardCollection;
use App\Form\Admin\CardCollectionType;
use App\Repository\CardCollectionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/cards/collections", name="admin_cards_collections_")
 * @Security("has_role('ROLE_MODERATOR')")
 */

class CardsCollectionsController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(Request $request, CardCollectionRepository $collections)
    {
        $query = $collections->createQueryBuilder("c")
            ->select(["c", "u"])
            ->leftJoin('c.user', 'u')
        ;

        if($request->query->get('userId')) $query = $query->andWhere('u.id = :userId')->setParameter('userId', $request->query->get('userId'));

        $list = $query->getQuery()->getResult();

        return $this->render('admin/cards/collections/index.html.twig', [
            'list' => $list
        ]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {

        $collection = new CardCollection();
        $form = $this->createForm(CardCollectionType::class, $collection, [
            "userTypeaheadRoute" => $this->generateUrl('api_users_list'),
            "cardTypeaheadRoute" => $this->generateUrl('api_cards_search'),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collection);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_cards_collections_list');
        }


        return $this->render('admin/cards/collections/form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/duplicate", name="duplicate")
     * @Method({"GET"})
     */
    public function duplicate(Request $request, CardCollection $collection)
    {
        $collection->setUser(null);

        $form = $this->createForm(CardCollectionType::class, $collection, [
            "userTypeaheadRoute" => $this->generateUrl('api_users_list'),
            "cardTypeaheadRoute" => $this->generateUrl('api_cards_search'),
        ]);

        return $this->render('admin/cards/collections/form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/duplicate", name="duplicate_post")
     * @Method({"POST"})
     */
    public function duplicatePost(Request $request)
    {
        $collection = new CardCollection();
        $form = $this->createForm(CardCollectionType::class, $collection);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collection);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_cards_collections_list');
        }

        return $this->redirectToRoute('admin_cards_collections_duplicate', ["id" => $collection->getId()]);
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, CardCollection $collection)
    {
        $form = $this->createForm(CardCollectionType::class, $collection, [
            "type"=>"edit",
            "userTypeaheadRoute" => $this->generateUrl('api_users_list'),
            "cardTypeaheadRoute" => $this->generateUrl('api_cards_search'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            return $this->redirectToRoute('admin_cards_collections_list');
        }

        return $this->render('admin/cards/collections/form.html.twig', [
            'item' => $collection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     * @Method("POST")
     */
    public function delete(Request $request, CardCollection $collection)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_cards_collections_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($collection);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_cards_collections_list');
    }

}