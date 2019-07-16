<?php

namespace App\Controller\Admin\Pages;

use App\Entity\User;
use App\Form\Admin\AdminType;
use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Constant\ViewMode;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/admin/administrators", name="admin_admins_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class AdminUsersController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(AdminRepository $admins)
    {
        $list = $admins->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/admins/index.html.twig', ['list' => $list]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($admin, $admin->getPlainPassword());
            $admin->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_admins_list');
        }


        return $this->render('admin/admins/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Admin $admin, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(AdminType::class, $admin, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($admin->getPlainPassword()) {
                $password = $passwordEncoder->encodePassword($admin, $admin->getPlainPassword());
                $admin->setPassword($password);
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');
            return $this->redirectToRoute('admin_admins_list');
        }

        return $this->render('admin/admins/form.html.twig', [
            'item' => $admin,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/delete", name="delete")
     * @Method("POST")
     */
    public function delete(Request $request, Admin $admin)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_admins_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($admin);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_admins_list');
    }
    /**
     * @Route("/switch-business", name="switch_business_mode")
     * @Method({"GET", "POST"})
     */
    public function switchBusinessUser(Request $request)
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2C){
            $user->setViewMode(ViewMode::B2B);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        elseif($user->getViewMode() == ViewMode::B2B){
            $user->setViewMode(ViewMode::B2C);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }


        $referer = $request->headers->get('referer');
        if ($referer == NULL) {
            return $this->redirectToRoute('front_homepage_index');
        } else {
            return $this->redirect($referer);
        }
    }

    /**
     * @Route("/cm-lists", name="cm_lists")
     * @Method({"GET", "POST"})
     */
    public function cmLists(Request $request, AuthorizationCheckerInterface $authChecker,UserRepository $userRepository)
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                $em= $this->getDoctrine()->getManager();
                $query = $em->createQuery('SELECT g, COUNT(m.user) AS userPack
                    FROM App:User AS g
                    LEFT JOIN App:UserPacks AS m WITH g.id = m.user
                    WHERE g.active=1 AND g.deleted = 0 AND g.cmUpgradeB2bDate is not null GROUP BY g.id');
                $result =  $query->getResult();

                //$list = $userRepository->findBy([], ['createdAt' => 'DESC']);
                return $this->render('admin/b2b/cmlists.html.twig',['list'=>$result]);
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
     * @Route("/payments", name="payments")
     * @Method({"GET", "POST"})
     */
    public function paymentsData(Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                return $this->render('admin/b2b/payments.html.twig');
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
    public function show(User $user): Response
    {
        return $this->render('admin/b2b/cmlists/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/status-update/{id}/{status}", name="status_update", methods={"GET"})
     */
    public function status(Request $request, AuthorizationCheckerInterface $authChecker): Response
    {
        $status = $request->get('status');
        $id = $request->get('id');
        $entityManager = $this->getDoctrine()->getManager();
        $userCm = $entityManager->getRepository(User::class)->find($id);
        $userCm->setActive($status);
        $entityManager->flush();

        return new JsonResponse(['data' => 'Updated']);
    }
    /**
     * @Route("/{id}/soft-delete", name="soft_delete", methods={"DELETE"})
     */
    public function deleteSoft(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->remove($userMission);
            $users = $entityManager->getRepository(User::class)->find($user->getId());
           // $users->setDeleted(1);
           // $users->setDeletedAt(new \DateTime());
            $users->setCmUpgradeB2bDate(null);
            $users->setB2bCmApproval(0);
            $users->setRoles(['ROLE_USER', 'ROLE_PIXIE']);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_admins_cm_lists');
    }
}