<?php

namespace App\Controller\Admin\Pages;

use App\Constant\ViewMode;
use App\Repository\ClientMissionProposalMediaRepository;
use App\Repository\ClientMissionProposalRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * @Route("/admin/message/lists", name="admin_message_")
 * @Security("has_role('ROLE_ADMIN')")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ClientMissionProposalRepository $clientMissionProposalRepository,AuthorizationCheckerInterface $authChecker): Response
    {
        $user = $this->getUser();
        if($user->getViewMode() == ViewMode::B2B){
            if($authChecker->isGranted('ROLE_B2C')) {
                return $this->render('admin/b2b/message/index.html.twig', [
                    'message_lists' => $clientMissionProposalRepository->findAll(),
                ]);
            }
            else{
                return $this->render('admin/errorpage/index.html.twig');
            }
        }
        else{
            return $this->render('admin/errorpage/index.html.twig');
        }
    }


    /**
     * @Route("/proposal-document-download/{id}",name="proposal_document_download")
     */
    public function downloadProposalMedia($id, ClientMissionProposalMediaRepository $proposalMediaRepo)
    {
        $media = $proposalMediaRepo->find($id);
        $date = new \DateTime();
        $response = new BinaryFileResponse('uploads/proposals/'.$media->getProposal()->getId().'/'.$media->getName());
        $ext = pathinfo('uploads/proposals/'.$media->getProposal()->getId().'/'.$media->getName(),PATHINFO_EXTENSION);

        $response->headers->set('Content-Type','text/plain');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $media->getName()
        );

        return $response;

    }
}
