<?php

namespace app\model;

use core\Model;
use PDO;

class HatirlananGiris extends Model
{
    private $personel_id = '';
    private $token_hash = '';
    private $bitis_tarihi = '';

    public function __construct($args = [])
    {
        $class_vars = get_class_vars(get_called_class());
        foreach ($args as $key => $value) {
            if (isset($class_vars[$key])) {
                $this->$key = $value;
            }
        }
    }

    public function getPersonelId()
    {
        return $this->personel_id;
    }

    public function getTokenHash()
    {
        return $this->token_hash;
    }

    public function getBitisTarihi()
    {
        return $this->bitis_tarihi;
    }

    public static function getByPersonelId($personel_id)
    {
        $sql = 'SELECT * FROM hatirlanan_personel_girisleri WHERE personel_id = :personel_id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':personel_id', $personel_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public function hasExpired()
    {
        return strtotime($this->bitis_tarihi) < time();
    }

    public function save()
    {
        $sql = 'INSERT INTO hatirlanan_personel_girisleri(personel_id, token_hash, bitis_tarihi) VALUES(:personel_id, :token_hash, :bitis_tarihi)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':personel_id', $this->personel_id, PDO::PARAM_INT);
        $stmt->bindValue(':token_hash', $this->token_hash, PDO::PARAM_STR);
        $stmt->bindValue(':bitis_tarihi', $this->bitis_tarihi, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function delete()
    {
        $sql = 'DELETE FROM hatirlanan_personel_girisleri WHERE token_hash = :token_hash';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $this->token_hash, PDO::PARAM_STR);
        return $stmt->execute();
    }
}