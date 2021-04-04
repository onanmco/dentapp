<?php

namespace core;

use app\utility\Auth;
use Exception;

abstract class View
{
    /**
     * Render the view file
     * 
     * @param string $file - file name
     * @param array $args - Arguments of the current route
     * 
     * @return void
     * 
     * @throws Exception
     */
    public static function render($file, $args = [])
    {
        $language = Auth::getUserLanguage();
        // $file = '../app/view/' . $file;

        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $file;

        if (is_readable($file)) {
            extract($args);
            try {
                ob_start();
                require($file);
                ob_flush();
            } catch (\Throwable $th) {
                ob_end_clean();
                throw $th;
            }       
        } else {
            throw new Exception("View file: \"$file\"  cannot be found.", 500);
        }
    }
}