<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PriceExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('clientPrice',[$this,'calculateClientPrice']),
            new TwigFilter('json_decode',[$this,'jsonDecode']),
        ];
    }

    public function calculateClientPrice($price, $margin)
    {
        return $price/(100 - $margin) * 100;
    }

    public function jsonDecode($string)
    {
        return json_decode($string);
    }
}