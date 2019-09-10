<?php


namespace App\Constant;


class UserLevel
{
    const LEVEL_1 = 0;
    const LEVEL_2 = 5;
    const LEVEL_3 = 10;
    const LEVEL_4 = 20;
    const LEVEL_5 = '';

    public function getList()
    {
        return [
            "label.level.level_1" => UserLevel::LEVEL_1,
            "label.level.level_2" => UserLevel::LEVEL_2,
            "label.level.level_3" => UserLevel::LEVEL_3,
            "label.level.level_4" => UserLevel::LEVEL_4,
            "label.level.level_5" => UserLevel::LEVEL_5
        ];
    }
}