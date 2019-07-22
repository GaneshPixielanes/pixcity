<?php

namespace App\Controller\B2B\Client;

use App\Entity\AutoMail;
use App\Entity\ClientMissionProposal;
use App\Entity\ClientMissionProposalMedia;
use App\Entity\Message;
use App\Entity\Ticket;
use App\Form\B2B\ClientMissionProposalType;
use App\Repository\NotificationsRepository;
use App\Repository\UserPacksRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("client/pack", name="b2b_client_pack_")
 * @Security("has_role('ROLE_USER')")
 */
class PackController extends AbstractController
{
    /**
     * @Route("/{id}", name="select")
     */
    public function index($id, UserPacksRepository $packRepo, Request $request, Filesystem $filesystem, NotificationsRepository $notificationsRepository)
    {

        if($request->getSession()->has('chosen_pack_url')){
            $request->getSession()->remove('chosen_pack_url');
        }

        $pack = $packRepo->find($id);
        $proposal = new ClientMissionProposal();

        if(is_null($pack))
        {
            return $this->redirect('client/search');
        }

        $form = $this->createForm(ClientMissionProposalType::class, $proposal);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $proposal->setPack($pack);
            $proposal->setUser($pack->getUser());
            $proposal->setClient($this->getUser());

            $entityManager->persist($proposal);
            $entityManager->flush();

            foreach($proposal->getMedias() as $media)
            {
                # If files are found in the temp folder, then move the files from temp folder.
                # Otherwise check the packs folder and move files from there (import images from packs)
                if($filesystem->exists('uploads/'.ClientMissionProposalMedia::tempFolder().$media->getName()))
                {
                    $filesystem->copy('uploads/'.ClientMissionProposalMedia::tempFolder().$media->getName(),'uploads/'.ClientMissionProposalMedia::uploadFolder().'/'.$proposal->getId().'/'.$media->getName());
                }
            }

//            $template = $this->getDoctrine() ->getRepository(AutoMail::class)->find(1);
//
//            $ticket = new Ticket();
//
//            $ticket->setClient($this->getUser());
//            $ticket->setCm($pack->getUser());
//            $ticket->setTemplateType($template);
//            $ticket->setInitiator('client');
//            $ticket->setObject($request->get('ticket')['Object']);
//            $ticket->setStatus('open');
//            $ticket->setCreatedAt(new \DateTime('now'));
//            $ticket->setUpdatedAt(new \DateTime('now'));
//
//            $em->persist($ticket);
//            $em->flush();
//
//            $message = new Message();
//            $message->setTicket($ticket);
//            $message->setContent($request->get('ticket')['messages']['content']);
//            $message->setType('1');
//            $message->setStatus(1);

            #Send notification
//            $notificationsRepository->insert($pack->getUser(),null,'mission_request', $this->getUser()->getFirstName().' has sent a mission request',0);

            return JsonResponse::create(['success' => true]);
        }

        return $this->render('b2b/client/pack/index.html.twig', [
            'pack' => $pack,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/proposal/upload", name="_upload")
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $file = $request->files->get('file');

        $fileName = $fileUploader->upload($file, ClientMissionProposalMedia::tempFolder(), true);

        return JsonResponse::create(['success' => true, 'fileName' => $fileName]);
    }//End of upload
}
