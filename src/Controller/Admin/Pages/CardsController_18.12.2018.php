<?php

namespace App\Controller\Admin\Pages;

use App\Constant\CardStatus;
use App\Entity\Card;
use App\Entity\CardMedia;
use App\Form\Admin\CardsFiltersType;
use App\Form\Shared\CardType;
use App\Repository\CardRepository;
use App\Service\FileUploader;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/cards", name="admin_cards_")
 * @Security("has_role('ROLE_MODERATOR')")
 */

class CardsController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(Request $request, CardRepository $cards)
    {
        $search = [];

        $filters = $request->query->get('cards_filters');

        $filterForm = $this->createForm(CardsFiltersType::class);
        $filterForm->handleRequest($request);

        $query = $cards->createQueryBuilder("c")
            ->select(["c", "u", "r", "d"])
            ->leftJoin('c.pixie', 'u')
            ->leftJoin('c.region', 'r')
            ->leftJoin('c.department', 'd')
        ;

        if(isset($filters)){
            if(!empty($filters['region'])) $query = $query->andWhere('c.region = :region')->setParameter('region', $filters['region']);
            if(!empty($filters['department'])) $query = $query->andWhere('c.department = :department')->setParameter('department', $filters['department']);
            if(!empty($filters['status'])) $query = $query->andWhere('c.status = :status')->setParameter('status', $filters['status']);
        }

        $query = $query->orderBy('c.createdAt', 'DESC');
        $list = $query->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();

        return $this->render('admin/cards/index.html.twig', [
            'filterForm' => $filterForm->createView(),
            'list' => $list
        ]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, CardRepository $cards)
    {
        $card = new Card();
        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if(empty($card->getSlug())){
                $card->generateSlug();
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($card);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_cards_list');
        }


        return $this->render('admin/cards/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Card $card)
    {
        $form = $this->createForm(CardType::class, $card, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(empty($card->getSlug())){
                $card->generateSlug();
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            return $this->redirectToRoute('admin_cards_list');
        }

        return $this->render('admin/cards/form.html.twig', [
            'item' => $card,
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
                $fileName = $fileUploader->upload($uploadedFile, CardMedia::uploadFolder(), true);
                $attachment = new CardMedia();
                $attachment->setName($fileName);
                $attachment->setUpdatedAt(new \DateTime());
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
     * SOFT DELETE
     * @Route("/{id}/soft-delete", name="soft_delete")
     * @Method("POST")
     */
    public function soft_delete(Request $request, Card $card)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_users_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $card->setDeleted(true);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_cards_list');
    }

    /**
     * RESTORE FROM SOFT DELETE
     * @Route("/{id}/restore-soft-delete", name="restore_soft_delete")
     * @Method("POST")
     */
    public function restore_soft_delete(Request $request, Card $card)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_users_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $card->setDeleted(false);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }


        return $this->redirectToRoute('admin_cards_list');
    }


    /**
     * HARD DELETE
     * @Route("/{id}/hard-delete", name="hard_delete")
     * @Method("POST")
     */
    public function hard_delete(Request $request, Card $card)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_users_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($card);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_cards_list');
    }

}