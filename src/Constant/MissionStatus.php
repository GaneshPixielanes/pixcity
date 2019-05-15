<?php

namespace App\Constant;

class MissionStatus
{
    const CREATED = "created";
    const ONGOING = "ongoing";
    const COMPLETED = "completed";
    const TERMINATED = "terminated";
    const CANCELLED = "cancelled";

    /**
     * @return array label => value
     */
    public static function getList(){
        return [
            "b2b.label.missionstatus.created" => MissionStatus::CREATED,
            //"label.cardstatus.saved" => CardStatus::SAVED,
            "b2b.label.missionstatus.ongoing" => MissionStatus::ONGOING,
            "b2b.label.missionstatus.terminated" => MissionStatus::TERMINATED,
            "b2b.label.missionstatus.completed" => MissionStatus::COMPLETED,
            "b2b.label.missionstatus.cancelled" => MissionStatus::CANCELLED,
        ];
    }
}