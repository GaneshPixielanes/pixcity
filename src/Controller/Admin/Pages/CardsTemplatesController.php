<?php

namespace App\Controller\Admin\Pages;

use App\Constant\CardProjectStatus;
use App\Constant\PaymentStatus;
use App\Entity\CardProject;
use App\Form\Admin\CardProjectType;
use App\Repository\CardProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/cards/templates", name="admin_cards_templates_")
 * @Security("has_role('ROLE_MODERATOR')")
 */

class CardsTemplatesController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(Request $request, CardProjectRepository $projects)
    {
        $query = $projects->createQueryBuilder("p")
            ->andWhere('p.status = :status')->setParameter('status', CardProjectStatus::TEMPLATE);


        $query = $query->orderBy('p.createdAt', 'DESC');
        $list = $query->getQuery()->getResult();

        return $this->render('admin/cards/templates/index.html.twig', [
            'list' => $list
        ]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $project = new CardProject();

        $form = $this->createForm(CardProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $project->setStatus(CardProjectStatus::TEMPLATE); // Force project status to template

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_cards_templates_list');
        }


        return $this->render('admin/cards/templates/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, CardProject $project)
    {
        $form = $this->createForm(CardProjectType::class, $project, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $project->setStatus(CardProjectStatus::TEMPLATE);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            return $this->redirectToRoute('admin_cards_templates_list');
        }

        return $this->render('admin/cards/templates/form.html.twig', [
            'item' => $project,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/delete", name="delete")
     * @Method("POST")
     */
    public function delete(Request $request, CardProject $project)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_cards_templates_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_cards_templates_list');
    }

}