<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Slider;
use App\Entity\SliderMedia;
use App\Entity\SliderSlide;
use App\Form\Admin\SliderSlideType;
use App\Repository\SliderSlideRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/pages/sliders/slides/{slider}", requirements={"slider": "\d+"}, name="admin_pages_sliders_slides_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class SlidersSlidesController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(Slider $slider, SliderSlideRepository $slides)
    {
        $list = $slides->findBySlider($slider->getId());

        return $this->render('admin/pages/sliders/slides/index.html.twig', [
            'slider' => $slider,
            'list' => $list
        ]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, Slider $slider)
    {
        $slide = new SliderSlide();
        $form = $this->createForm(SliderSlideType::class, $slide);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $slide->setSlider($slider);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($slide);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_pages_sliders_slides_list', ['slider'=>$slider->getId()]);
        }


        return $this->render('admin/pages/sliders/slides/form.html.twig', array(
            'slider' => $slider,
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Slider $slider, SliderSlide $slide)
    {
        $form = $this->createForm(SliderSlideType::class, $slide, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($slide->getImage() && $slide->getImage()->getName() === "") $slide->setImage(null);
            if($slide->getBackground() && $slide->getBackground()->getName() === "") $slide->setBackground(null);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            return $this->redirectToRoute('admin_pages_sliders_slides_list', ['slider'=>$slider->getId()]);
        }

        return $this->render('admin/pages/sliders/slides/form.html.twig', [
            'slider' => $slider,
            'item' => $slide,
            'form' => $form->createView(),
        ]);
    }


    /**
     * AJAX
     * @Route("/sort", name="sort")
     * @Method({"POST"})
     */
    public function sort(Request $request, SliderSlideRepository $slides)
    {
        if($request->request->get('id')){

            $em = $this->getDoctrine()->getManager();

            $slide = $slides->find($request->request->get('id'));
            $slide->setPosition($request->request->get('position'));

            $em->persist($slide);
            $em->flush();

            return new JsonResponse(["success" => true]);

        }
        else{
            return new JsonResponse(["error" => true]);
        }
    }


    /**
     * @Route("/upload", name="ajax_upload")
     * @Method({"POST"})
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $attachment = null;
        foreach ($request->files as $uploadedFile) {
            if($uploadedFile->isValid()) {
                $fileName = $fileUploader->upload($uploadedFile, SliderMedia::UPLOAD_FOLDER, true);
                $attachment = new SliderMedia();
                $attachment->setName($fileName);
            }
        }

        if($attachment){
            $response = new JsonResponse(['success' => true, 'name' => $attachment->getName(), 'url'=>$attachment->getUrl(), 'type'=>$attachment->getType()]);
        }
        else{
            $response = new JsonResponse(['error' => true], 400);
        }

        return $response;
    }


    /**
     * @Route("/{id}/delete", name="delete")
     * @Method("POST")
     */
    public function delete(Request $request, Slider $slider, SliderSlide $slide)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_pages_sliders_slides_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($slide);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_pages_sliders_slides_list', ['slider'=>$slider->getId()]);
    }

}