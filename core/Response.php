<?php

namespace core;

class Response
{
    public static function json_success($title = '', $body = [], $code = 200)
    {
        $payload = [
            'title' => $title,
            'body' => $body
        ];
        self::json_dispatch($payload, $code);
    }

    public static function json_failure($title = '', $errors = [], $code = 400)
    {
        $payload = [
            'title' => $title,
            'errors' => $errors
        ];
        self::json_dispatch($payload, $code);
    }

    private static function json_dispatch($payload, $code)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}