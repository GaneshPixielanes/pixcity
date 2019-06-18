<?php

namespace App\Constant;

class MissionStatus
{
    const CREATED = "created";
    const ONGOING = "ongoing";
    const COMPLETED = "completed";
    const TERMINATED = "terminated";
    const CANCELLED = "cancelled";
    const CANCEL_REQUEST_INITIATED = "cancel_request_cm";
    const CANCEL_REQUEST_INITIATED_CLIENT = "cancel_request_client";
    const TERMINATE_REQUEST_INITIATED = "terminate_request_cm";
    const TERMINATE_REQUEST_INITIATED_CLIENT = "terminate_request_client";
    const CLIENT_DECLINED = "client_declined";

    /**
     * @return array label => value
     */
    public static function getList(){
        return [
            "b2b.label.missionstatus.created" => MissionStatus::CREATED,
            //"label.cardstatus.saved" => CardStatus::SAVED,
            "b2b.label.ongoing" => MissionStatus::ONGOING,
            "b2b.label.terminated" => MissionStatus::TERMINATED,
            "b2b.label.terminated" => MissionStatus::TERMINATE_REQUEST_INITIATED,
            "b2b.label.terminated" => MissionStatus::TERMINATE_REQUEST_INITIATED_CLIENT,
            "b2b.label.completed" => MissionStatus::COMPLETED,
            "b2b.label.cancelled" => MissionStatus::CANCEL_REQUEST_INITIATED,
            "b2b.label.cancelled" => MissionStatus::CANCEL_REQUEST_INITIATED_CLIENT,
            "b2b.label.cancelled" => MissionStatus::CANCELLED,
            "b2b.label.client_declined" => MissionStatus::CLIENT_DECLINED,
        ];
    }
}