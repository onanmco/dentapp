<?php

namespace core;

abstract class Request
{
    /**
     * Get the current URI
     * 
     * @return string
     */
    public static function uri()
    {
        $uri = '';
        if (isset($_SERVER['QUERY_STRING'])) {
            $uri = $_SERVER['QUERY_STRING'];
        }
        return self::sanitizeUri($uri);
    }

    /**
     * Removes the query string variables (e.g. &, =) from the uri
     * 
     * @param string $uri
     * 
     * @return string
     */
    public static function sanitizeUri($uri)
    {
        $uri = rtrim($uri, '/');
        if (!empty($uri)) {
            $uri = explode('&', $uri, 2);
            if (strpos($uri[0], '=') === false) {
                $uri = $uri[0];
            } else {
                $uri = '';
            }
        }
        return $uri;
    }

    public static function method()
    {
        $method = '';
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $method = strtolower($_SERVER['REQUEST_METHOD']);
        }
        return $method;
    }

    /**
     * Returns true if the current request URI is from an API client
     *
     * @return bool
     */
    public static function is_from_api()
    {
        return preg_match('/^\/?api/', Request::uri());
    }

    /**
     * Returns true if the current request URI is from a portal user
     *
     * @return bool
     */
    public static function is_from_portal()
    {
        return !preg_match('/^\/?api/', Request::uri());
    }
}