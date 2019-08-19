<?php

namespace App\Controller\B2B;

use App\Constant\MissionStatus;
use App\Entity\Page;
use App\Repository\MissionRepository;
use App\Repository\NotificationsRepository;
use App\Repository\OptionRepository;
use App\Repository\UserMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/city-maker/factures", name="b2b_community_manager_invoice_")
 */
class InvoiceController extends Controller
{
    /**
     * @Route("", name="list")
     */
    public function index(UserMissionRepository $missionRepo, OptionRepository $optionsRepo, NotificationsRepository $notificationsRepo)
    {

        if($this->getUser()->getB2bCmApproval() != 1)
        {
            return $this->redirectToRoute('front_homepage_index');
        }

        $missions = $missionRepo->findBy([
           'user' => $this->getUser(),
           'status' => MissionStatus::TERMINATED
        ]);

        #SEO
        $page = new Page();
        $page->setMetaTitle('Pix.city Services : liste des factures');
        $page->setMetaDescription('Retrouvez dans cet espace toutes les factures de vos missions avec vos clients');

        return $this->render('b2b/invoice/index.html.twig', [
            'missions' => $missions,
            'tax' => $optionsRepo->findOneBy(['slug' => 'margin']),
            'notifications' => $notificationsRepo->findBy([
                'unread' => 1,
                'user' => $this->getUser()
            ]),
            'page' => $page
        ]);
    }

    /**
     * @Route("/generate/{id}", name="generate")
     */
        public function generate($id, UserMissionRepository $missionRepo)
        {
            if($this->getUser()->getB2bCmApproval() != 1)
            {
                return $this->redirectToRoute('front_homepage_index');
            }

        $user = $this->getUser();

        $mission = $missionRepo->findBy([
            'user' => $user,
            'id' => $id,
            'status' => MissionStatus::TERMINATED
        ]);

        if(empty($mission))
        {
            return $this->redirectToRoute('b2b_community_manager_invoice_list');
        }


        return $this->render('b2b/invoice/preview.html.twig',[
            'mission' => $mission[0]
        ]);

    }

    /**
     * @Route("/preview/{id}",name="preview")
     */
    public function preview($id, UserMissionRepository $missionRepo, Request $request,Filesystem $filesystem)
    {
        $user = $this->getUser();
        $mission = $missionRepo->findOneBy([
            'user' => $user,
            'id' => $id,
            'status' => MissionStatus::TERMINATED
        ]);


        $fileName = "PX-".$mission->getId()."-".$mission->getActiveLog()->getId()."-client.pdf";

        $file_path = 'invoices/'.$mission->getId().'/'.$fileName;

        if($filesystem->exists($file_path)){


            $result = "http".(isset($_SERVER['HTTPS']) ? "s" : null).'://'.$_SERVER["HTTP_HOST"].'/invoices/'.$mission->getId().'/'.$fileName;


            return new JsonResponse(['url' => $result]);

        }else{

            $mission = $missionRepo->activePrices($id);

            $filesystem->mkdir('invoices/'.$mission->getId(),0777);

            $client_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-client.pdf";

            $clientInvoicePath = "invoices/".$mission->getId().'/'.$client_filename;

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/client_invoice.html.twig',
                    array(
                        'mission' => $mission
                    )
                ), $clientInvoicePath
            );

            $result = "http".(isset($_SERVER['HTTPS']) ? "s" : null).'://'.$_SERVER["HTTP_HOST"].'/invoices/'.$mission->getId().'/'.$fileName;

            return new JsonResponse(['url' => $result]);

        }


//
//        if(empty($mission))
//        {
//            return $this->redirectToRoute('b2b_community_manager_invoice_list');
//        }
//
//        return $this->render('b2b/shared/_preview.html.twig',[
//            'mission' => $mission[0]
//        ]);
    }
}
