<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig_SimpleFunction;

class FrontExtension extends AbstractExtension
{
    public function getFunctions(){
        return array(
            new Twig_SimpleFunction('instagramFeed', array(FrontRuntime::class, 'instagramFeed')),
            new Twig_SimpleFunction('options', array(FrontRuntime::class, 'options')),
            new Twig_SimpleFunction('menu', array(FrontRuntime::class, 'menu')),
            new Twig_SimpleFunction('regions', array(FrontRuntime::class, 'regions')),
            new Twig_SimpleFunction('categories', array(FrontRuntime::class, 'categories')),
        );
    }
}