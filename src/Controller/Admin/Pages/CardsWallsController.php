<?php

namespace App\Controller\Admin\Pages;

use App\Entity\CardWall;
use App\Form\Admin\CardWallType;
use App\Repository\CardWallRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/pages/cards/walls", name="admin_pages_cards_walls_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class CardsWallsController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(Request $request, CardWallRepository $walls)
    {
        $query = $walls->createQueryBuilder("w")
            ->select(["w", "r", "d"])
            ->leftJoin('w.region', 'r')
            ->leftJoin('w.department', 'd')
        ;

        $query = $query->orderBy('w.updatedAt', 'DESC');
        $list = $query->getQuery()->getResult();

        return $this->render('admin/cards/walls/index.html.twig', [
            'list' => $list
        ]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $wall = new CardWall();
        $form = $this->createForm(CardWallType::class, $wall);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wall);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_pages_cards_walls_list');
        }


        return $this->render('admin/cards/walls/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, CardWall $wall)
    {
        $form = $this->createForm(CardWallType::class, $wall, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            return $this->redirectToRoute('admin_pages_cards_walls_list');
        }

        return $this->render('admin/cards/walls/form.html.twig', [
            'item' => $wall,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/delete", name="delete")
     * @Method("POST")
     */
    public function delete(Request $request, CardWall $wall)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_pages_cards_walls_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($wall);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_pages_cards_walls_list');
    }

}