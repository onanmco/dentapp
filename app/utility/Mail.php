<?php

namespace app\utility;

use config\Config;

class Mail
{
    public static function send($to, $from, $subject, $content)
    {
        $url = Config::MAILGUN_BASE_URL . '/messages';
        $api_key = Config::MAILGUN_API_KEY;

        $payload = [
            'from'    => $from,
            'to'      => $to,
            'subject' => $subject,
            'text' => $content
        ];
        
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // return json_decode($response);
        return $status_code === 200 ? true : false;
    }
}