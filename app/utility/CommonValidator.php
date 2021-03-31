<?php

namespace app\utility;

use app\constant\Constraints;
use app\constant\Messages;
use app\constant\Environment;

abstract class CommonValidator
{
    /**
     * Check if a string is a valid regexp or not
     * 
     * @param string $string
     * 
     * @return bool true|false - true if the $string is a valid regexp, false otherwise
     */
    public static function isValidRegexp($string)
    {
        $string = '/' . trim($string, '/') . '/';
        return (!(@preg_match($string, null) === false));
    }

    public static function isValidName($value, $field)
    {
        $errors = [];

        $value = trim($value, " \t");
        $value = preg_replace('/\s+/', ' ', $value);

        $min_length = Constraints::NAME_MIN_LEN($field);
        if (strlen($value) < $min_length['value']) {
            $errors[] = $min_length['message'];
        }

        $max_length = Constraints::NAME_MAX_LEN($field);
        if (strlen($value) > $max_length['value']) {
            $errors[] = $max_length['message'];
        }

        $name_regexp = Constraints::NAME_REGEXP($field);
        if (!preg_match($name_regexp['value'], $value)) {
            $errors[] = $name_regexp['message'];
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidPassword($value, $field)
    {
        $errors = [];

        $password_regexp = Constraints::PASSWORD_REGEXP($field);
        if (!preg_match($password_regexp['value'], $value)) {
            $errors[] = $password_regexp['message'];
        }

        $password_min_length = Constraints::PASSWORD_MIN_LENGTH($field);
        if (strlen($value) < $password_min_length['value']) {
            $errors[] = $password_min_length['message'];
        }

        $password_max_length = Constraints::PASSWORD_MAX_LEN($field);
        if (strlen($value) > $password_max_length['value']) {
            $errors[] = $password_max_length['message'];
        }

        $password_regexp_uppercase = Constraints::PASSWORD_REGEXP_UPPERCASE($field);
        if (!preg_match($password_regexp_uppercase['value'], $value)) {
            $errors[] = $password_regexp_uppercase['message'];
        }

        $password_regexp_digit = Constraints::PASSWORD_REGEXP_DIGIT($field);
        if (!preg_match($password_regexp_digit['value'], $value)) {
            $errors[] = $password_regexp_digit['message'];
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidUnixTimestamp($value, $field)
    {
        $errors = [];
        if (!(((string) (int) $value === $value) 
        && ((int) $value <= PHP_INT_MAX) 
        && ((int) $value >= ~PHP_INT_MAX))) {
            $errors[] = $field . ' alanı UNIX timestamp formatında olmalıdır.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidEmail($value, $field)
    {
        $errors = [];

        $email_max_length = Constraints::EMAIL_MAX_LEN($field);
        if (strlen($value) > $email_max_length['value']) {
            $errors[] =  $email_max_length['message'];
        }
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = Messages::INVALID_EMAIL($field);
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidId($value, $field)
    {
        $errors = [];

        $id_regexp = Constraints::ID_REGEXP($field);
        if (!preg_match($id_regexp['value'], $value)) {
            $errors[] = $id_regexp['message'];
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidPhone($value, $field)
    {
        $errors = [];

        $phone_regexp = Constraints::PHONE_REGEXP($field);
        if (!preg_match($phone_regexp['value'], $value)) {
            $errors[] = $phone_regexp['message'];
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidTckn($value, $field)
    {
        $errors = [];

        $tckn_regexp = Constraints::TCKN_REGEXP($field);
        if (!preg_match($tckn_regexp['value'], $value)) {
            $errors[] = $tckn_regexp['message'];
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isHTML($string)
    {
        return $string != strip_tags($string);
    }
}