<?php

namespace App\Controller\Admin\Pages;

use App\Constant\CardStatus;
use App\Constant\UserLevel;
use App\Entity\Card;
use App\Entity\CardMedia;
use App\Entity\ContentDraft;
use App\Form\Admin\CardsFiltersType;
use App\Form\Shared\CardType;
use App\Repository\CardRepository;
use App\Repository\ContentDraftRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\Mailer;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/cards", name="admin_cards_")
 * @Security("has_role('ROLE_MODERATOR')")
 */

class CardsController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(Request $request, CardRepository $cards)
    {
        $search = [];

        $filters = $request->query->get('cards_filters');

        $filterForm = $this->createForm(CardsFiltersType::class);
        $filterForm->handleRequest($request);

        $query = $cards->createQueryBuilder("c")
            ->select(["c", "u", "r", "d"])
            ->leftJoin('c.pixie', 'u')
            ->leftJoin('c.region', 'r')
            ->leftJoin('c.department', 'd')
        ;

        if(isset($filters)){
            if(!empty($filters['region'])) $query = $query->andWhere('c.region = :region')->setParameter('region', $filters['region']);
            if(!empty($filters['department'])) $query = $query->andWhere('c.department = :department')->setParameter('department', $filters['department']);
            if(!empty($filters['status'])) $query = $query->andWhere('c.status = :status')->setParameter('status', $filters['status']);
            if(!empty($filters['isInterview'])) $query = $query->andWhere('c.is_interview = :isInterview')->setParameter('isInterview', $filters['isInterview']);
        }

        $query = $query->orderBy('c.createdAt', 'DESC');
        $list = $query->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->getResult();

        return $this->render('admin/cards/index.html.twig', [
            'filterForm' => $filterForm->createView(),
            'list' => $list
        ]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, CardRepository $cards, ContentDraftRepository $contentDraftRepo)
    {
        $card = new Card();

        // if(!empty($contentDraftRepo->findOneBy(['draftType' => 0])))
        // {
        //     $card->setContent($contentDraftRepo->findOneBy(['draftType' => 0, 'projectID' => NULL])->getContent());
        // }

        $form = $this->createForm(CardType::class, $card, ['type' => 'admin']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if(empty($card->getSlug())){
                $card->generateSlug();
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($card);
            $entityManager->flush();

            //Delete all draft
            // $this->deleteDraft($contentDraftRepo);

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_cards_list');
        }


        return $this->render('admin/cards/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request,
                         Card $card,
                         Mailer $mailer,
                         ContentDraftRepository $contentDraftRepo,
                         UserRepository $userRepo
    )
    {

        $form = $this->createForm(CardType::class, $card, ["type"=>"edit"]);

        $form->handleRequest($request);
        if ($form->isSubmitted() ) {

            if(empty($card->getSlug())){
                $card->generateSlug();
            }

            if($form->get('status')->getData() == 'validated')
            {
                #Get the user of the corresponding card
                $user = $card->getPixie();


                $card->setPublishedAt(new \DateTime());
            }

            $this->getDoctrine()->getManager()->flush();
            #Calculate the user's level
            $level = $userRepo->calculateLevel($user->getId());
            #If the level is about to be updated, send email
            if($level > $user->getLevel())
            {
                $levels = UserLevel::getList();
                $userLevel = array_search('LEVEL_'.$level,$levels);
                $mailer->send($user->getEmail(),'Congratulations! Your level has been updated',
                    'emails/cm-level-update.html.twig'
                    ,[
                        'firstName'=>$card->getProject()->getPixie()->getFirstname(),
                        'city' => $card->getAddress()->getCity(),
                        'region' => $card->getProject()->getRegion()->getName(),
                        'level' => $userLevel
                    ], NULL, NULL);

            }
            #Update the user level
            $user->setLevel($level);
            #Log card's level
            $card->setLevel($level);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');

//            dd($card->getThumb()->getUrl());


                if(count($form->getData()->getPixie()->getCards()) == 1 && $form->get('status')->getData() == 'validated')
                {
                    //Delete draft
                    // $this->deleteDraft($contentDraftRepo);

                    //Send mail if the card is being validated and this is the first card of the user that is validated
                    $mailer->send($form->getData()->getPixie()->getEmail(), 'Mail validation 1ere CARD', 'emails/pixie-card-validated-first-time.html.twig', [
                        'firstName' => $form->getData()->getPixie()->getFirstname(),
                        'cardName' => str_replace([' ','-'],'',$form->get('name')->getData()),
                        'regionName' => str_replace([' ','-'],'',$form->get('region')->getData()->getName()),
                        'cityName' => str_replace([' ','-'],'',$form->get('address')->get('city')->getData()),
                        'bannerUrl' =>$card->getMasterhead()->getUrl(),
                        'slug' => $card->getSlug(),
                        'card' => $card
                    ]);
                }
                elseif($form->get('status')->getData() == "rejected")
                {
                    $mailer->send($form->getData()->getPixie()->getEmail(),'Card refusée',
                        'emails/pixie-card-refused-mail.html.twig',
                        [
                            'firstName' => $form->getData()->getPixie()->getFirstname()
                        ]
                    );
                }
//            $clear_cache=shell_exec("fixweb clear_cache");
//            $clear_cache=shell_exec("chmod -R 777 /home/pixcity/production/var/www/private/var/cache");

            return $this->redirectToRoute('admin_cards_list');
        }

        return $this->render('admin/cards/form.html.twig', [
            'item' => $card,
            'form' => $form->createView(),
        ]);
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
                $fileName = $fileUploader->upload($uploadedFile, CardMedia::uploadFolder(), true);
                $attachment = new CardMedia();
                $attachment->setName($fileName);
                $attachment->setUpdatedAt(new \DateTime());
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
     * SOFT DELETE
     * @Route("/{id}/soft-delete", name="soft_delete")
     * @Method("POST")
     */
    public function soft_delete(Request $request, Card $card)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_users_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $card->setDeleted(true);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_cards_list');
    }

    /**
     * RESTORE FROM SOFT DELETE
     * @Route("/{id}/restore-soft-delete", name="restore_soft_delete")
     * @Method("POST")
     */
    public function restore_soft_delete(Request $request, Card $card)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_users_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $card->setDeleted(false);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }


        return $this->redirectToRoute('admin_cards_list');
    }


    /**
     * HARD DELETE
     * @Route("/{id}/hard-delete", name="hard_delete")
     * @Method("POST")
     */
    public function hard_delete(Request $request, Card $card)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_users_list');
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($card);
            $em->flush();
            $this->addFlash('success', 'flash.delete.success');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'flash.delete.error');
        }

        return $this->redirectToRoute('admin_cards_list');
    }

    /**
     * @Route("/draft",name="draft")
     */
    public function draftContent(Request $request, ContentDraftRepository $contentDraftRepository)
    {

        $this->deleteDraft($contentDraftRepository);

        $draft = new ContentDraft();
        $draft->setContent($request->get('content'));
        $draft->setCreatedAt(new \DateTime());
        $draft->setDraftType(0);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($draft);
        $entityManager->flush();

        echo "Saved!";exit;
    }

    private function deleteDraft(ContentDraftRepository $contentDraftRepository)
    {
        $content = $contentDraftRepository->findOneBy(['draftType' => 0, 'projectID' => NULL]);

        if(!empty($content))
        {
            $em = $this->getDoctrine()->getEntityManager();
            $content = $contentDraftRepository->find($content->getId());
            $em->remove($content);
            $em->flush();
        }
        
        return json_encode(['success' => true]);      
    }

}