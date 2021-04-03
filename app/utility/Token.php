<?php

namespace app\utility;

use config\Config;

class Token
{
    private $value = '';

    public function __construct($value = null)
    {
        $this->value = $value !== null ? $value : bin2hex(random_bytes(16));
    }

    public function getValue()
    {
        return $this->value;
    }
    
    public function getHash()
    {
        return hash_hmac('sha256', $this->value, Config::SECRET_KEY);
    }
}