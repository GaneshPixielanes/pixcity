<?php

namespace App\Constant;

class CompanyStatus
{
    const COMPANY = "company";
    const ONGOING_REGISTRATION = "individualregistration";
    const SELF_EMPLOYED = "selfemployed";
    const MICRO_ENTREPRENEUR_TVA = "microentrepreneurtva";
    const MICRO_ENTREPRENEUR = "microentrepreneur";

    /**
     * @return array label => value
     */
    public static function getList(){
        return [
            "label.statusname.company" => CompanyStatus::COMPANY,
            "label.statusname.microentrepreneur" => CompanyStatus::MICRO_ENTREPRENEUR,
            "label.statusname.microentrepreneurtva" => CompanyStatus::MICRO_ENTREPRENEUR_TVA,
            "label.statusname.individualregistration" => CompanyStatus::ONGOING_REGISTRATION
        ];
    }
}