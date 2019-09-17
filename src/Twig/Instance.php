<?php

namespace App\Twig;

use App\Entity\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

Class Instance extends AbstractExtension{

    public function getFilters()
    {
        return [
            new TwigFilter('instant',[$this,'instant']),
        ];
    }

    public function instant($entity)
    {
        return $entity instanceof User;
    }
}