<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Mailer
{

    private $mailer;
    private $params;
    private $templating;
    private $logger;

    public function __construct(ParameterBagInterface $params, \Swift_Mailer $mailer, \Twig_Environment $templating, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->params = $params;
        $this->templating = $templating;
        $this->logger = $logger;
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

        $to = 'ganeshpctest@yopmail.com';
        $message = (new \Swift_Message($subject));
            if(is_null($from))
            {
                $message =  $message->setFrom($this->params->get("email_sender"), $this->params->get("email_sender_name"));
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

        // dd($this->mailer->send($message));
        if(!$this->mailer->send($message)){
            $this->logger->error("EMAIL FAIL : to ".$to." - template " . $template . " : " . $message, $params);
        }

    }


}