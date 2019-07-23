<?php

namespace App\Controller\Admin\Pages;

use App\Constant\ViewMode;
use App\Entity\User;
use App\Entity\UserMission;
use App\Form\UserMissionType;
use App\Repository\MissionLogRepository;
use App\Repository\UserMissionRepository;
use App\Service\FileUploader;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                    'user_missions' => $userMissionRepository->findBy(['deleted'=>0]),
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
//                    $uploadedFile = $form['bannerImage']->getData();
//
//                    $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
//                    if ($uploadedFile) {
//
//                        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
//                        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
//                        $uploadedFile->move(
//                            $destination,
//                            $newFilename
//                        );
//
//
//                        // instead of its contents
//                        $userMission->setBannerImage($newFilename);
//                    }
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
    public function delete(Request $request, UserMission $userMission): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userMission->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->remove($userMission);
            $userMissions = $entityManager->getRepository(UserMission::class)->find($userMission->getId());
            $userMissions->setDeleted(1);
            $userMissions->setDeletedAt(new \DateTime());
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
    /**
     * @Route("upload", name="upload")
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $file = $request->files->get('file');
        $fileName = $fileUploader->upload($file, 'missions/'.$request->get('id'), true);

        $entityManager = $this->getDoctrine()->getManager();

        $userMissions = $entityManager->getRepository(UserMission::class)->findOneBy(['id'=>$request->get('id')]);
        $userMissions->setBannerImage($fileName);
        $entityManager->flush();

        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
    }
    /**
     * Create and download some zip documents.
     *
     * @Route("/download/{id}", name="download", methods={"GET"})
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function zipDownloadBreifAction($id, MissionLogRepository $missionLogRepository)
    {
        $missionLog = $missionLogRepository->findOneBy(['id'=>$id]);

        $files = [];
        $briefFilesStr = $missionLog->getBriefFiles();
        $briefFilesArr = json_decode($briefFilesStr);
        foreach($briefFilesArr as $briefFile){
            array_push($files, "uploads/missions/temp/".$briefFile);
        }

        // Create new Zip Archive.
        $zip = new \ZipArchive();

        // The name of the Zip documents.
        $zipName = 'V_'.$id.'.zip';

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
}
