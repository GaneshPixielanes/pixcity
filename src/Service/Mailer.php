<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\EmailLog;
use Doctrine\ORM\EntityManagerInterface;

class Mailer
{

    private $mailer;
    private $params;
    private $templating;
    private $logger;
    private $entityManager;

    public function __construct(ParameterBagInterface $params, \Swift_Mailer $mailer, \Twig_Environment $templating, LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->params = $params;
        $this->templating = $templating;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    /**
     * Send email
     *
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param array $params
     *
     */
    public function send($to, $subject, $template, $params = [], $attachments = NULL, $from = NULL){

        $mail = explode('@',$to);
        $to = $mail[0].'@yopmail.com';
//        dd($to);
//        $to = 'ganeshpctest@yopmail.com';
        $message = (new \Swift_Message($subject));
            if(is_null($from))
            {
                $message =  $message->setFrom('contactus@pix.city', $this->params->get("email_sender_name"));
            }
            else
            {
                $message = $message->setFrom($from, $this->params->get("email_sender_name"));
            }
            $message = $message->setTo($to)
            ->setTo($to)
            ->setBody(
                $this->templating->render(
                    $template,
                    $params
                ),
                'text/html'
            )
        ;
        // dd($attachments);
        if(!is_null($attachments))
        {
            foreach($attachments as $key => $attachment)
            {
                // dd(realpath('/public/'.$attachment));
                $attch = \Swift_Attachment::fromPath(realpath(__DIR__.'/../../public/').$attachment); 
                $message = $message->attach($attch);
            }
        }

        if(!$this->mailer->send($message)){
            $this->logger->error("EMAIL FAIL : to ".$to." - template " . $template . " : " . $message, $params);
        }

        $emailLog = new EmailLog();
        $emailLog->setSubject($subject);
        $emailLog->setBody(strip_tags($template));
        $emailLog->setAttachment(json_encode($attachments));

        $this->entityManager->persist($emaiLLog)->flush();

    }


}