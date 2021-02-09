<?php

namespace app\utility;

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
}