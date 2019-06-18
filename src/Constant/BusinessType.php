<?php
namespace App\Constant;

class BusinessType
{
    const SAS = 'SAS';
    const SARL = 'SARL';
    const EURL = 'EURL';
    const SASU = 'SASU';

    public static function getList()
    {
        return [
            "SAS" => BusinessType::SAS,
            "SARL" => BusinessType::SARL,
            "EURL" => BusinessType::EURL,
            "SASU" => BusinessType::SASU,
        ];
    }

}