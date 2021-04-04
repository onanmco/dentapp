<?php

namespace app\model;

use core\Model;
use PDO;

class User extends Model
{
    private $id = '';
    private $first_name = '';
    private $last_name = '';
    private $tckn = '';
    private $email = '';
    private $password_hash = '';
    private $group_id = '';
    private $language_preference = '';

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

    public function getTckn()
    {
        return $this->tckn;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    public function getGroupId()
    {
        return $this->group_id;
    }

    public function getLanguagePreference()
    {
        return $this->language_preference;
    }

    public static function getAll(){
        $sql = 'SELECT * FROM users ORDER BY id DESC';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByFirstName($first_name)
    {
        $sql = 'SELECT * FROM users WHERE first_name = :first_name';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public function save()
    {
        $sql = 'INSERT INTO users(first_name, last_name, tckn, email, password_hash, group_id, language_preference) VALUES(:first_name, :last_name, :tckn, :email, :password_hash, :group_id, :language_preference)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
        $stmt->bindValue(':tckn', $this->tckn, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $this->password_hash, PDO::PARAM_STR);
        $stmt->bindValue(':group_id', $this->group_id, PDO::PARAM_INT);
        $stmt->bindValue(':language_preference', $this->language_preference, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getLastInserted()
    {
        $sql = 'SELECT * FROM users WHERE id = (SELECT MAX(id) FROM users)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function isEmailAvailable($email)
    {
        return self::findByEmail($email) === false;
    }

    public function delete()
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function findAllByGroupId($group_id)
    {
        $sql = 'SELECT * FROM users WHERE group_id = :group_id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }
}