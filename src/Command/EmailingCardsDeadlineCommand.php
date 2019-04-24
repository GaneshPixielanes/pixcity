<?php

namespace App\Command;

use App\Entity\CardProject;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EmailingCardsDeadlineCommand extends Command
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
            ->setName("app:emailing:cardsreminder")
            ->setDescription("Emailing - My Pixies' activity");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $countEmails = 0;

        $projectRepo = $this->em->getRepository(CardProject::class);

        $projects = $projectRepo->searchDeadlineComing();

        foreach($projects as $project){
            if($project instanceof CardProject && !empty($project->getPixie())) {

                $mail = $project->getPixie()->getEmail();
                $mail = 'pixcitytestmails@yopmail.com'; // Comment these for live

                $this->mailer->send($mail, 'La demande de card '.$project->getName().' arrive à échéance', 'emails/pixie-card-reminder.html.twig', [
                    'firstname' => $project->getPixie()->getFirstname(),
                ]);

                $countEmails++;

                $project->setReminderEmailSent(true);
                $this->em->persist($project);

            }
        }

        $this->em->flush();

        $output->writeln("Total email sent : ".$countEmails);
    }
}