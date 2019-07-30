<?php

namespace App\Controller\B2B;

use App\Entity\UserMission;
use App\Repository\MissionPaymentRepository;
use App\Repository\OptionRepository;
use App\Repository\UserMissionRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/download", name="download")
 */

class DownloadController extends Controller
{
    /**
     * @Route("/quatation/{id}/{path}", name="quatation")
     */
    public function quotation($id,$path,Filesystem $filesystem,UserMissionRepository $missionRepository,MissionPaymentRepository $missionPaymentRepository,OptionRepository $options)
    {
        $file_path = base64_decode($path);

        if($filesystem->exists($file_path)){

            return $this->downloadFile($file_path);

        }else{

            $mission = $missionRepository->activePrices($id);

            $cityMakerType = $mission->getUser()->getPixie()->getBilling()->getStatus();

            $missionLog = $mission->getActiveLog();

            $price = $missionLog->getUserBasePrice();

            $tax = $options->findOneBy(['slug' => 'tax']);
            $margin = $options->findOneBy(['slug' => 'margin']);

            $margin = $margin->getValue();
            $tax = $tax->getValue();

            $filesystem->mkdir('uploads/missions/temp/'.$mission->getId(),0777);

            $filename = 'PX-'.$mission->getId().'-'.$missionLog->getId().".pdf";

            $clientInvoicePath = "uploads/missions/temp/".$mission->getId().'/'.$filename;


            $last_result = $missionPaymentRepository->getPrices($price, $margin, $tax, $cityMakerType);

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/client_quotation.html.twig',
                    array(
                        'mission' => $mission,
                        'missionLog' => $missionLog,
                        'last_result' => $last_result
                    )
                ), $clientInvoicePath
            );

            $file_path = 'uploads/missions/temp/'.$mission->getId().'/'.$filename;

            return $this->downloadFile($file_path);


        }



    }

    /**
     * @Route("/invoice/{id}/{path}/{type}", name="invoice")
     */
    public function invoice($id,$path,$type,UserMissionRepository $missionRepository,Filesystem $filesystem)
    {
        $file_path = base64_decode($path);

        if($filesystem->exists($file_path)){

           return $this->downloadFile($file_path);

        }else{

            $mission = $missionRepository->activePrices($id);

            $filesystem->mkdir('invoices/'.$mission->getId(),0777);

            $client_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-".$type.".pdf";

            $clientInvoicePath = "invoices/".$mission->getId().'/'.$client_filename;

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/client_invoice.html.twig',
                    array(
                        'mission' => $mission
                    )
                ), $clientInvoicePath
            );

            $this->downloadFile($clientInvoicePath);

        }



    }




    /**
         * @Route("/generate-invoice/{id}", name="generate_invoice")
     */
    public function generateInvoiceDownload($id,UserMissionRepository $missionRepository,Filesystem $filesystem)
    {

        $mission = $missionRepository->activePrices($id);

        $datetime = new \DateTime(strtotime('d-m-Y-h:i:s'));

        $time_folder = $datetime->format('d-m-Y-h-i-s');

        $path = 'invoices/'.$mission->getId();

        $filesystem->mkdir($path,0777);

        //Client invoice creation

        $client_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-client.pdf";

        $clientInvoicePath = $path.'/'.$client_filename;

        if($filesystem->exists($clientInvoicePath)){

            $client_filename_old = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId().'-'.$time_folder."-client.pdf";

            $clientInvoicePath_old = $path.'/'.$client_filename_old;

            $filesystem->rename($clientInvoicePath, $clientInvoicePath_old);

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/client_invoice.html.twig',
                    array(
                        'mission' => $mission
                    )
                ), $clientInvoicePath
            );

        }else{

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/client_invoice.html.twig',
                    array(
                        'mission' => $mission
                    )
                ), $clientInvoicePath
            );

        }

        //City Maker invoice creation
        $cm_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-cm.pdf";

        $cmInvoicePath = $path.'/'.$cm_filename;

        if($filesystem->exists($cmInvoicePath)){

            $cm_filename_old = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId().'-'.$time_folder."-cm.pdf";

            $cmInvoicePath_old = $path.'/'.$cm_filename_old;

            $filesystem->rename($cmInvoicePath, $cmInvoicePath_old);

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/cm_invoice.html.twig',
                    array(
                        'mission' => $mission
                    )
                ), $cmInvoicePath
            );

        }else{

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/cm_invoice.html.twig',
                    array(
                        'mission' => $mission
                    )
                ), $cmInvoicePath
            );


        }

        //PCS Maker invoice creation
        $pcs_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-pcs.pdf";

        $pcsInvoicePath = "invoices/".$mission->getId().'/'.$pcs_filename;

        if($filesystem->exists($pcsInvoicePath)){

            $pcs_filename_old = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId().'-'.$time_folder."-pcs.pdf";

            $pcsInvoicePath_old = $path.'/'.$pcs_filename_old;

            $filesystem->rename($cmInvoicePath, $pcsInvoicePath_old);

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/pcs_invoice.html.twig',
                    array(
                        'mission' => $mission
                    )
                ), $pcsInvoicePath
            );

        }else{

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/pcs_invoice.html.twig',
                    array(
                        'mission' => $mission
                    )
                ), $pcsInvoicePath
            );

        }

        return new JsonResponse(['success' => true, 'message' => 'generation of new invoices is done,please check the file path '.$path]);


    }


    /**
     * @Route("/re-generate-invoice/{id}", name="re_generate_invoice")
     */
    public function reGenerateInvoiceDownload($id,UserMissionRepository $missionRepository,Filesystem $filesystem)
    {

        $mission = $missionRepository->activePrices($id);

        $datetime = new \DateTime(strtotime('d-m-Y-h:i:s'));

        $time_folder = $datetime->format('d-m-Y-h-i-s');

        $path = 'invoices/regenerations/'.$mission->getId().'/'.$time_folder;

        $filesystem->mkdir($path,0777);

        $client_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-client.pdf";

        $clientInvoicePath = $path.'/'.$client_filename;

        if(!$filesystem->exists($clientInvoicePath)){

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/client_invoice.html.twig',
                    array(
                        'mission' => $mission
                    )
                ), $clientInvoicePath
            );

        }

        $cm_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-cm.pdf";

        $cmInvoicePath = $path.'/'.$cm_filename;

        if(!$filesystem->exists($cmInvoicePath)){

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/cm_invoice.html.twig',
                    array(
                        'mission' => $mission
                    )
                ), $cmInvoicePath
            );

        }


        $pcs_filename = 'PX-'.$mission->getId().'-'.$mission->getActiveLog()->getId()."-pcs.pdf";

        $pcsInvoicePath = $path.'/'.$pcs_filename;

        $this->container->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView('b2b/invoice/pcs_invoice.html.twig',
                array(
                    'mission' => $mission
                )
            ), $pcsInvoicePath
        );

        return new JsonResponse(['success' => true, 'message' => 'generation of new invoices is done,please check the file path '.$path]);

    }


    public function downloadFile($path){

        $response = new BinaryFileResponse($path);

        $response->headers->set('Content-Type','text/plain');

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT
            );

        return $response;

    }
}
