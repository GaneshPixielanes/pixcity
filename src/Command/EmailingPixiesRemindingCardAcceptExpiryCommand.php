<?php

namespace App\Command;

use App\Entity\Card;
use App\Entity\CardProject;
use App\Entity\User;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EmailingPixiesRemindingCardAcceptExpiryCommand extends Command
{
    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $em, Mailer $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName("app:emailing:cardsrequestremindertoacceptsoon")
            ->setDescription("Emailing - Remind Pixies to Accept Cards within 24 hours");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->em->getRepository(User::class);
        $users = $users->findBy(['visible' => 1]);

        $cards = $this->em->getRepository(CardProject::class);
        $cards = $cards->findBy(['status' => ['assigned','accepted']]);
        $count = 0;
        foreach($cards as $card)
        {
//            $card->getDeliveryDate()->format('Ymd')
            $date = new \DateTime('+1 days');
            if($date->format('Ymd') == $card->getDeliveryDate()->format('Ymd'))
            {
                //Send mail
                $cardName = $card->getName();
                $firstName = $card->getPixie()->getFirstname();
                $mail = $card->getPixie()->getEmail();
                $mail = 'pixcitytestmails@yopmail.com'; // Comment these for live
                $this->mailer->send($mail,'24 heures avant expiration de ta card',
                    'emails/pixie-card-deadline-reminder.html.twig',[
                        'firstName' => $firstName,
                        'cardName' => $cardName
                    ]);

//                $output->writeln(); exit;
                $count++;
            }
//            $output->writeln($date->format('Ymd'));exit;
        }

        $output->writeln($count);
    }

}

?>