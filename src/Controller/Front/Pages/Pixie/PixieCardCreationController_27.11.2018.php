<?php

namespace App\Controller\Front\Pages\Pixie;

use App\Constant\CardProjectStatus;
use App\Constant\CardStatus;
use App\Entity\Card;
use App\Entity\CardInfo;
use App\Entity\CardMedia;
use App\Entity\Page;
use App\Form\Shared\CardType;
use App\Repository\CardProjectRepository;
use App\Repository\CardRepository;
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
 * @Route("/pixie/creation-de-card", name="front_pixie_card_creation_")
 * @Security("has_role('ROLE_PIXIE')")
 */

class PixieCardCreationController extends Controller
{

    /**
     * @Route("/demande/{id}", name="project")
     */
    public function from_project(Request $request, CardRepository $cardsRepo, CardProjectRepository $projectsRepo, Mailer $mailer)
    {
        $project = $projectsRepo->findOneBy([
            "id" => $request->get("id"),
            "status" => CardProjectStatus::PIXIE_ACCEPTED,
            "pixie" => $this->getUser()->getId()
        ]);

        if(!$project){
            return $this->redirectToRoute('front_pixie_account_cards_projects');
        }

        $card = $project->getCard();

        if(!$card){
            $card = new Card();

            //-----------------------------------------------
            // Set project pre-requisites to the card

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
                if($uploadedFile->getSize() < 10000000 && ($uploadedFile->guessExtension() === "jpeg" || $uploadedFile->guessExtension() === "png")){
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
        $project = $projectsRepo->findOneBy([
            "id" => $request->get("id"),
            "status" => CardProjectStatus::ASSIGNED,
            "pixie" => $this->getUser()->getId()
        ]);

        $user = $project->getPixie();

        if(!$project || !$user){
            return $this->redirectToRoute('front_pixie_account_cards_projects');
        }

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
            "card_project_name" => $project->getName()
        ];


        $page = new Page();
        $page->setName("Contrat de card");
        $page->setMetaTitle("Contrat de card");
        $page->setIndexed(false);

        return $this->render('front/account/pixie/cards-creation-contract.html.twig', array(
            'page' => $page,
            'user' => $user,
            'contract' => $contract,
        ));

    }

}