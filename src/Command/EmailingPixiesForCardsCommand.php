<?php
namespace App\Command;

use App\Entity\CardProject;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EmailingPixiesForCardsCommand extends Command
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
            ->setName("app:emailing:cardsrequestreminder")
            ->setDescription("Emailing - Remind Pixies to Request for Cards");
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $users = $this->em->getRepository(User::class);
       $users = $users->findBy(['visible' => 1]);
        $count = 0;
        foreach($users as $key => $pixie)
        {
            $project = $pixie->getProjects();
            $createdAMonthAgo = false;

            foreach($project as $proj)
            {
                $createdAt = $proj->getCreatedAt()->format('Ymd');
                $date = new \DateTime('-30 days');

                if($createdAt < $date->format('Ymd'))
                {
                    $createdAMonthAgo = true;
                }
                else
                {
                    $createdAMonthAgo = false;
                }
            }

            if($createdAMonthAgo)
            {
                //Send mail here
                $mail =$pixie->getEmail();
                $mail = 'pixcitytestmails@yopmail.com'; // Comment these for live
                $this->mailer->send($mail, 'Tu nous manques!', 'emails/pixie-card-request-reminder.html.twig', [
                    'firstname' => $pixie->getFirstname(),
                    'lastname' => $pixie->getLastname(),
                    'id' => $pixie->getId(),
                ]);
                $count++;
            }
        }
        $output->writeln("Number of times email has been sent :".$count);
    }
}