<?php

namespace app\utility;

abstract class CommonValidator
{
    /**
     * Check if a string is a valid regexp or not
     * 
     * @param string $string
     * 
     * @return bool true|false - true if the $string is a valid regexp, false otherwise
     */
    public static function isRegexp($string)
    {
        $string = '/' . trim($string, '/') . '/';
        return (!(@preg_match($string, null) === false));
    }
}