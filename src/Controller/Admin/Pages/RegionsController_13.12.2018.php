<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Region;
use App\Form\Admin\RegionType;
use App\Repository\RegionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/cards/regions", name="admin_cards_regions_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class RegionsController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(RegionRepository $regions)
    {
        $list = $regions->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/cards/regions/index.html.twig', ['list' => $list]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($region);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_cards_regions_list');
        }


        return $this->render('admin/cards/regions/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Region $region)
    {
        $form = $this->createForm(RegionType::class, $region, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            return $this->redirectToRoute('admin_cards_regions_list');
        }

        return $this->render('admin/cards/regions/form.html.twig', [
            'item' => $region,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/sort", name="sort")
     * @Method({"POST"})
     */
    public function sort(Request $request, RegionRepository $regions)
    {
        if($request->request->get('id')){

            $em = $this->getDoctrine()->getManager();

            $category = $regions->find($request->request->get('id'));
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
    public function delete(Request $request, Region $region)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_cards_regions_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($region);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_cards_categories_list');
    }

}