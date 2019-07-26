<?php

namespace App\Controller\B2B;

use App\Entity\AutoMail;
use App\Entity\ClientMissionProposal;
use App\Entity\Message;
use App\Entity\Page;
use App\Entity\Ticket;
use App\Form\B2B\TicketType;
use App\Repository\ClientRepository;
use App\Repository\MessageRepository;
use App\Repository\NotificationsRepository;
use App\Repository\OptionRepository;
use App\Repository\TicketRepository;
use App\Service\FileUploader;
use App\Service\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/city-maker/email-envoi", name="b2b_email_")
 * @Security("has_role('ROLE_CM')")
 */

class EmailController extends Controller
{
    /**
     * @Route("/index", name="index")
     */
    public function index(Request $request,ClientRepository $clientRepository,FileUploader $fileUploader,TicketRepository $ticketRepo)
    {


        $user = $this->getUser();

        $proposals = $this->getDoctrine()
            ->getRepository(ClientMissionProposal::class)
            ->findBy(['user' => $user->getId()]);

        $emails = [];

        foreach ($proposals as $proposal){
            if(!in_array($proposal->getUser()->getId(),$emails)){
                $emails[] = $proposal->getClient()->getId();
            }
        }
        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class,$ticket,['emails' => $emails]);

        $form->handleRequest($request);

        if($form->isSubmitted() && !$form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $client = $clientRepository->find($request->get('ticket')['client']);

            $template = $this->getDoctrine() ->getRepository(AutoMail::class)->find(1);

            $ticket->setClient($client);
            $ticket->setCm($user);
            $ticket->setTemplateType($template);
            $ticket->setInitiator('cm');
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

            return $this->redirectToRoute('b2b_email_send_emails');


        }

        $mails = $ticketRepo->findBy(['cm' => $user->getId()]);

        $sendMails = $ticketRepo->getAllSenderCM($user->getId());

        $receiverMails = $ticketRepo->getAllReceiverCM($user->getId());

        return $this->render('b2b/email/cm/index.html.twig', [
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

        $data = $ticketRepository->findBy(['id' => $id,'cm' => $user->getId()]);

        if(empty($data)){
            return $this->redirectToRoute('b2b_email_index');
        }

        $tickit_data = $ticketRepository->find($id);

        $tickits = $ticketRepository->findBy(['cm' => $user->getId()]);

        foreach ($tickit_data->getMessages() as $data){
            $data->setStatus(0);
            $entityManager->persist($data);
        }

        $entityManager->flush();

        $sendMails = $ticketRepository->getAllSenderCM($user->getId());

        $receiverMails = $ticketRepository->getAllReceiverCM($user->getId());

        $nowDate = new \DateTime();

        #SEO
        $page = new Page();
        $page->setMetaTitle('Pix.city Services : email city-maker');
        $page->setMetaDescription('Retrouvez dans cet espace tous vos échanges avec vos clients');

        return $this->render('b2b/email/cm/_view.html.twig',[
            'tickit_data' => $tickit_data,
            'tickits' => $tickits,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails,
            'nowDate' => $nowDate->format('Y-m-d h:i'),
            'page' => $page
        ]);
    }

    /**
     * @Route("/reply", name="reply")
     */
    public function replyEmail(Request $request,
                               TicketRepository $ticketRepository,
                               MessageRepository $messageRepository,
                               Mailer $mailer
    ){


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

        if($initiator == 'cm'){
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

        $files = [];

        foreach (explode(',',$message->getAttachment()) as $file){
            $files[$file] =  "/uploads/attachments/".$message->getTicket()->getId().'/'.$file;
        }

        $files = implode(',',$files);

        // Send mail

        $mailer->send($message->getTicket()->getClient()->getEmail(),
            'EMAIL FROM '.$this->getUser().': '.$message->getTicket()->getObject(),
            [
                'message' => $message->getContent(),
                'cm' => $message->getTicket()->getCm(),
                'client' => $message->getTicket()->getClient()
            ]
        );
        return JsonResponse::create(['success' => true,'content' => $content,'file' => $message->getAttachment()]);


    }


    /**
     * @Route("/send", name="sent")
     */
    public function sendEmail(Request $request,TicketRepository $ticketRepo)
    {
        $mails = $ticketRepo->findBy(['cm' => $this->getUser()]);
        $user = $this->getUser();

        $sendMails = $ticketRepo->getAllSenderCM($user->getId());

        $receiverMails = $ticketRepo->getAllReceiverCM($user->getId());

        #SEO
        $page = new Page();
        $page->setMetaTitle('Pix.city Services : email city-maker');
        $page->setMetaDescription('Retrouvez dans cet espace tous vos échanges avec vos clients');

        return $this->render('b2b/email/cm/view.html.twig',[
            'mails' => $mails,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails,
            'page' => $page
        ]);
    }

    /**
     * @Route("/inbox", name="inbox")
     */
    public function inboxEmail(Request $request,ClientRepository $clientRepository,NotificationsRepository $notificationsRepo,TicketRepository $ticketRepository,OptionRepository $optionRepository)
    {
        $user = $this->getUser();

        $mails = $ticketRepository->getAllReceiverCM($user->getId());

        $sendMails = $ticketRepository->getAllSenderCM($user->getId());

        $receiverMails = $ticketRepository->getAllReceiverCM($user->getId());

        $user = $this->getUser();

        $proposals = $this->getDoctrine()
            ->getRepository(ClientMissionProposal::class)
            ->findBy(['user' => $user->getId()]);

        $emails = [];

        foreach ($proposals as $proposal){
            if(!in_array($proposal->getUser()->getId(),$emails)){
                $emails[] = $proposal->getClient()->getId();
            }
        }
        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class,$ticket,['emails' => $emails]);

        $form->handleRequest($request);

        if($form->isSubmitted() && !$form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $client = $clientRepository->find($request->get('ticket')['client']);

            $template = $this->getDoctrine() ->getRepository(AutoMail::class)->find(1);

            $ticket->setClient($client);
            $ticket->setCm($user);
            $ticket->setTemplateType($template);
            $ticket->setInitiator('cm');
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


            return $this->redirectToRoute('b2b_email_send_emails');


        }

        $mails = $ticketRepository->findBy(['cm' => $user->getId()]);

        $sendMails = $ticketRepository->getAllSenderCM($user->getId());

        $receiverMails = $ticketRepository->getAllReceiverCM($user->getId());

        $tax = $optionRepository->findBy(['slug' => 'tax']);

        #SEO
        $page = new Page();
        $page->setMetaTitle('Pix.city Services : email city-maker');
        $page->setMetaDescription('Retrouvez dans cet espace tous vos échanges avec vos clients');

        return $this->render('b2b/email/cm/inbox.html.twig',[
            'form' => $form->createView(),
            'mails' => $mails,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails,
            'notifications' => $notificationsRepo->findBy([
                'unread' => 1,
                'user' => $this->getUser()
            ]),
            'tax' => $tax[0],
            'page' => $page
        ]);
    }

    /**
     * @Route("/send-emails", name="send_emails")
     */
    public function emailsSend(Request $request,ClientRepository $clientRepository,FileUploader $fileUploader,TicketRepository $ticketRepository,NotificationsRepository $notificationsRepository,OptionRepository $optionRepository)
    {
        $user = $this->getUser();

        $mails = $ticketRepository->getAllSenderCM($user->getId());

        $sendMails = $ticketRepository->getAllSenderCM($user->getId());

        $receiverMails = $ticketRepository->getAllReceiverCM($user->getId());

        $user = $this->getUser();

        $proposals = $this->getDoctrine()
            ->getRepository(ClientMissionProposal::class)
            ->findBy(['user' => $user->getId()]);

        $emails = [];

        foreach ($proposals as $proposal){
            if(!in_array($proposal->getUser()->getId(),$emails)){
                $emails[] = $proposal->getClient()->getId();
            }
        }
        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class,$ticket,['emails' => $emails]);

        $form->handleRequest($request);

        if($form->isSubmitted() && !$form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $client = $clientRepository->find($request->get('ticket')['client']);

            $template = $this->getDoctrine() ->getRepository(AutoMail::class)->find(1);

            $ticket->setClient($client);
            $ticket->setCm($user);
            $ticket->setTemplateType($template);
            $ticket->setInitiator('cm');
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

            return $this->redirectToRoute('b2b_email_send_emails');


        }

        $mails = $ticketRepository->findBy(['cm' => $user->getId()]);

        $sendMails = $ticketRepository->getAllSenderCM($user->getId());

        $receiverMails = $ticketRepository->getAllReceiverCM($user->getId());

        $tax = $optionRepository->findBy(['slug' => 'tax']);

        #SEO
        $page = new Page();
        $page->setMetaTitle('Pix.city Services : email city-maker');
        $page->setMetaDescription('Retrouvez dans cet espace tous vos emails envoyés');

        return $this->render('b2b/email/cm/inbox.html.twig',[
            'form' => $form->createView(),
            'mails' => $mails,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails,
            'notifications' => $notificationsRepository->findBy([
                'unread' => 1,
                'user' => $this->getUser()
            ]),
            'tax' => $tax[0],
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
