<?php

namespace App\Controller\Api;

use App\Entity\MissionDocument;
use App\Entity\MissionLog;
use App\Entity\UserMission;
use App\Form\B2B\MissionLogType;
use App\Repository\MissionPaymentRepository;
use App\Repository\NotificationsRepository;
use App\Repository\OptionRepository;
use App\Repository\UserMissionRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/city-maker/mission/", name="api_city_maker_mission_")
 */
class CityMakerMissionController extends Controller
{
    /**
     * @Route("upload", name="upload")
     */
    public function upload(Request $request, FileUploader $fileUploader)
    {
        $file = $request->files->get('file');
        $fileName = $fileUploader->upload($file, UserMission::tempFolder(), true);

        return JsonResponse::create(['success' => true, 'fileName' => $fileName,'originalName' => $file->getClientOriginalName()]);
    }//End of upload

    /**
     * @Route("edit/{id}",name="edit")
     */
    public function edit($id,
                         Request $request,
                         UserMissionRepository $missionRepo,
                         OptionRepository $optionRepo,
                         MissionPaymentRepository $missionPaymentRepository,
                         Filesystem $filesystem,
                         NotificationsRepository $notificationsRepository
    )
    {
        $mission = $missionRepo->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);
        if(is_null($mission))
        {
            return $this->redirectToRoute('b2b_community_manager_index');
        }
        // Log the data
        $missionLog = new MissionLog();
        $margin = $optionRepo->findOneBy([
            'slug' => 'margin'
        ])->getValue();

        $tax = $optionRepo->findOneBy(['slug' => 'tax']);

        $form = $this->createForm(MissionLogType::class, $missionLog);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {

            $em = $this->getDoctrine()->getManager();

            $missionLog->setBriefFiles(json_encode($request->get('document')));
            $missionLog->setIsActive(0);
            $missionLog->setMission($mission);
            $missionLog->setCreatedAt(new \DateTime());
            $missionLog->setCreatedBy($this->getUser()->getId());
            $em->persist($missionLog);
            $em->flush();

            foreach($mission->getDocuments() as $document)
            {
                $mission->removeDocument($document);
            }

            foreach($request->get('document') as $key => $document)
            {
                $missionDocument = new MissionDocument();

                $missionDocument->setName($document);
                $missionDocument->setOriginalName($request->get('documentName')[$key]);
                $missionDocument->setCreatedAt(new \DateTime());

                $mission->addDocument($missionDocument);
            }
            $mission->setMissionAgreedClient(1);

            $cityMakerType = $this->getUser()->getPixie()->getBilling()->getStatus();
            $price = $missionLog->getUserBasePrice();


            $tax = $tax->getValue();

//            $transactionFee = 0;
//            $total =  $clientPrice + ($clientPrice * ($tax/100)) + $transactionFee;
            $result = $missionPaymentRepository->getPrices($price, $margin, $tax, $cityMakerType);

            $filesystem->mkdir('uploads/missions/temp/'.$mission->getId(),0777);

            $filename = 'PX-'.$mission->getId().'-'.$missionLog->getId().".pdf";

            $clientInvoicePath = "uploads/missions/temp/".$mission->getId().'/'.$filename;

            $last_result = $result;

            $this->container->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView('b2b/invoice/client_quotation.html.twig',
                    array(
                        'mission' => $mission,
                        'missionLog' => $missionLog,
                        'last_result' => $last_result
                    )
                ), $clientInvoicePath
            );

            $missionLog->setQuotationfile($filename);

            $em->persist($mission);
            $em->persist($missionLog);
            $em->flush();

            /* Notificaton sent to the client informing about the edit*/
            $message = 'CM '.$mission->getUser().'  a édité la mission '.$mission->getTitle().'. Vous devez valider cette nouvelle version pour que le city-maker puisse continuer la mission';
//            $notificationsRepository->insert(null,$mission->getClient(),'edit_mission', 'Vous avez édité la mission '.$mission->getId().'. La nouvelle version de cette mission est en cours de validation côté client.');
            $notificationsRepository->insert(null,$mission->getClient(),'edit_mission', $message, $mission->getId(),$missionLog->getId());

            /* Notification sent to the CM verifying that his edit request has been sent */
            $message = 'Vous avez édité la mission '.$mission->getTitle().'. La nouvelle version de cette mission est en cours de validation côté client.';
            $notificationsRepository->insert($mission->getUser(),null,'edit_mission_cm', $message, $mission->getId(),$missionLog->getId());

            return new JsonResponse(['success' => true,'message' => 'Mission has been updated']);
        }
        return $this->render('b2b/mission/edit-form.html.twig',
            [
                'form' => $form->createView(),
                'mission' => $mission,
                'missionLog' => $mission,
                'margin' => $margin
            ]);
    }
}
