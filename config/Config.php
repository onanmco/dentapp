<?php

namespace config;

class Config
{
    const DATABASE = [
        'HOST' => 'database',
        'PORT' => '3306',
        'NAME' => 'mvc',
        'USER' => 'mvcuser',
        'PASS' => '1234'
    ];

    const SHOW_ERRORS = false;

    const SECRET_KEY = '2fd9d2364580b0f6ebeff9a1d58993c1';

    const CLIENT_APP_NAME = 'DentApp';
    const CLIENT_DOMAIN = 'dentapp.com';
    const CLIENT_EMAIL = 'info@' . self::CLIENT_DOMAIN;
    const CLIENT_EMAIL_OUTGOING = 'noreply@' . self::CLIENT_DOMAIN;
    const CLIENT_EMAIL_INCOMING = 'info@' . self::CLIENT_DOMAIN;
    const CLIENT_PHONE = '+90 (850) 000 00 00';

    const MAILGUN_BASE_URL = 'https://api.mailgun.net/v3/{DOMAIN}';
    const MAILGUN_API_KEY = '{API_KEY}';

    const DEFAULT_LANGUAGE = 'tr';
}