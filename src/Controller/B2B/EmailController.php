<?php

namespace App\Controller\B2B;

use App\Entity\AutoMail;
use App\Entity\Message;
use App\Entity\Ticket;
use App\Form\B2B\TicketType;
use App\Repository\ClientRepository;
use App\Repository\MessageRepository;
use App\Repository\TicketRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/b2b/email", name="b2b_email_")
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

        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class,$ticket);

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

            return $this->redirectToRoute('b2b_email_index');


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

        $tickit_data = $ticketRepository->find($id);

        $tickits = $ticketRepository->findBy(['cm' => $user->getId()]);

        foreach ($tickit_data->getMessages() as $data){
            foreach ($data as $item) {
                $item->setStatus(0);
            }

        }

        $entityManager->flush();

        $sendMails = $ticketRepository->getAllSenderCM($user->getId());

        $receiverMails = $ticketRepository->getAllReceiverCM($user->getId());

        return $this->render('b2b/email/cm/view.html.twig',[
            'tickit_data' => $tickit_data,
            'tickits' => $tickits,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails
        ]);
    }

    /**
     * @Route("/reply", name="reply")
     */
    public function replyEmail(Request $request,TicketRepository $ticketRepository,MessageRepository $messageRepository){


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

        return JsonResponse::create(['success' => true,'content' => $content,'file' => $message->getAttachment()]);


    }


    /**
     * @Route("/send", name="sent")
     */
    public function sendEmail(Request $request,TicketRepository $ticketRepository)
    {
        $mails = $ticketRepository->findBy(['cm' => $this->getUser()]);

        $sendMails = $ticketRepo->getAllSenderCM($user->getId());

        $receiverMails = $ticketRepo->getAllReceiverCM($user->getId());

        return $this->render('b2b/email/cm/view.html.twig',[
            'mails' => $mails,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails
        ]);
    }

    /**
     * @Route("/inbox", name="inbox")
     */
    public function inboxEmail(Request $request,TicketRepository $ticketRepository)
    {
        $user = $this->getUser();

        $mails = $ticketRepository->getAllReceiverCM($user->getId());

        $sendMails = $ticketRepository->findBy(['cm' => $user->getId(),'initiator' => 'cm']);

        $receiverMails = $ticketRepository->findBy(['cm' => $user->getId(),'initiator' => 'client']);

        return $this->render('b2b/email/cm/inbox.html.twig',[
            'mails' => $mails,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails
        ]);
    }

    /**
     * @Route("/send-emails", name="send_emails")
     */
    public function emailsSend(Request $request,TicketRepository $ticketRepository)
    {
        $user = $this->getUser();

        $mails = $ticketRepository->getAllSenderCM($user->getId());

        $sendMails = $ticketRepository->findBy(['cm' => $user->getId(),'initiator' => 'cm']);

        $receiverMails = $ticketRepository->findBy(['cm' => $user->getId(),'initiator' => 'client']);

        return $this->render('b2b/email/cm/inbox.html.twig',[
            'mails' => $mails,
            'sendMails' => $sendMails,
            'receiverMails' => $receiverMails
        ]);
    }







}
