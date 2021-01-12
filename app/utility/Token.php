<?php

namespace app\utility;

use config\Config;

class Token
{
    private $value = '';
    private $expire_date = '';

    public function __construct($hours = 24, $value = null)
    {
        if ($value !== null) {
            $this->value = $value;
        } else {
            $this->value = bin2hex(random_bytes(16));
        }
        $this->expire_date = time() + 60 * 60 * $hours;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getExpireDate()
    {
        return $this->expire_date;
    }

    public function getHash()
    {
        return hash_hmac('sha256', $this->value, Config::SECRET_KEY);
    }
}