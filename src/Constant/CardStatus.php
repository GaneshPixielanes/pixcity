<?php

namespace App\Constant;

class CardStatus
{
    const CREATED = "created";
    const SAVED = "saved";
    const SUBMITTED = "submitted";
    const VALIDATED = "validated";
    const REFUSED = "refused";

    /**
     * @return array label => value
     */
    public static function getList(){
        return [
            "label.cardstatus.created" => CardStatus::CREATED,
            //"label.cardstatus.saved" => CardStatus::SAVED,
            "label.cardstatus.submitted" => CardStatus::SUBMITTED,
            "label.cardstatus.validated" => CardStatus::VALIDATED,
            "label.cardstatus.refused" => CardStatus::REFUSED,
        ];
    }


    /**
     * @return string
     */
    public static function getLabel($key){
        switch($key){
            case CardStatus::CREATED: return "A faire";
            case CardStatus::SAVED: return "Sauvegardée";
            case CardStatus::SUBMITTED: return "En validation";
            case CardStatus::VALIDATED: return "Validée";
            case CardStatus::REFUSED: return "Refusée";
            default: return "-";
        }
    }


}
