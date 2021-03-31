<?php

namespace app\constant;

abstract class Responses
{
    public static function UNKNOWN_ERROR($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Sunucu Hatası',
            'message' => $message,
            'code' => 500
        ];
    }

    public static function UNAUTHORIZED_ACCESS($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Erişim Kısıtlandı',
            'message' => $message,
            'code' => 401
        ];
    }

    public static function BAD_REQUEST($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Hatalı İstek',
            'message' => $message,
            'code' => 400
        ];
    }

    public static function SUCCESS($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Başarılı',
            'message' => $message,
            'code' => 200
        ];
    }

    public static function SERVICE_UNAVAILABLE($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Servis Erişilebilir Değil',
            'message' => $message,
            'code' => 500
        ];
    }

    public static function VALIDATION_ERROR($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Doğrulama Hatası',
            'message' => $message,
            'code' => 400,
        ];
    }

    public static function MISSING_FIELD($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Eksik Alan',
            'message' => $message,
            'code' => 400,
        ];
    }
}