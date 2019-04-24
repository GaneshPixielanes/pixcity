<?php

namespace App\Constant;

class CardProjectStatus
{
    const TEMPLATE = "template";
    const CREATED = "created";
    const CANCELLED = "cancelled";
    const ASSIGNED = "assigned";
    const PIXIE_ACCEPTED = "pixie_accepted";
    const PIXIE_REFUSED = "pixie_refused";
    const PIXIE_SUBMITTED = "pixie_submitted";
    const VALIDATED = "validated";
    const REFUSED = "refused";
    const PAYED = "payed";

    /**
     * @return array label => value
     */
    public static function getList(){
        return [
            "label.cardstatus.created" => CardProjectStatus::CREATED,
            "label.cardstatus.cancelled" => CardProjectStatus::CANCELLED,
            "label.cardstatus.assigned" => CardProjectStatus::ASSIGNED,
            "label.cardstatus.pixie_accepted" => CardProjectStatus::PIXIE_ACCEPTED,
            "label.cardstatus.pixie_refused" => CardProjectStatus::PIXIE_REFUSED,
            "label.cardstatus.submitted" => CardProjectStatus::PIXIE_SUBMITTED,
            "label.cardstatus.validated" => CardProjectStatus::VALIDATED,
            "label.cardstatus.refused" => CardProjectStatus::REFUSED,
        ];
    }

    /**
     * @return string
     */
    public static function getLabel($key){
        switch($key){
            case CardProjectStatus::CREATED: return "Créée";
            case CardProjectStatus::ASSIGNED: return "Reçue";
            case CardProjectStatus::PIXIE_ACCEPTED: return "Demande acceptée";
            case CardProjectStatus::PIXIE_REFUSED: return "Demande déclinée";
            case CardProjectStatus::PIXIE_SUBMITTED: return "En validation";
            case CardProjectStatus::VALIDATED: return "Validée";
            case CardProjectStatus::REFUSED: return "Refusée";
            case CardProjectStatus::CANCELLED: return "Annulée";
            case CardProjectStatus::PAYED: return "Payée";
            default: return "-";
        }
    }
}