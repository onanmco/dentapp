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

    /**
     * Split given regular expression in two parts
     * 1st part represents the string between forward slashes
     * 2nd part represents the flag
     * 
     * @param string
     * @return array
     */
    public static function parseRegex($regex)
    {
        $flag = false;
        if (preg_match('/^\/(?P<regex>.+)\/(?P<flag>.+)$/', $regex, $matches)) {
            $flag = $matches['flag'];
            $regex = $matches['regex'];
        }
        return [
            'regex' => $regex,
            'flag' => $flag
        ];
    }
}