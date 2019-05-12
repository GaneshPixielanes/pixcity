<?php
namespace App\Constant;

class BusinessType
{
    const TYPE1 = 'business_type_1';
    const TYPE2 = 'business_type_2';
    const TYPE3 = 'business_type_3';
    const TYPE4 = 'business_type_4';

    public static function getList()
    {
        return [
            "Business Type 1" => BusinessType::TYPE1,
            "Business Type 2" => BusinessType::TYPE2,
            "Business Type 3" => BusinessType::TYPE3,
            "Business Type 4" => BusinessType::TYPE4,
        ];
    }

}