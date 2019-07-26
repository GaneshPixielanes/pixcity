<?php

namespace App\Controller\Front;

use App\Entity\Page;
use App\Repository\FaqsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/faq", name="front_faq_")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     */
    public function index(FaqsRepository $faqsRepository)
    {
        #SEO
        $page = new Page();
        $page->setMetaTitle("FAQ de Pix.city Services : La marketplace de community managers et influenceurs locaux freelances ");
        $page->setMetaDescription("Retrouvez toutes les questions que vous vous posez si vous êtes un influenceur, community manager ou un commerce local et que vous utilisez notre service");


        return $this->render('front/faq/index.html.twig', [
            'faqs0' => $faqsRepository->findBy(['category'=>'CLIENT', 'subcategory'=>0]),
            'faqs1' => $faqsRepository->findBy(['category'=>'CLIENT', 'subcategory'=>1]),
            'faqs2' => $faqsRepository->findBy(['category'=>'CLIENT', 'subcategory'=>2]),

            'faqs3' => $faqsRepository->findBy(['category'=>'CLIENT', 'subcategory'=>5]),
            'faqs4' => $faqsRepository->findBy(['category'=>'CLIENT', 'subcategory'=>6]),

            'faqsCm0' => $faqsRepository->findBy(['category'=>'CM', 'subcategory'=>0]),
            'faqsCm1' => $faqsRepository->findBy(['category'=>'CM', 'subcategory'=>1]),
            'faqsCm2' => $faqsRepository->findBy(['category'=>'CM', 'subcategory'=>2]),

            'faqsCm3' => $faqsRepository->findBy(['category'=>'CM', 'subcategory'=>3]),
            'faqsCm4' => $faqsRepository->findBy(['category'=>'CM', 'subcategory'=>4]),
            'page' => $page
        ]);
    }
}
