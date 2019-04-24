<?php

namespace App\Constant;

class PaymentStatus
{
    const PENDING = "pending";
    const TOPAY_BY_CHECK = "topay";
    const TOPAY_BY_BANKTRANSFER = "topay_by_banktransfer";
    const PAYED_BY_CHECK = "payed_by_check";
    const PAYED_BY_BANKTRANSFER = "payed_by_banktransfer";

    /**
     * @return array label => value
     */
    public static function getList(){
        return [
            "label.paymentstatus.pending" => PaymentStatus::PENDING,
            "label.paymentstatus.topay" => PaymentStatus::TOPAY_BY_CHECK,
            "label.paymentstatus.payed_by_check" => PaymentStatus::PAYED_BY_CHECK,
            "label.paymentstatus.payed_by_banktransfer" => PaymentStatus::PAYED_BY_BANKTRANSFER,
        ];
    }
}