<?php

namespace app\model;

use core\Model;
use PDO;

class Personel extends Model
{
    private $id = '';
    private $isim = '';
    private $soyisim = '';
    private $tckn = '';
    private $email = '';
    private $password_hash = '';
    private $meslek_id = '';
    private $api_token = '';

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

    public function getIsim()
    {
        return $this->isim;
    }

    public function getSoyisim()
    {
        return $this->soyisim;
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

    public function getMeslekId()
    {
        return $this->meslek_id;
    }

    public function getApiToken()
    {
        return $this->api_token;
    }

    public static function getAll(){
        $sql = 'SELECT * FROM personeller ORDER BY id DESC';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM personeller WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM personeller WHERE email = :email';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByName($isim)
    {
        $sql = 'SELECT * FROM personeller WHERE isim = :isim';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':isim', $isim, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public function save()
    {
        $sql = 'INSERT INTO personeller(isim, soyisim, tckn, email, password_hash, meslek_id, api_token) VALUES(:isim, :soyisim, :tckn, :email, :password_hash, :meslek_id, :api_token)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':isim', $this->isim, PDO::PARAM_STR);
        $stmt->bindValue(':soyisim', $this->soyisim, PDO::PARAM_STR);
        $stmt->bindValue(':tckn', $this->tckn, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $this->password_hash, PDO::PARAM_STR);
        $stmt->bindValue(':meslek_id', $this->meslek_id, PDO::PARAM_INT);
        $stmt->bindValue(':api_token', $this->api_token, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function getLastInserted()
    {
        $sql = 'SELECT * FROM personeller WHERE id = (SELECT MAX(id) FROM personeller)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByApiToken($api_token)
    {
        $sql = 'SELECT * FROM personeller WHERE api_token = :api_token';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':api_token', $api_token, PDO::PARAM_STR);
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
        $sql = 'DELETE FROM personeller WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}