<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Menu;
use App\Form\Admin\MenuType;
use App\Repository\MenuItemRepository;
use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/menus", name="admin_menus_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class MenusController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(MenuRepository $menus)
    {
        $list = $menus->findBy([]);

        return $this->render('admin/menus/index.html.twig', ['list' => $list]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if($menu->getSlug() === "") $menu->setSlug(null);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($menu);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_menus_list');
        }

        return $this->render('admin/menus/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Menu $menu)
    {
        $form = $this->createForm(MenuType::class, $menu, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($menu->getSlug() === "") $menu->setSlug(null);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');
            return $this->redirectToRoute('admin_menus_list');
        }

        return $this->render('admin/menus/form.html.twig', [
            'item' => $menu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sort", name="sort")
     * @Method({"POST"})
     */
    public function sort(Request $request, MenuItemRepository $menuItems)
    {
        if($request->request->get('id')){

            $em = $this->getDoctrine()->getManager();

            $menu = $menuItems->find($request->request->get('id'));
            $menu->setPosition($request->request->get('position'));

            $em->persist($menu);
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
    public function delete(Request $request, Menu $menu)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_menus_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_menus_list');
    }

}