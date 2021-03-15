<?php

namespace app\model;

use core\Model;
use PDO;

class RandevuTuru extends Model
{
    private $id = '';
    private $randevu_turu = '';
    
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

    public function getRandevuTuru()
    {
        return $this->randevu_turu;
    }

    public function save()
    {
        $sql = 'INSERT INTO randevu_turleri(randevu_turu) VALUES(:randevu_turu)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':randevu_turu', $this->randevu_turu, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function getById($id)
    {
        $sql = 'SELECT * FROM randevu_turleri WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAll()
    {
        $sql = 'SELECT * FROM randevu_turleri ORDER BY id ASC';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }
}