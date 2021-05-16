<?php

namespace app\model;

use core\Model;
use PDO;

class Group extends Model
{
    private $id = '';
    private $group = '';

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

    public function getGroup()
    {
        return $this->group;
    }
    
    public function save()
    {
        $sql = 'INSERT INTO `groups`(`group`) VALUES(:group)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':group', $this->group, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function getAll()
    {
        $sql = 'SELECT * FROM `groups`';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM `groups` WHERE `id` = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function isExist($id)
    {
        return self::findById($id) !== false;
    }
}