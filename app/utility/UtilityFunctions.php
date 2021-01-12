<?php

namespace app\utility;

class UtilityFunctions
{
    public static function sanitizeName($name)
    {
        $name = trim($name, ' ');
        $name = preg_replace('/\s\s+/', ' ', $name);
        return $name;
    }
}