<?php

namespace App\Controller\Admin\Pages;

use App\Entity\Option;
use App\Form\Admin\OptionType;
use App\Repository\CardRepository;
use App\Repository\OptionRepository;
use App\Service\Mailer;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/options", name="admin_options_")
 * @Security("has_role('ROLE_ADMIN')")
 */

class OptionsController extends Controller
{

    /**
     * @Route("", name="list")
     * @Method({"GET"})
     */
    public function index(OptionRepository $options)
    {
        $list = $options->findAll();
        return $this->render('admin/options/index.html.twig', ['list' => $list]);
    }


    /**
     * @Route("/new", name="new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $option = new Option();
        $form = $this->createForm(OptionType::class, $option);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if($option->getSlug() === "") $option->setSlug(null);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($option);
            $entityManager->flush();

            $this->addFlash('success', 'flash.add.success');

            return $this->redirectToRoute('admin_options_list');
        }


        return $this->render('admin/options/form.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Option $option)
    {
        $form = $this->createForm(OptionType::class, $option, ["type"=>"edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($option->getSlug() === "") $option->setSlug(null);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'flash.update.success');
            return $this->redirectToRoute('admin_options_list');
        }

        return $this->render('admin/options/form.html.twig', [
            'item' => $option,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/backup", name="backup")
     * @Method({"GET", "POST"})
     */
    public function backup(Request $request)
    {

        $backupState = $output = NULL;
        $filename = "SQL_Pixielanes_".date("Ymd").".sql";
        $filepath = "../sql/".$filename;

        $command = "mysqldump -u ".$this->getDoctrine()->getConnection()->getUsername()." ".(($this->getDoctrine()->getConnection()->getPassword())?"-p ".$this->getDoctrine()->getConnection()->getPassword():"")." -h ".$this->getDoctrine()->getConnection()->getHost()." ".$this->getDoctrine()->getConnection()->getDatabase()." > ".$filepath;
        exec($command, $output, $backupState);

        if(!$backupState) {
            //$this->addFlash('success', 'flash.backup.success');

            $response = new BinaryFileResponse($filepath);
            $mimeTypeGuesser = new FileinfoMimeTypeGuesser();
            if($mimeTypeGuesser->isSupported()){
                $response->headers->set('Content-Type', $mimeTypeGuesser->guess($filepath));
            }else{
                $response->headers->set('Content-Type', 'text/plain');
            }

            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);

            return $response;
        }else {
            $this->addFlash('error', 'flash.backup.error');
        }

        return $this->redirectToRoute('admin_options_list');
    }

    /**
     * @Route("/email-test", name="emailtest")
     * @Method({"GET", "POST"})
     */
    public function emailtest(Request $request, Mailer $mailer)
    {

        $mailer->send($this->getParameter("email_admin"), 'Validation de votre compte', 'emails/pixie-account-validation.html.twig', [
            'firstname' => "Adrien",
            'token' => "123456789"
        ]);

        return $this->redirectToRoute('admin_options_list');

    }

    /**
     * @Route("/email-template", name="email_template")
     * @Method({"GET", "POST"})
     */
    public function email_template(Request $request, CardRepository $cardRepo)
    {
        return $this->render('emails/user-account-validation.html.twig',['firstname' => 'Adrien', 'token' => '123456']);

        //$cards = $cardRepo->search(["pixie" => 4, "lastWeek" => true]);
        //return $this->render('emails/user-pixies-activity.html.twig',['firstname' => 'Adrien', 'cards' => $cards]);
    }

    /**
     * @Route("/pdf-test", name="pdf_test")
     * @Method({"GET", "POST"})
     */
    public function pdf_test(Request $request, CardRepository $cardRepo)
    {
        $html = "test";

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'file.pdf'
        );
    }

}