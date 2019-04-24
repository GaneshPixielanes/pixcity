<?php

namespace App\Controller\Api;

use App\Constant\CardProjectStatus;
use App\Entity\CardProject;
use App\Repository\CardProjectRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cron",name="cron_")
 */
class MailController extends AbstractController
{
    /**
     * @Route("/mail", name="mail")
     */
    public function index()
    {
        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }


    /**
     * @Route("/card-deadline-reminder-mail",name="card_deadline_reminder_mail")
     */
    public function cardDeadlineReminder(CardProjectRepository $project, Mailer $mailer)
    {
        $date = new \DateTime('+1 days'); // Deadline

        // The projects must have tomorrow as the deadline and "City Maker Accepted" as the status
        $filters = [
                        'delivery_date' => $date,
                        'status' => CardProjectStatus::PIXIE_ACCEPTED
                    ];
        // Get the projects with tomorrow's deadline
        $projects = $project->search($filters);
        if(!empty($projects))
        {
            // If there are any projects matching the criteria, send the mail
            foreach($projects as $project)
            {            

                $to = $project->getPixie()->getEmail();
                // $to = 'ganeshcrontest@yopmail.com';

                $mailer->send($to, '24 heures avant expiration de ta card ', 'emails/pixie-card-reminder.html.twig', [
                    'firstname' => $project->getPixie()->getFirstname(),
                    'project' => $project
                ],null,
                'redaction@pix.city');
            }

            return new JsonResponse(
                json_encode(
                    [
                        'status' => true,
                        'message' => count($projects).' Project reminders has been sent on '.date('Y-m-d H:i:s')
                    ]
                )
            );
        }
        return new JsonResponse(
            json_encode(
                [
                    'status' => false,
                    'message' => 'No e-mail has been sent on '.date('Y-m-d H:i:s')
                ]
            )
        );
    }

    /**
     * @Route("/no-card-assigned-for-month", name="no_card_assigned_for_month")
     */
    public function NoCardAssignedForMonth(UserRepository $userRepository, Mailer $mailer)
    {
        $date = new \DateTime('-30 days');
        // List of cityMakers
        $cityMakers = $userRepository->searchPixies();
        $count = 0;

        foreach($cityMakers as $cityMaker)
        {
            // Get the last project created for the "city-maker"
            $project = $cityMaker->getProjects()->last();
            // If the last project sent by the city maker is over a month old, send them a reminder e-mail
            if($project instanceof CardProject && $project->getCreatedAt() < $date)
            {
                echo "--mail--";
                $to =$project->getPixie()->getEmail();
                $mailer->send($to, 'Tu nous manques!', 'emails/pixie-card-request-reminder.html.twig', [
                    'firstname' => $project->getPixie()->getFirstname(),
                    'lastname' => $project->getPixie()->getLastname(),
                    'id' => $project->getPixie()->getId(),
                ],null,
							'redaction@pix.city');
                $count++;
            }
        }

        if($count > 0)
        {
            return new JsonResponse(
                json_encode(
                    [
                        'status' => true,
                        'message' => $count.' reminders has been sent to propose Cards on '.date('Y-m-d H:i:s')
                    ]
                )
            );
        }
        return new JsonResponse(
            json_encode(
                [
                    'status' => false,
                    'message' => 'No proposal request reminders has been sent on '.date('Y-m-d H:i:s')
                ]
            )
        );
    }

    /**
     * @Route("/card-not-accepted-yet",name="card_not_accepted_yet")
     */
    public function cardNotAcceptedReminder(CardProjectRepository $cardProjectRepository, Mailer $mailer)
    {
        // List of projects that has been assigned to the city makers
        $projects = $cardProjectRepository->findBy(['status' => CardProjectStatus::ASSIGNED]);
        // Get the deadline
        $date = new \DateTime('-3 days');

        // Mail counter; tracks the number of mails that has been sent
        $count = 0;
        foreach($projects as $project)
        {
            // If project was created more than 3 days ago, send the reminder mail
            if($project instanceof CardProject && $project->getCreatedAt() <= $date)
            {

                $to = $project->getPixie()->getEmail();

                $mailer->send($to, 'Card attribuée, en attente de ta validation', 'emails/pixie-remind-accept-card.html.twig', [
                    'firstname' => $project->getPixie()->getFirstname(),
                    'lastname' => $project->getPixie()->getLastname(),
                    'id' => $project->getPixie()->getId(),
                ],null,
				'redaction@pix.city');
                //Send mail here
                $count++;
            }
        }

        if($count > 0)
        {
            return new JsonResponse(
                json_encode(
                    [
                        'status' => true,
                        'message' => $count.'  for city-makers who haven\'t accepted projects, on '.date('Y-m-d H:i:s')
                    ]
                )
            );
        }
        return new JsonResponse(
            json_encode(
                [
                    'status' => false,
                    'message' => 'No reminders have been sent for city-makers who haven\'t accepted projects on '.date('Y-m-d H:i:s')
                ]
            )
        );
    }

}
