<?php
namespace App\Twig;

use App\Constant\CompanyStatus;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ClientPriceExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price',[$this,'formatClientPrice']),
        ];
    }

    public function formatClientPrice(int $price,$margin,$tax,$citymakerType)
    {

        $clientPrice = 0;
        if($citymakerType){
            $result['client_price'] = round((100 * $price)/(100 - $margin));
            $result['client_tax'] = 0;
            $clientPrice = $result['client_price'];
        }else{
            $result['client_price'] = round($price/(100 - $margin) * 100);
            $result['client_tax'] = $result['client_price'] * $tax/100;
            $clientPrice = $result['client_price'] + $result['client_tax'];
        }

        return $clientPrice;

    }


}