<?php

namespace App\Controller\Admin\Pages;

use App\Controller\B2B\Client\MissionController;
use App\Entity\Option;
use App\Entity\User;
use App\Entity\UserMission;
use App\Form\Admin\AdminType;
use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Repository\UserMissionRepository;
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
                    WHERE g.active=1 AND g.deleted = 0 AND g.cmUpgradeB2bDate is not null AND (m.deleted = 0 OR m.deleted is null) GROUP BY g.id ORDER BY g.id DESC');
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
     * @Route("/invoices", name="invoices")
     * @Method({"GET", "POST"})
     */
    public function invoicesData(UserMissionRepository $userMissionRepository, AuthorizationCheckerInterface $authChecker)
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                return $this->render('admin/b2b/invoices/index.html.twig', [
                    'user_missions' => $userMissionRepository->findBy(['status'=>'terminated'],['id'=>'DESC']),
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
     * @Route("/invoices/show/{id}", name="show_invoices", methods={"GET"})
     */
    public function showInvoices(UserMission $userMission): Response
    {
        return $this->render('admin/b2b/invoices/show.html.twig', [
            'user_mission' => $userMission,
        ]);
    }

    /**
     * Create and download some zip documents.
     *
     * @Route("/invoices/download/{id}", name="download_invoices", methods={"GET"})
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function zipDownloadDocumentsAction($id, UserMissionRepository $userMissionRepository)
    {
        $mission = $userMissionRepository->findOneBy(['id'=>$id]);
        $filename = MissionController::createSlug($mission->getTitle());

        $clientInvoicePath = "invoices/".$mission->getId().'/'.$filename."-client.pdf";
        $cmInvoicePath = "invoices/".$mission->getId().'/'.$filename."-cm.pdf";
        $pcsInvoicePath = "invoices/".$mission->getId().'/'.$filename."-pcs.pdf";

        $files = [];


        //foreach ($documents as $document) {
            array_push($files,  $clientInvoicePath);
            array_push($files,  $cmInvoicePath);
            array_push($files,  $pcsInvoicePath);
        //}

        // Create new Zip Archive.
        $zip = new \ZipArchive();

        // The name of the Zip documents.
        $zipName = 'm_'.$id.'.zip';

        $zip->open($zipName,  \ZipArchive::CREATE);
        foreach ($files as $file) {
            $zip->addFromString(basename($file),  file_get_contents($file));
        }
        $zip->close();

        $response = new Response(file_get_contents($zipName));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
        $response->headers->set('Content-length', filesize($zipName));

        @unlink($zipName);

        return $response;
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
            $testAccounts = $entityManager->getRepository(Option::class)->findOneBy(['slug'=>'dev-cm-email']);
            if(strpos($testAccounts->getValue(),$users->getEmail()) !== false) { //in
                $emailStore = explode('@',$users->getEmail());
                $emailRename = $emailStore[0].'_deleted_'.strtotime("now").'@'.$emailStore[1];
                $users->setEmail($emailRename);
                $users->setVisible(0);
                $users->setActive(0);
                $users->setDeleted(1);
                $users->setDeletedAt(new \DateTime());
            }
            $users->setCmUpgradeB2bDate(null);
            $users->setB2bCmApproval(null);
            $users->setRoles(['ROLE_USER', 'ROLE_PIXIE']);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_admins_cm_lists');
    }
}