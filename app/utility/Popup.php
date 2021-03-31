<?php

namespace app\utility;

use app\constant\Fields;

class Popup
{
    private $title = '';
    private $message = '';
    private $code = 404;

    public function __construct($args = [])
    {
        $class_vars = get_class_vars(get_called_class());
        foreach ($args as $key => $value) {
            if (isset($class_vars[$key])) {
                $this->$key = $value;
            }
        }
    }

    public function print()
    {
        echo "show_popup('" . htmlspecialchars($this->title) . "', '" . htmlspecialchars($this->message) . "', '" . htmlspecialchars($this->code) . "');";
    }

    public static function getAll() {
        if (isset($_SESSION[Fields::POPUPS])) {
            $popups = $_SESSION[Fields::POPUPS];
            unset ($_SESSION[Fields::POPUPS]);
            return $popups;
        }
        return [];
    }

    public static function add($args = []) {
        if (!isset($_SESSION[Fields::POPUPS])) {
            $_SESSION[Fields::POPUPS] = [];
        }
        $_SESSION[Fields::POPUPS][] = new Popup($args);
    } 

    public static function printAll()
    {
        $popups = self::getAll();
        foreach ($popups as $popup) {
            $popup->print();
        }
    }
}