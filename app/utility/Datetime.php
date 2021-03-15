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
    public static function getFirstDayOfWeek($timestamp)
    {
        $date_info = getdate($timestamp);
        $offset = ($date_info['wday'] == 0) ? 6 : ($date_info['wday']  - 1);
        return mktime(0, 0, 0, $date_info['mon'], $date_info['mday'] - $offset, $date_info['year']);
    }

    /**
     * Returns the unix timestamp of current month's first day
     * 
     * @param int $timestamp
     * @return int
     */
    public static function getFirstDayOfMonth($timestamp)
    {
        $date_info = getdate($timestamp);
        $offset = $date_info['mday'] - 1;
        return mktime(0, 0, 0, $date_info['mon'], $date_info['mday'] - $offset, $date_info['year']);
    }

    /**
     * Returns the unix timestamp of current month's last day
     * 
     * @param int $timestamp
     * @return int
     */
    public static function getLastDayOfMonth($timestamp)
    {
        $date_info = getdate($timestamp);
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $date_info['mon'], $date_info['year']);
        $offset = $days_in_month - $date_info['mday'];
        return mktime(0, 0, 0, $date_info['mon'], $date_info['mday'] + $offset, $date_info['year']);
    }
}