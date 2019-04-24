<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig_SimpleFunction;

class AdminExtension extends AbstractExtension
{
    public function getFunctions(){
        return array(
            new Twig_SimpleFunction('findPendingCards', array(AdminRuntime::class, 'findPendingCards')),
        );
    }
}