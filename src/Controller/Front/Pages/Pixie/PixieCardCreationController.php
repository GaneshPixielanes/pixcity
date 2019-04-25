<?php

namespace App\Controller\Front\Pages\Pixie;

use App\Constant\CardProjectStatus;
use App\Constant\CardStatus;
use App\Entity\Card;
use App\Entity\CardInfo;
use App\Entity\CardMedia;
use App\Entity\ContentDraft;
use App\Entity\Page;
use App\Form\Shared\CardType;
use App\Repository\CardProjectRepository;
use App\Repository\CardRepository;
use App\Repository\ContentDraftRepository;
use App\Service\FileUploader;
use App\Service\Mailer;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/city-maker/creation-de-card", name="front_pixie_card_creation_")
 * @Security("has_role('ROLE_PIXIE')")
 */

class PixieCardCreationController extends Controller
{

    /**
     * @Route("/demande/{id}", name="project")
     */
    public function from_project(Request $request, CardRepository $cardsRepo, CardProjectRepository $projectsRepo, Mailer $mailer, ContentDraftRepository $contentDraftRepo)
    {
        $project = $projectsRepo->findOneBy([
            "id" => $request->get("id"),
            "status" => CardProjectStatus::PIXIE_ACCEPTED,
            "pixie" => $this->getUser()->getId()
        ]);
        dd($project);
        if(!$project){
            return $this->redirectToRoute('front_pixie_account_cards_projects');
        }

        $card = $project->getCard();

        if(!$card){
            $card = new Card();

            //-----------------------------------------------
            // Set project pre-requisites to the card
            if($project->getContentDraft())
            {
                // exit;
                $card->setContent($project->getContentDraft()->getContent());
            }

            if($project->getRegion()) $card->setRegion($project->getRegion());
            if($project->getDepartment()) $card->setDepartment($project->getDepartment());
            if($project->getCategories()) $card->setCategories($project->getCategories());
            if($project->getInfos()){
                foreach($project->getInfos() as $info){
                    $cardInfo = new CardInfo();
                    $cardInfo->setLabel($info->getLabel());
                    $cardInfo->setType($info->getType());
                    $cardInfo->setMandatory($info->getMandatory());
                    $card->addInfo($cardInfo);
                }
            }
        }

        //-----------------------------------------------
        // Create the form

        $form = $this->createForm(CardType::class, $card, ["type" => "creation", "upload_folder" => $this->getUser()->getId()]);
        $form->handleRequest($request);

        //-----------------------------------------------
        // Test all project mandatory fields

        if($form->isSubmitted()) {

            if($project->getMinWords()) {
                $cardContent = $form->get('content')->getData();
                $cardContentWords = str_word_count(strip_tags(strtolower($cardContent)), 1);

                if (count($cardContentWords) < ($project->getMinWords() - $project->getMinWords()*0.1)) {
                    $form->get('content')->addError(new FormError('error.minwords'));
                }
            }

            if($project->getMinPhotos()){
                if($card->getMedias()->count() < $project->getMinPhotos()){
                    $form->get('medias')->addError(new FormError('error.minphotos'));
                }
            }

        }

        //-----------------------------------------------
        // On submit

        if ($form->isSubmitted() && $form->isValid()) {

            $submitType = $form->get('submit_type')->getData();

            if($submitType === "publish") {
                $card->setStatus(CardStatus::SUBMITTED);
                $project->setStatus(CardProjectStatus::PIXIE_SUBMITTED);
            }
            else{
                $card->setStatus(CardStatus::SAVED);
            }

            $card->generateSlug();
            $card->getAddress()->setZipShortCode(substr($card->getAddress()->getZipCode(), 0 , 2));
            dd($card->getAddress());
            $card->setPixie($this->getUser());
            $project->setCard($card);

            // Save the user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($card);
            $entityManager->persist($project);
            $entityManager->flush();


            //----------------------------------------
            // Send confirmation email

            $mailer->send($this->getUser()->getEmail(), 'Card en cours de validation', 'emails/pixie-card-received.html.twig', [
                'firstname' => $this->getUser()->getFirstname()
            ]);


            // Add the flash message
            if($submitType === "publish") {
                $this->addFlash('card_published', '');
            }

            return $this->redirectToRoute("front_pixie_account_cards_projects");

        }

        //-----------------------------------------------
        // Create the page

        $page = new Page();
        $page->setName("Création de card");
        $page->setMetaTitle("Création de card");
        $page->setIndexed(false);

        return $this->render('front/account/pixie/cards-creation.html.twig', array(
            'page' => $page,
            'project' => $project,
            'card' => $card,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/upload", name="ajax_upload")
     * @Method({"POST"})
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $attachment = null;
        foreach ($request->files as $uploadedFile) {
            if ($uploadedFile instanceof UploadedFile) {
                // Test file size, limit is 10Mo
                if($uploadedFile->getSize() < 14000000 && ($uploadedFile->guessExtension() === "jpeg" || $uploadedFile->guessExtension() === "png")){
                    if ($uploadedFile->isValid()) {
                        $fileName = $fileUploader->upload($uploadedFile, CardMedia::uploadFolder(), true);
                        $attachment = new CardMedia();
                        $attachment->setName($fileName);
                        $attachment->setUpdatedAt(new \DateTime());
                    }
                }
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
     * @Route("/contrat/{id}", name="contract")
     * @Method({"GET"})
     */
    public function contract(Request $request, CardProjectRepository $projectsRepo)
    {
        #Get the logged in User ID
        $user_id = $this->getUser()->getId();

        #Get the corresponding contract details and the user details
        $contract = $projectsRepo->generateContract($request->get("id"), $projectsRepo, $user_id);

        $page = new Page();
        $page->setName("Contrat de card");
        $page->setMetaTitle("Contrat de card");
        $page->setIndexed(false);

        return $this->render('front/account/pixie/cards-creation-contract.html.twig', array(
            'page' => $page,
            'user' => $this->getUser(),
            'contract' => $contract,
        ));

    }

    /**
     * @Route("/draft/{id}",name="draft")
     */
    public function draftContent($id=null, Request $request, ContentDraftRepository $contentDraftRepository, CardProjectRepository $cardProjectRepo)
    {

        $url = explode('/',$request->headers->get('referer'));
        if(isset($url[6]))
        {
            $project = $cardProjectRepo->find($url[6]);

            if(!empty($project->getContentDraft()))
            {
                $em = $this->getDoctrine()->getEntityManager();
    //            $content = $contentDraftRepository->find($content->getId());
                $em->remove($project->getContentDraft());
                $em->flush();
            }

            $draft = new ContentDraft();
            $draft->setContent($request->get('content'));

            $draft->setCreatedAt(new \DateTime());
            $draft->setDraftType(0);

            $project->setContentDraft($draft);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();            
        }
        else
        {
            $content = $contentDraftRepository->findOneBy(['draftType' => 0, 'projectID' => NULL]);

            if(!empty($content))
            {
                $em = $this->getDoctrine()->getEntityManager();
                $content = $contentDraftRepository->find($content->getId());
                $em->remove($content);
                $em->flush();
            }

            $draft = new ContentDraft();
            $draft->setContent($request->get('content'));
            $draft->setCreatedAt(new \DateTime());
            $draft->setDraftType(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($draft);
            $entityManager->flush();

            echo "Saved!";exit;            
        }


        echo "Saved!";exit;
    }

}