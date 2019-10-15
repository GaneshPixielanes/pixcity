<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FileExtension extends AbstractExtension{

    public function getFilters(){
        return array(
            new TwigFilter('ext', array($this, 'ext')),
        );
    }

    public function ext($filepath){
        $ext = pathinfo($filepath, PATHINFO_EXTENSION);
        return $ext;
    }

}