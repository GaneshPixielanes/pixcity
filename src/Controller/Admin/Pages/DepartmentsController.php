<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Department;
use App\Entity\Region;
use App\Form\Admin\DepartmentType;
use App\Repository\DepartmentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/cards/departments/{region}", requirements={"region": "\d+"}, name="admin_cards_departments_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class DepartmentsController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(Region $region, DepartmentRepository $departments)
    {
        $list = $departments->findByRegion($region->getId());

        return $this->render('admin/cards/regions/departments/index.html.twig', [
            'region' => $region,
            'list' => $list
        ]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, Region $region)
    {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $department->setRegion($region);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($department);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_cards_departments_list', ['region'=>$region->getId()]);
        }


        return $this->render('admin/cards/regions/departments/form.html.twig', array(
            'region' => $region,
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Region $region, Department $department)
    {
        $form = $this->createForm(DepartmentType::class, $department, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            return $this->redirectToRoute('admin_cards_departments_list', ['region'=>$region->getId()]);
        }

        return $this->render('admin/cards/regions/departments/form.html.twig', [
            'region' => $region,
            'item' => $department,
            'form' => $form->createView(),
        ]);
    }


    /**
     * AJAX
     * @Route("/sort", name="sort")
     * @Method({"POST"})
     */
    public function sort(Request $request, DepartmentRepository $departments)
    {
        if($request->request->get('id')){

            $em = $this->getDoctrine()->getManager();

            $category = $departments->find($request->request->get('id'));
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
    public function delete(Request $request, Region $region, Department $department)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_cards_departments_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($department);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_cards_departments_list', ['region'=>$region->getId()]);
    }

}