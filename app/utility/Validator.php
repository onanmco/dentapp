<?php

namespace app\utility;

use app\constant\Messages;

class Validator
{
    private $errors = [];
    private $value = '';
    private $field_name = '';
    private $stop_once = false;
    private $errors_incremented = false;
    private $stop = false;

    public function __construct($value, $field_name)
    {
        $this->value = $value;
        $this->field_name = $field_name;
        return $this;
    }

    public function stop()
    {
        $this->stop = true;
        return $this;
    }

    public function stop_once()
    {
        $this->stop_once = true;
        return $this;
    }

    public function required()
    {
        if (!empty($this->errors) && $this->stop === true) {
            return $this;
        }

        if ($this->errors_incremented === true && $this->stop_once === true) {
            return $this;
        }

        if (empty($this->value)) {
            $this->errors[] = Messages::CANNOT_BE_EMPTY($this->field_name);
            $this->errors_incremented = $this->stop_once === true ? true : false;
        }
        return $this;
    }

    public function match($pattern, $error_message)
    {
        if (!empty($this->errors) && $this->stop === true) {
            return $this;
        }

        if ($this->errors_incremented === true && $this->stop_once === true) {
            return $this;
        }

        if (!preg_match($pattern, $this->value)) {
            $this->errors[] = $error_message;
            $this->errors_incremented = $this->stop_once === true ? true : false;
        }
        return $this;
    }

    public function min_len($length)
    {
        if (!empty($this->errors) && $this->stop === true) {
            return $this;
        }

        if ($this->errors_incremented === true && $this->stop_once === true) {
            return $this;
        }

        if (strlen($this->value) < $length) {
            $this->errors[] = Messages::MIN_LEN($length, $this->field_name);
            $this->errors_incremented = $this->stop_once === true ? true : false;
        }
        return $this;
    }

    public function max_len($length)
    {
        if (!empty($this->errors) && $this->stop === true) {
            return $this;
        }

        if ($this->errors_incremented === true && $this->stop_once === true) {
            return $this;
        }

        if (strlen($this->value) > $length) {
            $this->errors[] = Messages::MAX_LEN($length, $this->field_name);
            $this->errors_incremented = $this->stop_once === true ? true : false;
        }
        return $this;
    }
    
    public function int()
    {
        if (!empty($this->errors) && $this->stop === true) {
            return $this;
        }

        if ($this->errors_incremented === true && $this->stop_once === true) {
            return $this;
        }
        
        if (!preg_match('/^\d+$/', $this->value)) {
            $this->errors[] = Messages::SHOULD_BE_INTEGER($this->field_name);
            $this->errors_incremented = $this->stop_once === true ? true : false;
        }
        return $this;
    }
    
    public function numeric()
    {
        if (!empty($this->errors) && $this->stop === true) {
            return $this;
        }

        if ($this->errors_incremented === true && $this->stop_once === true) {
            return $this;
        }
        
        if (!preg_match('/(^\d+$)|(^\d+\.\d+$)/', $this->value)) {
            $this->errors[] = Messages::SHOULD_BE_NUMERIC($this->field_name);
            $this->errors_incremented = $this->stop_once === true ? true : false;
        }
        return $this;
    }

    public function between($min, $max)
    {
        if (!empty($this->errors) && $this->stop === true) {
            return $this;
        }

        if ($this->errors_incremented === true && $this->stop_once === true) {
            return $this;
        }

        if (!((float)$this->value >= $min && (float)$this->value <= $max)) {
            $this->errors[] = Messages::SHOULD_BE_IN_BETWEEN($min, $max, $this->field_name);
            $this->errors_incremented = $this->stop_once === true ? true : false;
        }
        return $this;
    }

    public function email()
    {
        if (!empty($this->errors) && $this->stop === true) {
            return $this;
        }

        if ($this->errors_incremented === true && $this->stop_once === true) {
            return $this;
        } 
        
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = Messages::INVALID_EMAIL($this->field_name);
            $this->errors_incremented = $this->stop_once === true ? true : false;
        }
    }
    
    public function ip_addr()
    {
        if (!empty($this->errors) && $this->stop === true) {
            return $this;
        }
        
        if ($this->errors_incremented === true && $this->stop_once === true) {
            return $this;
        }
        
        if (!preg_match('/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $this->value)) {
            $this->errors[] = Messages::INVALID_IP_ADDR($this->field_name);
            $this->errors_incremented = $this->stop_once === true ? true : false;            
        }
    }


    public function isValid()
    {
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

}