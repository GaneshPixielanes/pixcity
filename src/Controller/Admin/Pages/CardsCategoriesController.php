<?php

namespace App\Controller\Admin\Pages;

use App\Entity\CardCategory;
use App\Form\Admin\CardCategoryType;
use App\Repository\CardCategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/cards/categories", name="admin_cards_categories_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class CardsCategoriesController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(CardCategoryRepository $categories)
    {
        $list = $categories->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/cards/categories/index.html.twig', ['list' => $list]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $category = new CardCategory();
        $form = $this->createForm(CardCategoryType::class, $category);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_cards_categories_list');
        }


        return $this->render('admin/cards/categories/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, CardCategory $category)
    {
        $form = $this->createForm(CardCategoryType::class, $category, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            return $this->redirectToRoute('admin_cards_categories_list');
        }

        return $this->render('admin/cards/categories/form.html.twig', [
            'item' => $category,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/sort", name="sort")
     * @Method({"POST"})
     */
    public function sort(Request $request, CardCategoryRepository $categories)
    {
        if(($request->request->get('id')) && ($request->request->get('position'))){

            $em = $this->getDoctrine()->getManager();

            $category = $categories->find($request->request->get('id'));
            $category->setPosition($request->request->get('position'));

            $em->persist($category);
            $em->flush();

            return new JsonResponse(["success" => true]);

        }
        else{
            return new JsonResponse(["error" => true]);
        }
    }



    /**
     * @Route("/{id}/delete", name="delete")
     * @Method("POST")
     */
    public function delete(Request $request, CardCategory $category)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_cards_categories_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_cards_categories_list');
    }

}