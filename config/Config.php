<?php

namespace config;

class Config
{
    const DATABASE = [
        'HOST' => 'localhost',
        'PORT' => '3306',
        'NAME' => 'mvc',
        'USER' => 'cem',
        'PASS' => '1234'
    ];

    const SHOW_ERRORS = false;

    const SECRET_KEY = '2fd9d2364580b0f6ebeff9a1d58993c1';

    const CLIENT_APP_NAME = 'DentApp';
    const CLIENT_EMAIL = 'info@dentapp.com';
    const CLIENT_PHONE = '+90 (850) 000 00 00';
}