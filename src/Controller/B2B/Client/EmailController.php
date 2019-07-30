<?php

namespace App\Controller\B2B\Client;

use App\Constant\MissionStatus;
use App\Entity\AutoMail;
use App\Entity\ClientMissionProposal;
use App\Entity\Message;
use App\Entity\Page;
use App\Entity\Ticket;
use App\Form\B2B\TicketType;
use App\Repository\ClientMissionProposalRepository;
use App\Repository\ClientRepository;
use App\Repository\MessageRepository;
use App\Repository\NotificationsRepository;
use App\Repository\TicketRepository;
use App\Repository\UserMissionRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



/**
 * @Route("/client/email", name="client_email_")
 * @Security("has_role('ROLE_USER')")
 */
class EmailController extends Controller
{
    /**
     * @Route("/index", name="index")
     */
    public function index(Request $request,TicketRepository $ticketRepo,UserRepository $userRepository,FileUploader $fileUploader)
    {

        $user = $this->getUser();

        $proposals = $this->getDoctrine()
            ->getRepository(ClientMissionProposal::class)
            ->findBy(['client' => $user->getId()]);

        $emails = [];

        foreach ($proposals as $proposal){
            if(!in_array($proposal->getUser()->getEmail(),$emails)){
                $emails[] = $proposal->getUser()->getId();
            }
        }


        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class,$ticket,['emails' => $emails]);

        $form->handleRequest($request);

        if($form->isSubmitted() && !$form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            $cm = $userRepository->find($request->get('ticket')['cm']);

            $template = $this->getDoctrine()->getRepository(AutoMail::class)->find(1);

            $ticket->setClient($user);
            $ticket->setCm($cm);
            $ticket->setTemplateType($template);
            $ticket->setInitiator('client');
            $ticket->setObject($request->get('ticket')['Object']);
            $ticket->setStatus('open');
            $ticket->setCreatedAt(new \DateTime('now'));
            $ticket->setUpdatedAt(new \DateTime('now'));

            $em->persist($ticket);
            $em->flush();

            $message = new Message();
            $message->setTicket($ticket);
            $message->setContent($request->get('ticket')['messages']['content']);
            $message->setType('1');
            $message->setStatus(1);

            $fileName = [];

            $files = $request->files->get('ticket')['messages']['attachment'];

            foreach ($files as $file){

                $fileExtension = md5(uniqid()).'.'.$file->guessExtension();

                $uploadDir = $this->get('kernel')->getRootDir() . '/../public/uploads/attachment/'.$ticket->getId();

                if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }


                if ($file->move($uploadDir, $fileExtension)) {
                    $fileName[] = $fileExtension;
                }



            }

            $message->setAttachment(implode(',',$fileName));
            $message->setAutoMail('no');

            $message->setCreatedAt(new \DateTime('now'));
            $message->setUpdatedAt(new \DateTime('now'));

            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('client_email_send_emails');


        }

        $mails = $ticketRepo->findBy(['client' => $user->getId()]);

        $sendMails = $ticketRepo->getAllSenderClient($user->getId());

        $receiverMails = $ticketRepo->getAllReceiverClient($user->getId());

        return $this->render('b2b/email/client/index.html.twig', [
            'form' => $form->createView(),
            'mails' => $mails,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails
        ]);
    }


    /**
     * @Route("/view/{id}", name="view")
     */
    public function view($id,TicketRepository $ticketRepository)
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();

        $data = $ticketRepository->findBy(['id' => $id,'client' => $user->getId()]);

        if(empty($data)){
            return $this->redirectToRoute('client_email_index');
        }

        $tickit_data = $ticketRepository->find($id);

        if(!empty($tickit_data->getMessages())){
            foreach ($tickit_data->getMessages() as $data){
                $data->setStatus(0);
                $entityManager->persist($data);
            }
        }


        $entityManager->flush();

        $sendMails = $ticketRepository->getAllSenderClient($user->getId());

        $receiverMails = $ticketRepository->getAllReceiverClient($user->getId());
        $nowDate = new \DateTime();

        return $this->render('b2b/email/client/_view.html.twig',[
            'tickit_data' => $tickit_data,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails,
            'nowDate' => $nowDate->format('Y-m-d')
        ]);
    }

    /**
     * @Route("/reply", name="reply")
     */
    public function replyEmail(Request $request,TicketRepository $ticketRepository,MessageRepository $messageRepository, Mailer $mailer){

        $fileName = [];

        $files = $request->files;

        foreach ($files as $file){

            $fileExtension = md5(uniqid()).'.'.$file->guessExtension();

            $uploadDir = $this->get('kernel')->getRootDir() . '/../public/uploads/attachment/'.$request->get('id');

            if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }


            if ($file->move($uploadDir, $fileExtension)) {
                $fileName[] = $fileExtension;
            }

        }

        $tickit = $ticketRepository->find($request->get('id'));

        $content = $request->get('comment');

        $initiator = $tickit->getInitiator();

        if($initiator == 'client'){
            $type = 1;
        }else{
            $type = 0;
        }

        $em = $this->getDoctrine()->getManager();

        $message = new Message();
        $message->setTicket($tickit);
        $message->setContent($content);
        $message->setType($type);
        $message->setStatus(1);
        $message->setAutoMail('no');
        $message->setAttachment(implode(',',$fileName));
        $message->setCreatedAt(new \DateTime('now'));
        $message->setUpdatedAt(new \DateTime('now'));

        $em->persist($message);
        $em->flush();

        $proposal_ticket = $ticketRepository->getclientPropsal($tickit->getClient()->getId());

        foreach ($proposal_ticket as $object) {

            if($object->getObject() == $tickit->getObject()){

//                if(count($messageRepository->getMessagesCountByclient($tickit->getId())) < 4 ){

                    $mailer->send($tickit->getCm()->getEmail(),
                        $tickit->getObject(),
                        'emails/b2b/internal_email.html.twig',
                        [
                            'tickit' => $tickit,
                            'content' => $message->getContent()
                        ]);


//                }

            }
        }

        return JsonResponse::create(['success' => true,'file' => $message->getAttachment()]);


    }

    /**
     * @Route("/send", name="sent")
     */
    public function sendEmail(Request $request,TicketRepository $ticketRepository)
    {
        $user = $this->getUser();

        $mails = $ticketRepository->findBy(['cm' => $this->getUser()]);

        $sendMails = $ticketRepository->getAllSenderClient($user->getId());

        $receiverMails = $ticketRepository->getAllReceiverClient($user->getId());

        return $this->render('b2b/email/client/view.html.twig',[
            'mails' => $mails,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails
        ]);
    }


    /**
     * @Route("", name="inbox")
     */
    public function inboxEmail(Request $request,
                               TicketRepository $ticketRepository,
                               UserRepository $userRepository,
                               UserMissionRepository $missionRepo,
                               NotificationsRepository $notificationsRepo,
                               ClientMissionProposalRepository $clientMissionProposalRepository,
                                Mailer $mailer)
    {

        $user = $this->getUser();

        $mails = $ticketRepository->getAllReceiverClient($user->getId());

        $sendMails = $ticketRepository->getAllSenderClient($user->getId());

        $receiverMails = $ticketRepository->getAllReceiverClient($user->getId());

        $user = $this->getUser();

        $proposals = $this->getDoctrine()
            ->getRepository(ClientMissionProposal::class)
            ->findBy(['client' => $user->getId()]);

        $emails = [];

        foreach ($proposals as $proposal){
            if(!in_array($proposal->getUser()->getEmail(),$emails)){
                $emails[] = $proposal->getUser()->getId();
            }
        }

        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class,$ticket,['emails' => $emails]);

        $form->handleRequest($request);

        if($form->isSubmitted() && !$form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            $cm = $userRepository->find($request->get('ticket')['cm']);

            $template = $this->getDoctrine() ->getRepository(AutoMail::class)->find(1);

            $ticket->setClient($user);
            $ticket->setCm($cm);
            $ticket->setTemplateType($template);
            $ticket->setInitiator('client');
            $ticket->setObject($request->get('ticket')['Object']);
            $ticket->setStatus('open');
            $ticket->setCreatedAt(new \DateTime('now'));
            $ticket->setUpdatedAt(new \DateTime('now'));

            $em->persist($ticket);
            $em->flush();

            $message = new Message();
            $message->setTicket($ticket);
            $message->setContent($request->get('ticket')['messages']['content']);
            $message->setType('1');
            $message->setStatus(1);

            $fileName = [];$fileOrgName = [];

            $files = $request->files->get('ticket')['messages']['attachment'];

            foreach ($files as $file){

                $fileExtension = md5(uniqid()).'.'.$file->guessExtension();

                $uploadDir = $this->get('kernel')->getRootDir() . '/../public/uploads/attachment/'.$ticket->getId();

                if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }


                if ($file->move($uploadDir, $fileExtension)) {
                    $fileName[] = $fileExtension;
                    $fileOrgName[] = $file->getClientOriginalName();
                }



            }



            $message->setAttachment(implode(',',$fileName));
            $message->setFilname(implode(',',$fileOrgName));
            $message->setAutoMail('no');

            $message->setCreatedAt(new \DateTime('now'));
            $message->setUpdatedAt(new \DateTime('now'));

            $em->persist($message);
            $em->flush();


            // Send mail to the Client
            $mailTitle = 'Question sur le pack : "'.trim($ticket->getObject()).'"';
            $mailer->send($ticket->getClient()->getEmail(),
                $mailTitle,
                'emails/b2b/email-cm-contacted-client-1.html.twig',
                [
                    'client' => $ticket->getClient(),
                    'message' => $message->getContent(),
                    'cityMaker' => $ticket->getCm()
                ],
                null,
                'services@pix.city'
            );
            return $this->redirectToRoute('client_email_inbox');


        }

        #SEO
        $page = new Page();
        $page->setMetaTitle("Pix.city Services : email client");
        $page->setMetaDescription("Retrouvez dans cet espace tous vos échanges avec les city-makers");


        return $this->render('b2b/email/client/inbox.html.twig',[
            'form' => $form->createView(),
            'mails' => $mails,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails,
            'missions' => $missionRepo->findOngoingMissions($this->getUser(),'client'),
            'notifications' => $notificationsRepo->findBy(['client' => $this->getUser(), 'unread' => 1]),
            'page' => $page
        ]);
    }

    /**
     * @Route("/emails-envoi", name="send_emails")
     */
    public function emailsSend(Request $request,TicketRepository $ticketRepository,UserRepository $userRepository,NotificationsRepository $notificationsRepo)
    {
        $user = $this->getUser();

        $mails = $ticketRepository->getAllSenderClient($user->getId());

        $sendMails = $ticketRepository->getAllSenderClient($user->getId());

        $receiverMails = $ticketRepository->getAllReceiverClient($user->getId());

        $user = $this->getUser();

        $proposals = $this->getDoctrine()
            ->getRepository(ClientMissionProposal::class)
            ->findBy(['client' => $user->getId()]);

        $emails = [];

        foreach ($proposals as $proposal){
            if(!in_array($proposal->getUser()->getEmail(),$emails)){
                $emails[] = $proposal->getUser()->getId();
            }
        }

        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class,$ticket,['emails' => $emails]);

        $form->handleRequest($request);

        if($form->isSubmitted() && !$form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            $cm = $userRepository->find($request->get('ticket')['cm']);

            $template = $this->getDoctrine() ->getRepository(AutoMail::class)->find(1);

            $ticket->setClient($user);
            $ticket->setCm($cm);
            $ticket->setTemplateType($template);
            $ticket->setInitiator('client');
            $ticket->setObject($request->get('ticket')['Object']);
            $ticket->setStatus('open');
            $ticket->setCreatedAt(new \DateTime('now'));
            $ticket->setUpdatedAt(new \DateTime('now'));

            $em->persist($ticket);
            $em->flush();

            $message = new Message();
            $message->setTicket($ticket);
            $message->setContent($request->get('ticket')['messages']['content']);
            $message->setType('1');
            $message->setStatus(1);

            $fileName = [];$fileOrgName = [];

            $files = $request->files->get('ticket')['messages']['attachment'];

            foreach ($files as $file){

                $fileExtension = md5(uniqid()).'.'.$file->guessExtension();

                $uploadDir = $this->get('kernel')->getRootDir() . '/../public/uploads/attachment/'.$ticket->getId();

                if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }


                if ($file->move($uploadDir, $fileExtension)) {
                    $fileName[] = $fileExtension;
                    $fileOrgName[] = $file->getClientOriginalName();
                }



            }

            $message->setAttachment(implode(',',$fileName));
            $message->setFilname(implode(',',$fileOrgName));
            $message->setAutoMail('no');

            $message->setCreatedAt(new \DateTime('now'));
            $message->setUpdatedAt(new \DateTime('now'));

            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('client_email_send_emails');

        }
        #SEO
        $page = new Page();
        $page->setMetaTitle("Pix.city Services : email envoi client");
        $page->setMetaDescription("Retrouvez dans cet espace tous vos emails envoyés");


        return $this->render('b2b/email/client/inbox.html.twig',[
            'form' => $form->createView(),
            'mails' => $mails,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails,
            'notifications' => $notificationsRepo->findBy(['client' => $this->getUser(), 'unread' => 1]),
            'page' => $page
        ]);
    }

    /**
     * @Route("/attachment-download/{id}/{filename}",name="attachment_download")
     */
    public function download($id,$filename, MessageRepository $messageRepo)
    {
        $message = $messageRepo->find($id);
        if($message->getTicket()->getCm() == $this->getUser() || $message->getTicket()->getClient() == $this->getUser())
        {
            $storedNames = explode(',',$message->getAttachment());
            $actualNames = explode(',',$message->getFilname());
            $position = array_search($filename,$storedNames);

            $date = new \DateTime();
            $file = 'uploads/attachment/'.$message->getTicket()->getId().'/'.$filename;
            $response = new BinaryFileResponse($file);
            $ext = pathinfo($file,PATHINFO_EXTENSION);

            $response->headers->set('Content-Type','text/plain');
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $actualNames[$position]
            );

            return $response;
        }

        return new JsonResponse(['success' => false]);
    }

}
