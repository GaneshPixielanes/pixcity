<?php

namespace App\Constant;

class TransactionType
{
    const BANKTRANSFER = "banktransfer";
    const CHECK = "check";

    /**
     * @return array label => value
     */
    public static function getList(){
        return [
            "label.transactiontype.banktransfer" => TransactionType::BANKTRANSFER,
            "label.transactiontype.CHECK" => TransactionType::CHECK,
        ];
    }
}