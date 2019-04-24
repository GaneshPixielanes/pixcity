<?php
namespace App\Command;

use App\Entity\Card;
use App\Entity\CardProject;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EmailingPixiesCardAcceptDeadlineCommand extends Command
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
            ->setName("app:emailing:pixiecardsacceptreminder")
            ->setDescription("Emailing - Remind Pixies to Accept Cards");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cards = $this->em->getRepository(CardProject::class);
        $assignedCards = $cards->findBy(['status' => 'assigned']);
        $deadline = new \DateTime('-3 days');
        $count = 0;

        foreach ($assignedCards as $card)
        {
            $assignedDate = $card->getCreatedAt()->format('Ymd');
            if($assignedDate < $deadline->format('Ymd'))
            {
                $mail = $card->getPixie()->getEmail();
                $mail = 'pixcitytestmails@yopmail.com'; // Comment these for live

                $this->mailer->send($mail, 'Card attribuée, en attente de ta validation', 'templates/emails/pixie-remind-accept-card.html.twig', [
                    'firstname' => $card->getPixie()->getFirstname(),
                    'lastname' => $card->getPixie()->getLastname(),
                    'id' => $card->getPixie()->getId(),
                ]);
                $count++;
            }

        }
        $output->writeln(count($assignedCards));
    }
}