<?php

namespace app\utility;

use Exception;

class Validator
{
    public $errors = [];

    public $rules = [
        'REQUIRED' => ['', 'alanı boş bırakılamaz.'],
        'MIN' => ['/^.{<min>,}$/', 'alanı en az <min> karakter içermelidir.'],
        'MAX' => ['/^.{0,<max>}$/', 'alanı en fazla <max> karakter içerebilir.'],
        'CAN' => ['/^[<regexp>]*$/', 'alanı yalnızca <verbal> içerebilir.'],
        'MUST' => ['/[<regexp>]+/', 'alanı en az bir adet <verbal> içermelidir.'],
        'EMAIL' => ['', 'Lütfen geçerli bir e-mail adresi girin.'],
        'TCKN' => ['/(^$)|(^\d{11}$)/', 'Lütfen geçerli bir TCKN girin.'],
        'NUMBER' => ['/(^\d+$)|(^\d+\.\d+$)|(^$)/', 'alanı yalnızca sayısal değerler içerebilir (virgül yerine nokta kullanın).'],
        'MESLEK' => ['/(^sekreter$)|(^hekim$)|(^patron$)/', 'alanı yalnızca sekreter, hekim ya da patron değerlerinden biri olabilir.']
    ];

    public function validate($name, $string, $rule, $params = [])
    {
        $name .= ' ';
        if (!isset($this->rules[$rule])) {
            throw new Exception('Undefined validation rule', 500);
        }
        if ($rule === 'EMAIL') {
            if (!filter_var($string, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = $this->rules['EMAIL'][1];
            }
        }
        if ($rule === 'REQUIRED') {
            if (strlen($string) < 1) {
                $this->errors[] = $name . $this->rules['REQUIRED'][1];
            }
        }
        if ($rule === 'MIN') {
            $regex = str_replace('<min>', $params['min'], $this->rules['MIN'][0]);
            $message = str_replace('<min>', $params['min'], $this->rules['MIN'][1]);
            if (!preg_match($regex, $string)) {
                $this->errors[] = $name . $message;
            }
        }
        if ($rule === 'MAX') {
            $regex = str_replace('<max>', $params['max'], $this->rules['MAX'][0]);
            $message = str_replace('<max>', $params['max'], $this->rules['MAX'][1]);
            if (!preg_match($regex, $string)) {
                $this->errors[] = $name . $message;
            }
        }
        if ($rule === 'CAN' || $rule === 'MUST') {
            $regex = str_replace('<regexp>', $params['regexp'], $this->rules[$rule][0]);
            $message = str_replace('<verbal>', $params['verbal'], $this->rules[$rule][1]);
            if (!preg_match($regex, $string)) {
                $this->errors[] = $name . $message;
            }
        }
        if ($rule === 'TCKN') {
            $regex = $this->rules['TCKN'][0];
            $message = $this->rules['TCKN'][1];
            if (!preg_match($regex, $string)) {
                $this->errors[] = $message;
            }
        }
        if ($rule === 'NUMBER') {
            $regex = $this->rules['NUMBER'][0];
            $message = $this->rules['NUMBER'][1];
            if (!preg_match($regex, $string)) {
                $this->errors[] = $name . $message;
            }
        }
        if ($rule === 'MESLEK') {
            $regex = $this->rules['MESLEK'][0];
            $message = $this->rules['MESLEK'][1];
            if (!preg_match($regex, $string)) {
                $this->errors[] = $name . $message;
            }
        }
    }

    public function validateAll($data = [])
    {
        foreach ($data as $value) {
            $params = (isset($value[3])) ? $value[3] : [];
            $this->validate($value[0], $value[1], $value[2], $params);
        }
        return $this->errors;
    }
}