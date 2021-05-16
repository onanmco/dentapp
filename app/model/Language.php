<?php

namespace app\model;

use core\Model;
use PDO;

class Language extends Model
{
    private $id = '';
    private $language = '';

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

    public function getLanguage()
    {
        return $this->language;
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM `languages` WHERE `id` = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByLanguage($language)
    {
        $sql = 'SELECT * FROM `languages` WHERE `language` = :language';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':language', $language, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAll()
    {
        $sql = 'SELECT `language` FROM `languages`';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return [];
        }
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = $row['language'];
        }
        return $results;
    }
}