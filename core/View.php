<?php

namespace core;

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
        $file = '../app/view/' . $file;

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