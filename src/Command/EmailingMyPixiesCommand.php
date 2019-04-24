<?php

namespace App\Command;

use App\Entity\Card;
use App\Entity\User;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EmailingMyPixiesCommand extends Command
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
            ->setName("app:emailing:mypixies")
            ->setDescription("Emailing - My Pixies' activity");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $countEmails = 0;

        $userRepo = $this->em->getRepository(User::class);
        $cardRepo = $this->em->getRepository(Card::class);

        $users = $userRepo->searchUserFollowingPixies();

        foreach($users as $user){
            if($user instanceof User) {

                $pixies = $user->getFavoritePixies();
                $pixiesCards = [];

                foreach($pixies as $pixie){
                    if($pixie instanceof User) {
                        $filters = [
                            "pixie" => $pixie->getId(),
                            "lastWeek" => true
                        ];

                        $cards = $cardRepo->search($filters);
                        $pixiesCards = array_merge($pixiesCards, $cards);
                    }
                }

                if(count($pixiesCards) > 0){

                    $this->mailer->send($user->getEmail(), 'L\'activité de vos Pixies', 'emails/user-pixies-activity.html.twig', [
                        'firstname' => $user->getFirstname(),
                        'cards' => $pixiesCards
                    ]);

                    $countEmails++;

                }

            }
        }

        $output->writeln("Total email sent : ".$countEmails);
    }
}