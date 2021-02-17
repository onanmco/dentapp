<?php

namespace app\utility;

class Datetime
{
    const MIN = 60;
    const HOUR = self::MIN * 60;
    const DAY = self::HOUR * 24;
    const WEEK = self::DAY * 7;

    /**
     * Returns the unix timestamp of current week's monday 
     * assumed that the start of a week is monday.
     * 
     * @param int $timestamp
     * @return int
     */
    public static function getMonday($timestamp)
    {
        $date_info = getdate($timestamp);
        $day_offset = ($date_info['wday'] == 0) ? 6 : ($date_info['wday']  - 1);
        $current_timestamp = mktime(0, 0, 0, $date_info['mon'], $date_info['mday'], $date_info['year']);
        return $current_timestamp - $day_offset * self::DAY;
    }
}