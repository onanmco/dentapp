<?php

namespace app\model;

use core\Model;
use PDO;

class Hasta extends Model

{
    private $id = '';
    private $isim = '';
    private $soyisim = '';
    private $telefon = '';
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

    public function getIsim()
    {
        return $this->isim;
    }

    public function getSoyisim()
    {
        return $this->soyisim;
    }

    public function getTelefon()
    {
        return $this->telefon;
    }

    public function getTckn()
    {
        return $this->tckn;
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM hastalar WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findByTckn($tckn)
    {
        $sql = 'SELECT * FROM hastalar WHERE tckn = :tckn';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':tckn', $tckn, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function isHastaAvailable($tckn)
    {
        return self::findByTckn($tckn) === false;
    }

    public function save()
    {
        $sql = 'INSERT INTO hastalar(isim, soyisim, telefon, tckn) VALUES(:isim, :soyisim, :telefon, :tckn)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':isim', $this->isim, PDO::PARAM_STR);
        $stmt->bindValue(':soyisim', $this->soyisim, PDO::PARAM_STR);
        $stmt->bindValue(':telefon', $this->telefon, PDO::PARAM_STR);
        $stmt->bindValue(':tckn', $this->tckn, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function delete()
    {
        $sql = 'DELETE FROM hastalar WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function serialize()
    {
        return [
            'id' => $this->id,
            'isim' => $this->isim,
            'soyisim' => $this->soyisim,
            'telefon' => $this->telefon,
            'tckn' => $this->tckn
        ];
    }

    public static function findByIsimOrSoyisimOrTckn($string)
    {
        $sql = "SELECT * 
                FROM hastalar 
                WHERE isim LIKE :placeholder OR soyisim LIKE :placeholder OR tckn LIKE :placeholder";
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':placeholder', '%' . $string . '%', PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function isEqual($other_hasta) 
    {
        return $this->id === $other_hasta->getId();
    }


}