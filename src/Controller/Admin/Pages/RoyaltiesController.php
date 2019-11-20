<?php

namespace App\Controller\Admin\Pages;

use Doctrine\ORM\Query;
use App\Constant\ViewMode;
use App\Entity\Royalties;
use App\Form\RoyaltiesType;
use App\Repository\RoyaltiesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/admin/royalties", name="admin_royalties_")
 * @Security("has_role('ROLE_ADMIN')")
 */
class RoyaltiesController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(RoyaltiesRepository $royaltiesRepository,AuthorizationCheckerInterface $authChecker,Request $request): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $filters = $request->query->get('royalties');
                $filterForm = $this->createForm(RoyaltiesType::class);
                $filterForm->handleRequest($request);

                $query = $royaltiesRepository->createQueryBuilder("c")
                    ->select(["c", "u", "r","k"])
                    ->leftJoin('c.cm', 'u')
                    ->leftJoin('c.mission', 'r')
                    ->innerJoin('r.client', 'k')
                ;

                if(isset($filters)){
                    if(!empty($filters['mission'])) $query = $query->andWhere('c.mission = :mission')->setParameter('mission', $filters['mission']);
                    if(!empty($filters['cm'])) $query = $query->andWhere('c.cm = :cm')->setParameter('cm', $filters['cm']);
                }
                $missionId = $request->query->get('id');
                if(isset($missionId)){
                    $query = $query->andWhere('c.mission = :mission')->setParameter('mission', $missionId);
                }
                $query = $query->orderBy('c.createdAt', 'DESC');
                $royaltiesData = $query->getQuery()
                    ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
                    ->getResult();

                return $this->render('admin/b2b/royalties/index.html.twig', [
                    'filterForm' => $filterForm->createView(),
                    'royalties' => $royaltiesData,
                ]);
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $royalty = new Royalties();
                $form = $this->createForm(RoyaltiesType::class, $royalty);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($royalty);
                    $entityManager->flush();

                    return $this->redirectToRoute('admin_royalties_index');
                }

                return $this->render('admin/b2b/royalties/new.html.twig', [
                    'royalty' => $royalty,
                    'form' => $form->createView(),
                ]);
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Royalties $royalty,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                return $this->render('admin/b2b/royalties/show.html.twig', [
                    'royalty' => $royalty,
                ]);
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Royalties $royalty,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $form = $this->createForm(RoyaltiesType::class, $royalty);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->getDoctrine()->getManager()->flush();

                    return $this->redirectToRoute('admin_royalties_index');
                }

                return $this->render('admin/b2b/royalties/edit.html.twig', [
                    'royalty' => $royalty,
                    'form' => $form->createView(),
                ]);
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Royalties $royalty,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                if ($this->isCsrfTokenValid('delete'.$royalty->getId(), $request->request->get('_token'))) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($royalty);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('admin/b2b/royalties_index');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }
}
