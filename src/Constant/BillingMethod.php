<?php

namespace App\Constant;

class BillingMethod
{
    const BANK_TRANSFER = "banktransfer";
    const CHECK = "check";

    /**
     * @return array label => value
     */
    public static function getList(){
        return [
            "label.billingmethod.banktransfer" => BillingMethod::BANK_TRANSFER,
            //"label.billingmethod.check" => BillingMethod::CHECK,
        ];
    }
}