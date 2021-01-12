<?php

function psr0($class_name)
{
    $full_path = dirname(__DIR__) . DIRECTORY_SEPARATOR;    
    $class_name = ltrim($class_name, '\\');
    $last_bs_pos = strrpos($class_name, '\\');
    if ($last_bs_pos) {
        $namespace = substr($class_name, 0, $last_bs_pos);
        $class_name = substr($class_name, $last_bs_pos + 1);
        $full_path .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $full_path .= str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
    if (is_readable($full_path)) {
        require($full_path);
    }
}

spl_autoload_register('psr0');