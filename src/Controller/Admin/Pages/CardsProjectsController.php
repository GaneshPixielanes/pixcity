<?php

namespace App\Controller\Admin\Pages;

use App\Constant\CardProjectStatus;
use App\Constant\PaymentStatus;
use App\Entity\CardProject;
use App\Entity\CardProjectAttachment;
use App\Entity\Notifications;
use App\Entity\Page;
use App\Entity\Card;
use App\Form\Admin\CardProjectType;
use App\Form\Admin\CardsProjectsFiltersType;
use App\Repository\CardProjectRepository;
use App\Repository\NotificationsRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\Mailer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/cards/projects", name="admin_cards_projects_")
 * @Security("has_role('ROLE_MODERATOR')")
 */

class CardsProjectsController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(Request $request, CardProjectRepository $projects)
    {
        $search = [];

        $filters = $request->query->get('cards_projects_filters');
        $filterForm = $this->createForm(CardsProjectsFiltersType::class);
        $filterForm->handleRequest($request);

        $query = $projects->createQueryBuilder("p")
            ->select(["p", "u", "r", "d"])
            ->leftJoin('p.pixie', 'u')
            ->leftJoin('p.region', 'r')
            ->leftJoin('p.department', 'd')

            ->leftJoin('p.transactions', 't')

            ->andWhere('p.status != :status')->setParameter('status', CardProjectStatus::TEMPLATE); // Hide template

        if(isset($filters)){
            if(!empty($filters['region'])) $query = $query->andWhere('p.region = :region')->setParameter('region', $filters['region']);
            if(!empty($filters['department'])) $query = $query->andWhere('p.department = :department')->setParameter('department', $filters['department']);
            if(!empty($filters['status'])) $query = $query->andWhere('p.status = :statusfilter')->setParameter('statusfilter', $filters['status']);
            if(!empty($filters['late'])){
                $now = new \DateTime("now");
                $query = $query->andWhere('p.deliveryDate < :now')->setParameter('now', $now);
            }

        }
        else{
            $query = $query->andWhere('p.status != :statusfilter')->setParameter('statusfilter', CardProjectStatus::VALIDATED);
        }

        $query = $query->orderBy('p.createdAt', 'DESC');
        $list = $query->getQuery()->getResult();

        return $this->render('admin/cards/projects/index.html.twig', [
            'filterForm' => $filterForm->createView(),
            'list' => $list
        ]);
    }



    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, CardProjectRepository $projects, Mailer $mailer)
    {
        $templates = $projects->createQueryBuilder("p")
            ->andWhere('p.status = :status')->setParameter('status', CardProjectStatus::TEMPLATE)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()->getResult();

        $project = new CardProject();
        $form = $this->createForm(CardProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if(count($form->getData()->getPixie()->getCards()) == 0)
            {
                //Send mail

                $mailer->send($form->getData()->getPixie()->getEmail(), 'Ta première card a été créée', 'emails/pixie-card-assigned-first-time.html.twig', [
                    'firstName' => $form->getData()->getPixie()->getFirstname(),
                    'card' => $project->getName()
                ]);
            }


            // Enter card details
//            $card = $project->getCard();
//            $card->setName($project->getName());
//            $card->setIndexed(false);
//            $card->setDepartment($project->getDepartment());
//            $card->setRegion($project->getRegion());
//            $card->setIsInterview($project->getIsInterview());
//            $card->setPixie($project->getPixie());
//
//            $project->setCard($card);
//
            $entityManager = $this->getDoctrine()->getManager();
//
//            $entityManager->persist($card);
            $entityManager->persist($project);
//            $entityManager->persist($notification);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');
            $this->addFlash('info', 'You can add gallery/images from here');

            return $this->redirectToRoute('admin_cards_projects_list');
        }

        return $this->render('admin/cards/projects/form.html.twig', array(
            'templates' => $templates,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, CardProject $project, TransactionRepository $transactions, Mailer $mailer)
    {
        $form = $this->createForm(CardProjectType::class, $project, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $card = $project->getCard();

            $project->setUpdatedBy($this->getUser());
            


            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

            //return $this->redirectToRoute('admin_cards_projects_list');
        }

        //-------------------------------
        // Find transactions

        $transaction = $transactions->createQueryBuilder("t")
            ->leftJoin("t.projects", "p")
            ->where("p.id = :projectId")->setParameter('projectId', $project->getId())
            ->getQuery()->getResult();

        return $this->render('admin/cards/projects/form.html.twig', [
            'transaction' => $transaction,
            'item' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/assign", name="ajax_assign")
     * @Method({"POST"})
     */
    public function assign(Request $request, CardProjectRepository $projects, UserRepository $users)
    {
        $userId = intval($request->request->get('userId'));
        $projectIds = $request->request->get('projectsIds');
        $projectsDatas = [];

        if($userId && $projectIds){

            $user = $users->find($userId);

            foreach($projectIds as $projectId){
                $project = $projects->find($projectId);
                $project->setPixie($user);
                $project->setUpdatedBy($this->getUser());
                $projectsDatas[] = [
                    'id' => $project->getId(),
                    'row' => $this->render('admin/cards/projects/_row.html.twig', [
                        'item' => $project
                    ])->getContent()
                ];
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return new JsonResponse($projectsDatas);
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
                $fileName = $fileUploader->upload($uploadedFile, CardProjectAttachment::UPLOAD_FOLDER, false);
                $attachment = new CardProjectAttachment();
                $attachment->setName($fileName);
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
     * @Route("/save-template", name="ajax_save_template")
     * @Method({"POST"})
     */
    public function saveAsTemplate(Request $request, CardProjectRepository $projects)
    {
        $templateName = $request->request->get("templateName");

        $project = new CardProject();
        $form = $this->createForm(CardProjectType::class, $project);
        $form->handleRequest($request);

        $responseDatas = [];

        if ($form->isSubmitted()) {

            if($templateName) $project->setName($templateName);
            $project->setStatus(CardProjectStatus::TEMPLATE);
            $project->setPaymentStatus(PaymentStatus::PENDING);
            $project->setPixie(null);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            $responseDatas = ['success' => true];
        }
        else{
            $responseDatas = ['error' => true];
        }

        return new JsonResponse($responseDatas);
    }

    /**
     * @Route("/load-template", name="ajax_load_template")
     * @Method({"POST"})
     */
    public function loadTemplate(Request $request, CardProjectRepository $projects)
    {
        if($request->request->get("id")) {
            $project = $projects->find($request->request->get("id"));

            $form = $this->createForm(CardProjectType::class, $project, ["type"=>"edit"]);

            $formHtml = $this->render('admin/cards/projects/_fields.html.twig', [
                'form' => $form->createView(),
            ])->getContent();

            return new JsonResponse(["success"=>true, "form"=>$formHtml]);

        }

        return new JsonResponse(["error"=>true]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     * @Method("POST")
     */
    public function delete(Request $request, CardProject $project)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_cards_projects_list');
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

        return $this->redirectToRoute('admin_cards_projects_list');
    }

    /**
     * @Route("/contrat/{id}", name="contract")
     * @Method({"GET"})
     */
    public function contract(Request $request, CardProjectRepository $projectsRepo)
    {
        $project = $projectsRepo->findOneBy([
            "id" => $request->get("id"),
        ]);

        $contract = json_decode($project->getContract());

        $page = new Page();
        $page->setName("Contrat de card");
        $page->setMetaTitle("Contrat de card");
        $page->setIndexed(false);

        return $this->render('front/account/pixie/cards-creation-contract.html.twig', array(
            'page' => $page,
            'contract' => $contract,
        ));

    }
}