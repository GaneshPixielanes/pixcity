<?php

namespace App\Controller\Admin\Pages;

use App\Constant\ViewMode;
use App\Entity\UserMission;
use App\Form\UserMissionType;
use App\Repository\UserMissionRepository;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * @Route("/admin/user/mission", name="admin_user_mission_")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserMissionController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UserMissionRepository $userMissionRepository, AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                return $this->render('admin/b2b/user_mission/index.html.twig', [
                    'user_missions' => $userMissionRepository->findAll(),
                ]);
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $userMission = new UserMission();
        $form = $this->createForm(UserMissionType::class, $userMission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userMission);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_mission_index');
        }

        return $this->render('admin/b2b/user_mission/new.html.twig', [
            'user_mission' => $userMission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(UserMission $userMission): Response
    {
        return $this->render('admin/b2b/user_mission/show.html.twig', [
            'user_mission' => $userMission,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserMission $userMission, AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $form = $this->createForm(UserMissionType::class, $userMission);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $uploadedFile = $form['bannerImage']->getData();

                    $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                    if ($uploadedFile) {

                        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                        $uploadedFile->move(
                            $destination,
                            $newFilename
                        );


                        // instead of its contents
                        $userMission->setBannerImage($newFilename);
                    }
                    $this->getDoctrine()->getManager()->flush();

                    return $this->redirectToRoute('admin_user_mission_index', [
                        'id' => $userMission->getId(),
                    ]);
                }

                return $this->render('admin/b2b/user_mission/edit.html.twig', [
                    'user_mission' => $userMission,
                    'form' => $form->createView(),
                ]);
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserMission $userMission): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userMission->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userMission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_mission_index');
    }

    /**
     * @Route("/view/{id}/{usrtype}", name="view", methods={"GET"})
     */
    public function viewByUser(Request $request,UserMissionRepository $userMissionRepository): Response
    {
        $userOrClientId = $request->attributes->get('id');
        $userType    = $request->attributes->get('usrtype');
        if($userType == 'user'){
            $selectedUserRelated = $userMissionRepository->findBy(['user'=>$userOrClientId]);
        }
        elseif($userType == 'client'){
            $selectedUserRelated = $userMissionRepository->findBy(['client'=>$userOrClientId]);
        }
        else{
            $selectedUserRelated = $userMissionRepository->findAll();
        }

        return $this->render('admin/b2b/user_mission/index.html.twig', [
            'user_missions' => $selectedUserRelated,
        ]);
    }
}
