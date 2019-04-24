<?php

namespace App\Constant;

class TransactionStatus
{
    const PIXIE_ASKED_BANKTRANSFER_PAYMENT = "pixie_asked_banktransfer";
    const PIXIE_ASKED_CHECK_PAYMENT = "pixie_asked_check";
    const BANKTRANSFER_ERROR = "banktransfer_error";
    const BANKTRANSFER_SUCCESS = "banktransfer_success";
    const CHECK_SUCCESS = "check_success";
    const CHECK_ERROR = "check_error";

    /**
     * @return array label => value
     */
    public static function getList(){
        return [
            "label.transactiontype.pixie_asked_banktransfer" => TransactionStatus::PIXIE_ASKED_BANKTRANSFER_PAYMENT,
            "label.transactiontype.pixie_asked_check" => TransactionStatus::PIXIE_ASKED_CHECK_PAYMENT,
            "label.transactiontype.banktransfer_error" => TransactionStatus::BANKTRANSFER_ERROR,
            "label.transactiontype.banktransfer_success" => TransactionStatus::BANKTRANSFER_SUCCESS,
            "label.transactiontype.check_success" => TransactionStatus::CHECK_SUCCESS,
            "label.transactiontype.check_error" => TransactionStatus::CHECK_ERROR,
        ];
    }
}