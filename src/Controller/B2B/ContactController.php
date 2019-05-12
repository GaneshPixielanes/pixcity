<?php

namespace App\Controller\B2B;

use App\Entity\Contact;
use App\Form\B2B\ContactType;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="b2_b_contact")
     */
    public function index(Request $request, Mailer $mailer)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $contact->setCreatedAt(new \DateTime());
            //Send mail
            $mailer->send('ganesh@pix.city',
                $contact->getFirstName().' '.$contact->getLastName().' has sent the message',
                $contact->getMessage(), [
            ]);

             //Save content
            $em->persist($contact);
            $em->flush();
        }

        return $this->render('b2b/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
