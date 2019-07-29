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


    public function downloadFile($path){

        $response = new BinaryFileResponse($path);

        $response->headers->set('Content-Type','text/plain');

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            );

        return $response;

    }
}
