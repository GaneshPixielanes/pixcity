<?php

namespace App\Controller\Admin\Pages;

use App\Entity\PageCategory;
use App\Entity\PageCategoryMedia;
use App\Form\Admin\PageCategoryType;
use App\Repository\PageCategoryRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/pages/categories", name="admin_pages_categories_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class PagesCategoriesController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(Request $request, PageCategoryRepository $pages)
    {
        $query = $pages->createQueryBuilder("p")
            ->select(["p", "r"])
            ->leftJoin('p.region', 'r')
        ;

        $query = $query->orderBy('p.updatedAt', 'DESC');
        $list = $query->getQuery()->getResult();

        return $this->render('admin/pages/categories/index.html.twig', [
            'list' => $list
        ]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $page = new PageCategory();
        $form = $this->createForm(PageCategoryType::class, $page);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_pages_categories_list');
        }


        return $this->render('admin/pages/categories/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, PageCategory $page)
    {
        $form = $this->createForm(PageCategoryType::class, $page, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            return $this->redirectToRoute('admin_pages_categories_list');
        }

        return $this->render('admin/pages/categories/form.html.twig', [
            'item' => $page,
            'form' => $form->createView(),
        ]);
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
                $fileName = $fileUploader->upload($uploadedFile, PageCategoryMedia::UPLOAD_FOLDER, true);
                $attachment = new PageCategoryMedia();
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
    public function delete(Request $request, PageCategory $page)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_pages_categories_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($page);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_pages_categories_list');
    }

}