<?php

namespace core;

class Response
{
    public static function json($body = [], $code = 200)
    {
        $status = ($code >= 200 && $code < 300) ? 'success' : 'error';
        $payload = [
            'status' => $status,
            'body' => $body
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