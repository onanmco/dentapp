<?php

namespace app\utility;

use core\Request;

class UtilityFunctions
{
    public static function turkish_lowercase($str)
    {
        $lowercase = array('ç', 'ğ', 'ı', 'i', 'ö', 'ş', 'ü');
        $uppercase = array('Ç', 'Ğ', 'I', 'İ', 'Ö', 'Ş', 'Ü');
        $str = str_replace($uppercase, $lowercase, $str);
        return mb_strtolower($str);
    }

    public static function turkish_uppercase($str)
    {
        $lowercase = array('ç', 'ğ', 'ı', 'i', 'ö', 'ş', 'ü');
        $uppercase = array('Ç', 'Ğ', 'I', 'İ', 'Ö', 'Ş', 'Ü');
        $str = str_replace($lowercase, $uppercase, $str);
        return mb_strtoupper($str);
    }

    public static function turkish_uc_first($str)
    {
        $first_letter = substr($str, 0, 1);
        $rest = substr($str, 1);
        return self::turkish_uppercase($first_letter) . $rest;
    }
}