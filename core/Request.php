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
    
}