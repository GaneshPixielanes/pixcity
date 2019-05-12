<?php

namespace App\Controller\B2B;

use App\Repository\PackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/b2b/pack", name="b2b_pack_")
 */
class PackController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function index(PackRepository $packRepo)
    {
        $packs = $packRepo->findAll();
        return $this->render('b2b/pack/index.html.twig', [
            'packs' => $packs,
        ]);
    }
}
