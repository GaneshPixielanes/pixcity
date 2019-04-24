<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Slider;
use App\Form\Admin\SliderType;
use App\Repository\SliderRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/pages/sliders", name="admin_pages_sliders_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class SlidersController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(SliderRepository $sliders)
    {
        $list = $sliders->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/pages/sliders/index.html.twig', ['list' => $list]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($slider);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_pages_sliders_list');
        }


        return $this->render('admin/pages/sliders/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Slider $slider)
    {
        $form = $this->createForm(SliderType::class, $slider, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            return $this->redirectToRoute('admin_pages_sliders_list');
        }

        return $this->render('admin/pages/sliders/form.html.twig', [
            'item' => $slider,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/sort", name="sort")
     * @Method({"POST"})
     */
    public function sort(Request $request, SliderRepository $sliders)
    {
        if($request->request->get('id')){

            $em = $this->getDoctrine()->getManager();

            $category = $sliders->find($request->request->get('id'));
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
    public function delete(Request $request, Slider $slider)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_pages_sliders_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($slider);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_pages_sliders_list');
    }

}