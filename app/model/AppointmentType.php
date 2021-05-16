<?php

namespace app\model;

use core\Model;
use PDO;

class AppointmentType extends Model
{
    private $id = '';
    private $appointment_type = '';
    
    public function __construct($args = [])
    {
        $class_vars = get_class_vars(get_called_class());
        foreach ($args as $key => $value) {
            if (isset($class_vars[$key])) {
                $this->$key = $value;
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAppointmentType()
    {
        return $this->appointment_type;
    }

    public function save()
    {
        $sql = 'INSERT INTO `appointment_types`(`appointment_type`) VALUES(:appointment_type)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':appointment_type', $this->appointment_type, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function getById($id)
    {
        $sql = 'SELECT * FROM `appointment_types` WHERE `id` = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAll()
    {
        $sql = 'SELECT * FROM `appointment_types` ORDER BY `id` ASC';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }
}