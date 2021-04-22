<?php

namespace app\constant\en;

abstract class Responses
{
    public static function UNKNOWN_ERROR($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Internal Server Error',
            'message' => $message,
            'code' => 500
        ];
    }

    public static function UNAUTHORIZED_ACCESS($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Unauthorized Access',
            'message' => $message,
            'code' => 401
        ];
    }

    public static function BAD_REQUEST($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Bad Request',
            'message' => $message,
            'code' => 400
        ];
    }

    public static function PAGE_NOT_FOUND($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Page Not Found',
            'message' => $message,
            'code' => 404
        ];
    }

    public static function OK($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Success',
            'message' => $message,
            'code' => 200
        ];
    }

    public static function SERVICE_UNAVAILABLE($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Service Unavailable',
            'message' => $message,
            'code' => 500
        ];
    }

    public static function VALIDATION_ERROR($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Validation Error',
            'message' => $message,
            'code' => 400,
        ];
    }

    public static function MISSING_FIELD($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Missing Field',
            'message' => $message,
            'code' => 400,
        ];
    }

    public static function INVALID_FIELD($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Invalid Field',
            'message' => $message,
            'code' => 400,
        ];
    }

    public static function METHOD_NOT_ALLOWED($message = Constants::DEFAULT_RESPONSE_MESSAGE)
    {
        return [
            'title' => 'Method Not Allowed',
            'message' => $message,
            'code' => 405,
        ];
    }
}