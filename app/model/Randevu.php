<?php

namespace app\model;

use core\Model;
use PDO;

class Randevu extends Model
{
    private $id = '';
    private $personel_id = '';
    private $hasta_id = '';
    private $baslangic = '';
    private $bitis = '';
    private $notlar = '';
    private $hatirlat = false;

    public function __construct($args = [])
    {
        $class_vars = get_class_vars(get_called_class());
        foreach ($args as $key => $value) {
            if (isset($class_vars[$key])) {
                $this->$key = $value;
            }
        }
    }

    public function save()
    {
        $sql = 'INSERT INTO randevu (personel_id, hasta_id, baslangic, bitis, notlar, hatirlat) 
                VALUES (:personel_id, :hasta_id, :baslangic, :bitis, :notlar, :hatirlat)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':personel_id', $this->personel_id, PDO::PARAM_INT);
        $stmt->bindValue(':hasta_id', $this->hasta_id, PDO::PARAM_INT);
        $stmt->bindValue(':baslangic', $this->baslangic, PDO::PARAM_STR);
        $stmt->bindValue(':bitis', $this->bitis, PDO::PARAM_STR);
        $stmt->bindValue(':notlar', $this->notlar, PDO::PARAM_STR);
        $stmt->bindValue(':hatirlat', $this->hatirlat, PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    public static function getById($id)
    {
        $sql = 'SELECT * FROM randevu WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getBetween($start_timestamp, $end_timestamp)
    {
        $start_datetime = date('Y-m-d H:i:s', $start_timestamp);
        $end_datetime = date('Y-m-d H:i:s', $end_timestamp);
        $sql = 'SELECT * FROM randevu WHERE baslangic > :start_datetime AND baslangic < :end_datetime ORDER BY baslangic ASC';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':start_datetime', $start_datetime, PDO::PARAM_STR);
        $stmt->bindValue(':start_datetime', $end_datetime, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getOverlappingCount($start_timestamp, $end_timestamp)
    {
        $start_datetime = date('Y-m-d H:i:s', $start_timestamp);
        $end_datetime = date('Y-m-d H:i:s', $end_timestamp);
        $sql = 'SELECT COUNT(*) 
                FROM randevu 
                WHERE :start_datetime < bitis AND :end_datetime > baslangic';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':start_datetime', $start_datetime, PDO::PARAM_STR);
        $stmt->bindValue(':end_datetime', $end_datetime, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_NUM);
        $stmt->execute();
        return $stmt->fetch()[0];
    }
}