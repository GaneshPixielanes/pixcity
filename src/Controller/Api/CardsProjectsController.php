<?php

namespace App\Controller\Api;

use App\Constant\CardProjectStatus;
use App\Controller\Front\SearchPageController;
use App\Entity\Page;
use App\Repository\CardProjectRepository;
use App\Service\Mailer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use ZipArchive;

/**
 * @Route("/api/projects", name="api_projects_")
 */

class CardsProjectsController extends SearchPageController
{

    private $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer(array(new ObjectNormalizer()), array(new JsonEncoder()));
    }


    /**
     * Get modal content
     * @Route("/modal-infos", name="modal_infos")
     * @Method({"GET", "POST"})
     */
    public function infos(Request $request, CardProjectRepository $projectsRepo)
    {
        $projectId = $request->request->get("id");

        $html = "";

        if($projectId){
            $project = $projectsRepo->findOneBy(["pixie" => $this->getUser()->getId(), "id" => $projectId]);

            $html = $this->render('front/_modals/project-infos.html.twig', ['project' => $project])->getContent();
        }

        $responseContent = [
            "html" => $html,
            "result" => ($html !== "")?true:false
        ];


        return new JsonResponse($responseContent);
    }


    /**
     * Pixie accept project
     * @Route("/accept", name="accept")
     * @Method({"GET", "POST"})
     */
    public function accept(Request $request, CardProjectRepository $projectsRepo)
    {
        $projectId = $request->request->get("id");

        #Get the logged in User ID
        $user_id = $this->getUser()->getId();

        #Get the corresponding contract details and the user details
        $contract = $projectsRepo->generateContract($request->get("id"), $projectsRepo, $user_id);

        $page = new Page();
        $page->setName("Contrat de card");
        $page->setMetaTitle("Contrat de card");
        $page->setIndexed(false);

        $html = $this->render('front/account/pixie/cards-creation-contract.html.twig', array(
            'page' => $page,
            'user' => $this->getUser(),
            'contract' => $contract,
        ));

        $success = false;

        if($projectId){
            $project = $projectsRepo->findOneBy(["pixie" => $this->getUser()->getId(), "id" => $projectId, "status" => CardProjectStatus::ASSIGNED]);
            if($project){

                $contract = [
                    "gender" => $project->getPixie()->getGender(),
                    "firstname" => $project->getPixie()->getFirstname(),
                    "lastname" => $project->getPixie()->getLastname(),
                    "birthdate" => $project->getPixie()->getBirthdate(),
                    "status" => $project->getPixie()->getPixie()->getBilling()->getStatus(),
                    "company_name" => $project->getPixie()->getPixie()->getBilling()->getCompanyName(),
                    "company_address" => $project->getPixie()->getPixie()->getBilling()->getAddress()->getInlineAddress(),
                    "company_country" => $project->getPixie()->getPixie()->getBilling()->getAddress()->getCountry(),
                    "company_tva" => $project->getPixie()->getPixie()->getBilling()->getTva(),
                    "date" => date("Y-m-d H:i:s"),
                    "card_project_name" => $project->getName(),
                    'price' => $project->getPrice(),
                    // 'interview' => $project->getIsInterview()
                ];

                $project->setContract(json_encode($contract));
                $project->setStatus(CardProjectStatus::PIXIE_ACCEPTED);
                $project->setContractDetails($html);

                // Save the project
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($project);
                $entityManager->flush();

                $success = true;
            }
        }

        $responseContent = [
            "result" => $success
        ];

        return new JsonResponse($responseContent);
    }


    /**
     * Pixie refused project
     * @Route("/refuse", name="refuse")
     * @Method({"GET", "POST"})
     */
    public function refuse(Request $request, CardProjectRepository $projectsRepo, Mailer $mailer)
    {
        $projectId = $request->request->get("id");

        $success = false;
//        dd($this->getUser());
        $html = $this->render('emails/pixie-card-refused-pixie.html.twig',
            [
                'firstName' => $this->getUser()->getFirstName(),
                'lastName' => $this->getUser()->getLastName()
            ]);

        print $html;exit;
        if($projectId){
            $project = $projectsRepo->findOneBy(["pixie" => $this->getUser()->getId(), "id" => $projectId, "status" => CardProjectStatus::ASSIGNED]);
            if($project){
                $project->setStatus(CardProjectStatus::PIXIE_REFUSED);

                // Save the project
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($project);
                $entityManager->flush();

                $mailer->send('sourcing@pix.city','emails/pixie-card-refused-pixie.html.twig',
                    [
                        'firstName' => $this->getUser()->getFirstName(),
                        'lastName' => $this->getUser()->getLastName()
                    ]);
                $success = true;
            }
        }

        $responseContent = [
            "result" => $success
        ];

        return new JsonResponse($responseContent);
    }


    /**
     * Pixie refused project
     * @Route("/download-files/{id}", name="download_files")
     * @Method({"GET"})
     */
    public function download_files(Request $request, CardProjectRepository $projectsRepo)
    {
        $projectId = $request->get("id");

        $success = false;

        if($projectId){
            $project = $projectsRepo->findOneBy(["pixie" => $this->getUser()->getId(), "id" => $projectId]);

            if($project){

                $zip = new ZipArchive();
                $fileName = $project->getId().'_documents.zip';
                $zipName = "uploads/projects/".$fileName;
                $zip->open($zipName,  ZipArchive::CREATE);
                foreach($project->getAttachments() as $attachment){
                    $zip->addFromString(basename($attachment->getUrl()),  file_get_contents("../public".$attachment->getUrl()));
                }
                $zip->close();

                $response = new Response(file_get_contents($zipName));
                $response->headers->set('Content-Type', 'application/zip');
                $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
                $response->headers->set('Content-length', filesize($zipName));

                return $response;


            }
        }

        $responseContent = [
            "result" => $success
        ];

        return new JsonResponse($responseContent);
    }

}