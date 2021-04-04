<?php

namespace app\constant;

use core\Error;

abstract class Responses
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
        $class_name = 'tr\\' . $class_name_without_namespace;
        if ($pos !== false) {
            $class_name = substr($existing_class_name, 0, $pos) . 'tr\\' . substr($existing_class_name, $pos);
        }
        if (method_exists($class_name, $name)) {
            return call_user_func_array([$class_name, $name], $arguments);
        }

        return [
            'title' => '###ÇEVİRİ_HATASI###',
            'message' => 'İçerik yüklenirken bir sorun oluştu.',
            'code' => 500
        ];
    }
}