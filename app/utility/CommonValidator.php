<?php

namespace app\utility;

use app\constant\SiteConfig;

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

    public static function isValidName($value, $field = 'İsim')
    {
        $errors = [];
        $value = trim($value, " \t");
        $value = preg_replace('/\s+/', ' ', $value);
        if (strlen($value) < SiteConfig::NAME_MIN_LENGTH) {
            $errors[] = $field . ' alanı boş bırakılamaz.';
        }
        if (strlen($value) > SiteConfig::NAME_MAX_LENGTH) {
            $errors[] = $field . ' alanı en fazla ' . SiteConfig::NAME_MAX_LENGTH . ' karakter içermelidir.';
        }
        if (!preg_match(SiteConfig::NAME_CHARSET, $value)) {
            $errors[] = $field . ' alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire içerebilir.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidPassword($value, $field = 'Şifre')
    {
        $errors = [];
        if (!preg_match(SiteConfig::PASSWORD_CHARSET, $value)) {
            $errors[] = $field . ' alanı yalnızca harf, sayı, özel karakterler, tire ve nokta içerebilir.';
        }
        if (strlen($value) < SiteConfig::PASSWORD_MIN_LENGTH) {
            $errors[] = $field . ' alanı en az ' . SiteConfig::PASSWORD_MIN_LENGTH . ' karakter içermelidir.';
        }
        if (strlen($value) > SiteConfig::PASSWORD_MAX_LENGTH) {
            $errors[] = $field . ' alanı en fazla ' . SiteConfig::PASSWORD_MAX_LENGTH . ' karakter içermelidir.';
        }
        if (!preg_match('/[A-Z]/', $value)) {
            $errors[] = $field . ' alanı en az bir adet büyük harf içermelidir.';
        }
        if (!preg_match('/[\d]/', $value)) {
            $errors[] = $field . ' alanı en az bir adet rakam içermelidir.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidUnixTimestamp($value, $field = 'Zaman damgası')
    {
        $errors = [];
        if (!(((string) (int) $value === $value) 
        && ((int) $value <= PHP_INT_MAX) 
        && ((int) $value >= ~PHP_INT_MAX))) {
            $errors[] = $field . ' alanı UNIX timestamp formatında olmalıdır.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidEmail($value, $field = 'e-mail')
    {
        $errors = [];
        if (strlen($value) > SiteConfig::EMAIL_MAX_LENGTH) {
            $errors[] = 'E-mail alanı en fazla ' . SiteConfig::EMAIL_MAX_LENGTH . ' karakter içermelidir.';
        }
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Lütfen geçerli bir' . $field . 'adresi girin.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidId($value, $field = 'Id')
    {
        $errors = [];
        if (!preg_match(SiteConfig::ID_REGEX, $value)) {
            $errors[] = $field . ' alanı 0\'dan büyük bir sayı olmalıdır.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidPhone($value, $field = 'telefon')
    {
        $errors = [];
        if (!preg_match(SiteConfig::PHONE_REGEX, $value)) {
            $errors[] = 'Lütfen geçerli bir ' . $field . ' numarası girin.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidTckn($value, $field = 'TCKN')
    {
        $errors = [];
        if (!preg_match(SiteConfig::TCKN_REGEX, $value)) {
            $errors[] = 'Lütfen geçerli bir ' . $field . ' girin';
        }
        return (empty($errors)) ? true : $errors;
    }
}