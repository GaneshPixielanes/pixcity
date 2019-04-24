<?php

namespace App\Controller\Admin\Pages;

use App\Form\Admin\AdminType;
use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

}