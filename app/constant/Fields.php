<?php

namespace app\constant;

use core\Error;

class Fields
{
    public function __callStatic($name, $arguments)
    {
        $existing_class_name = ltrim(get_called_class(), '\\');
        $class_name_without_namespace = ltrim(get_called_class(), '\\');
        $last_bs_pos = strrpos(get_called_class(), '\\');
        if ($last_bs_pos) {
            $class_name_without_namespace = substr($class_name_without_namespace, $last_bs_pos + 1);
        }

        $pos = strrpos($existing_class_name, $class_name_without_namespace);
        $class_name = 'en\\' . $class_name_without_namespace;
        if ($pos !== false) {
            $class_name = substr($existing_class_name, 0, $pos) . 'en\\' . substr($existing_class_name, $pos);
        }
        
        try {
            $value = constant($class_name . '::' . $name);
        } catch (\Throwable $th) {
            $value = null;
        }

        if (!$value) {
            return '###ÇEVİRİ_HATASI###';
        }

        return $value;
    }
}