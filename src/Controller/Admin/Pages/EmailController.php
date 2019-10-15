<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Email;
use App\Form\Admin\EmailType;
use App\Repository\EmailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/email", name="admin_email_")
 */
class EmailController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index()
    {
        return $this->render('admin/email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $email = new Email();

        $form = $this->createForm(EmailType::class, $email);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();


            $entityManager->persist($email);
            $entityManager->flush();

            return $this->redirectToRoute('admin_email_list');
        }
        return $this->render('/admin/email/form.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ajax", name="ajax")
     */
    public function ajax(Request $request, EmailRepository $emailRepo)
    {
        $columns = ["subject", "slug", "sentTo", "createdAt", "status"];

        $orders = $request->query->get("order");
        $orderBy = [];

        foreach($orders as $order){
            switch($columns[$order["column"]]){
                case "subject":
                case "slug":
                case "sentTo":
                case "status":
                case "createdAt":
                    $orderBy[] = [$columns[$order["column"]], $order["dir"]];
                    break;
                default:
                    $orderBy[] = ["createdAt", "DESC"];
            }
        }

        $searchText = $request->query->get("search");

        $start = $request->query->get("start", 0);
        $length = $request->query->get("length", 0);
        $page = (($start > 0)?$start / $length:0)+1;

        $filters = [];
        if($searchText && $searchText["value"]){
            $filters["text"] = $searchText["value"];
        }

//        $users = $usersRepo->searchUsers($filters, $page, $length, $orderBy);
//        $total = $usersRepo->countUsers($filters);
        $emails = $emailRepo->findAll();
        $total = count($emails);

        $json = [
            "draw" => intval($request->query->get('draw')),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => []
        ];

        foreach($emails as $email){
            $json["data"][] = [
                "subject" => $email->getSubject(),
                "slug" => $email->getSlug(),
                "sentTo" => $email->getSentTo(),
                "createdAt" => $email->getCreatedAt()->format('d-M-Y'),
                "status" => ($email->getStatus() == 1)?"active":"inactive",
                "id" => $email->getId()
            ];
        }

        return new JsonResponse($json);
    }

    /**
     * @Route("/edit/{id}",name="edit")
     */
    public function edit($id, EmailRepository $emailRepo, Request $request)
    {
        $email = $emailRepo->find($id);

        $form = $this->createForm(EmailType::class, $email);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($email);
            $entityManager->flush();

            return $this->redirectToRoute('admin_email_list');
        }

        return $this->render('/admin/email/form.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
