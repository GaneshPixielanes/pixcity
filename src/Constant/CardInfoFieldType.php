<?php

namespace App\Constant;

class CardInfoFieldType
{
    const TEXT = "text";
    const PRICE = "price";
    const DATE = "date";
    const TIME = "time";
    const DATETIME = "datetime";
    const PHONE = "phone";
    const EMAIL = "email";
    const LINK = "link";

    /**
     * @return array label => value
     */
    public static function getList(){
        return [
            "label.cardinfo.type.text" => CardInfoFieldType::TEXT,
            "label.cardinfo.type.price" => CardInfoFieldType::PRICE,
            "label.cardinfo.type.date" => CardInfoFieldType::DATE,
            "label.cardinfo.type.time" => CardInfoFieldType::TIME,
            "label.cardinfo.type.phone" => CardInfoFieldType::PHONE,
            "label.cardinfo.type.email" => CardInfoFieldType::EMAIL,
            "label.cardinfo.type.link" => CardInfoFieldType::LINK,
        ];
    }

    public static function getIconByType($type){
        switch($type){
            case CardInfoFieldType::TEXT: $icon = "fa-align-justify"; break;
            case CardInfoFieldType::PRICE: $icon = "fa-euro-sign"; break;
            case CardInfoFieldType::DATE: $icon = "fa-calendar-alt"; break;
            case CardInfoFieldType::TIME: $icon = "fa-clock"; break;
            case CardInfoFieldType::DATETIME: $icon = "fa-calendar-alt"; break;
            case CardInfoFieldType::PHONE: $icon = "fa-phone"; break;
            case CardInfoFieldType::EMAIL: $icon = "fa-at"; break;
            case CardInfoFieldType::LINK: $icon = "fa-link"; break;
            default: $icon = "";
        }
        return $icon;
    }

    public static function getValueByType($type, $value){
        switch($type){
            case CardInfoFieldType::PHONE: $value = "<a href='tel:".$value."'>".$value."</a>"; break;
            case CardInfoFieldType::EMAIL: $value = "<a href='mailto:".$value."'>".$value."</a>"; break;
            case CardInfoFieldType::LINK: $value = "<a href='".$value."' target='_blank'>".$value."</a>"; break;
        }
        return $value;
    }
}