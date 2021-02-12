<?php

namespace core;

class Response
{
    public static function json($data = [], $code = 200)
    {
        $status = ($code >= 200 && $code < 300) ? 'success' : 'failure';
        $payload = [
            'status' => $status,
            'data' => $data
        ];
        self::json_dispatch($payload, $code);
    }

    public static function json_dispatch($payload, $code)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}