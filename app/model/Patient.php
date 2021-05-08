<?php

namespace app\model;

use core\Model;
use PDO;

class Patient extends Model

{
    private $id = '';
    private $first_name = '';
    private $last_name = '';
    private $phone = '';
    private $tckn = '';

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

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getTckn()
    {
        return $this->tckn;
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM patients WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByTckn($tckn)
    {
        $sql = 'SELECT * FROM patients WHERE tckn = :tckn';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':tckn', $tckn, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function isPatientAvailable($tckn)
    {
        return self::findByTckn($tckn) === false;
    }

    public function save()
    {
        $sql = 'INSERT INTO patients(first_name, last_name, phone, tckn) VALUES(:first_name, :last_name, :phone, :tckn)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $this->phone, PDO::PARAM_STR);
        $stmt->bindValue(':tckn', $this->tckn, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function delete()
    {
        $sql = 'DELETE FROM patients WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function serialize()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'tckn' => $this->tckn
        ];
    }

    public static function findByNameOrTckn($string)
    {
        $sql = "SELECT * 
                FROM patients 
                WHERE first_name LIKE :placeholder OR last_name LIKE :placeholder OR tckn LIKE :placeholder";
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':placeholder', '%' . $string . '%', PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function isEqual($other_patient) 
    {
        return $this->id === $other_patient->getId();
    }


}