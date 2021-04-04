<?php

namespace app\constant;

class Constraints
{
    public static function PASSWORD_MIN_LENGTH($field)
    {
        $value = 8;
        return [
            'value'   => $value,
            'message' => Messages::MIN_LEN($value, $field)
        ];
    }

    public static function PASSWORD_MAX_LEN($field)
    {
        $value = 20;
        return [
            'value'   => $value,
            'message' => Messages::MAX_LEN($value, $field)
        ];
    }

    public static function PASSWORD_REGEXP($field)
    {
        $value = '/^[\@\!\^\+\%\/\(\)\=\?\_\*\-\<\>\#\$\½\{\[\]\}\\\|\w]*$/';
        return [
            'value'   => $value,
            'message' => Messages::PASSWORD_REGEXP($field)
        ];
    }

    public static function PASSWORD_REGEXP_UPPERCASE($field)
    {
        $value = '/[A-Z]/';
        $count = 1;
        return [
            'value'   => $value,
            'message' => Messages::REGEXP_UPPERCASE($count, $field)
        ];
    }

    public static function PASSWORD_REGEXP_DIGIT($field)
    {
        $value = '/[\d]/';
        $count = 1;
        return [
            'value'   => $value,
            'message' => Messages::REGEXP_DIGIT($count, $field)
        ];
    }

    public static function NAME_MIN_LEN($field)
    {
        $value = 1;
        return [
            'value'   => $value,
            'message' => Messages::MIN_LEN($value, $field)
        ];
    }

    public static function NAME_MAX_LEN($field)
    {
        $value = 64;
        return [
            'value'   => $value,
            'message' => Messages::MAX_LEN($value, $field)
        ];
    }

    public static function NAME_REGEXP($field)
    {
        $value = '/^[a-zA-Z\s\.\'\-ığüşöçİĞÜŞÖÇ]*$/';
        return [
            'value'   => $value,
            'message' => Messages::NAME_REGEXP($field)
        ];
    }

    public static function EMAIL_MAX_LEN($field)
    {
        $value = 64;
        return [
            'value'   => $value,
            'message' => Messages::MAX_LEN($value, $field)
        ];
    }

    public static function PHONE_REGEXP($field)
    {
        $value = '/^0\d{10}$/';
        return [
            'value'   => $value,
            'message' => Messages::PHONE_REGEXP($field)
        ];
    }

    public static function TCKN_REGEXP($field)
    {
        $value = '/^\d{11}$/';
        return [
            'value'   => $value,
            'message' => Messages::TCKN_REGEXP($field)
        ];
    }

    public static function ID_REGEXP($field)
    {
        $value = '/^\d+$/';
        return [
            'value'   => $value,
            'message' => Messages::ID_REGEXP($field)
        ];
    }

    public static function COMPOSITE_SEARCH_REGEXP($field)
    {
        $value = '/^[a-zA-Z\s\.\'\-ığüşöçİĞÜŞÖÇ\d]+$/';
        return [
            'value'   => $value,
            'message' => Messages::COMPOSITE_SEARCH_REGEXP($field)
        ];
    }

    public static function COMPOSITE_SEARCH_MIN_LEN($field)
    {
        $value = 3;
        return [
            'value'   => $value,
            'message' => Messages::MIN_LEN($value, $field)
        ];
    }

    public static function TCKN_USER_SIGNUP_REGEXP($field)
    {
        $value = '/(^$)|(^\d{11}$)/g';
        return [
            'value' => $value,
            'message' => Messages::INVALID_TCKN_USER_SIGNUP($field)
        ];
    }
}