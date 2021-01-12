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
    private $maas = '';
    private $meslek = '';
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

    public function getMaas()
    {
        return $this->maas;
    }

    public function getMeslek()
    {
        return $this->meslek;
    }

    public function getApiToken()
    {
        return $this->api_token;
    }

    public static function getAll(){
        $sql = 'SELECT * FROM personel ORDER BY id DESC';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM personel WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM personel WHERE email = :email';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByName($isim)
    {
        $sql = 'SELECT * FROM personel WHERE isim = :isim';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':isim', $isim, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public function save()
    {
        $sql = 'INSERT INTO personel(isim, soyisim, tckn, email, password_hash, maas, meslek, api_token) VALUES(:isim, :soyisim, :tckn, :email, :password_hash, :maas, :meslek, :api_token)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':isim', $this->isim, PDO::PARAM_STR);
        $stmt->bindValue(':soyisim', $this->soyisim, PDO::PARAM_STR);
        $stmt->bindValue(':tckn', $this->tckn, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $this->password_hash, PDO::PARAM_STR);
        $stmt->bindValue(':maas', $this->maas, PDO::PARAM_STR);
        $stmt->bindValue(':meslek', $this->meslek, PDO::PARAM_STR);
        $stmt->bindValue(':api_token', $this->api_token, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function getLastInserted()
    {
        $sql = 'SELECT * FROM personel WHERE id = (SELECT MAX(id) FROM personel)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByApiToken($api_token)
    {
        $sql = 'SELECT * FROM personel WHERE api_token = :api_token';
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
        $sql = 'DELETE FROM personel WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}