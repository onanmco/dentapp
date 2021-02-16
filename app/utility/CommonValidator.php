<?php

namespace app\utility;

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
        if (strlen($value) < 1) {
            $errors[] = $field . ' alanı boş bırakılamaz.';
        }
        if (!preg_match('/^[a-zA-Z\s\.\'\-ığüşöçİĞÜŞÖÇ]*$/', $value)) {
            $errors[] = $field . ' alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire içerebilir.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidPassword($value, $min = 8, $max = 20, $field = 'Şifre')
    {
        $errors = [];
        if (!preg_match('/^[\@\!\^\+\%\/\(\)\=\?\_\*\-\<\>\#\$\½\{\[\]\}\\\|\w]*$/', $value)) {
            $errors[] = $field . ' alanı yalnızca harf, sayı, özel karakterler, tire ve nokta içerebilir.';
        }
        if (strlen($value) < 8) {
            $errors[] = $field . ' alanı en az ' . $min . ' karakter içermelidir.';
        }
        if (strlen($value) > 20) {
            $errors[] = $field . ' alanı en fazla ' . $max . ' karakter içermelidir.';
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
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Lütfen geçerli bir' . $field . 'adresi girin.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidId($value, $field = 'Id')
    {
        $errors = [];
        if (!preg_match('/^\d+$/', $value)) {
            $errors[] = $field . ' alanı 0\'dan büyük bir sayı olmalıdır.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidPhone($value, $field = 'Telefon')
    {
        $errors = [];
        if (!preg_match('/^0\d{10}$/', $value)) {
            $errors[] = 'Lütfen geçerli bir ' . $field . ' numarası girin.';
        }
        return (empty($errors)) ? true : $errors;
    }

    public static function isValidTckn($value, $field = 'TCKN')
    {
        $errors = [];
        if (!preg_match('/^\d{11}$/', $value)) {
            $errors[] = 'Lütfen geçerli bir ' . $field . ' girin';
        }
        return (empty($errors)) ? true : $errors;
    }
}