<?php

namespace app\model;

use core\Model;
use PDO;

class Appointment extends Model
{
    private $id = '';
    private $user_id = '';
    private $patient_id = '';
    private $start = '';
    private $end = '';
    private $notes = '';
    private $notify = false;
    private $appointment_type_id = '';

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
        $sql = 'INSERT INTO appointments (user_id, patient_id, start, end, notes, notify, appointment_type_id) 
                VALUES (:user_id, :patient_id, :start, :end, :notes, :notify, :appointment_type_id)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':patient_id', $this->patient_id, PDO::PARAM_INT);
        $stmt->bindValue(':start', $this->start, PDO::PARAM_STR);
        $stmt->bindValue(':end', $this->end, PDO::PARAM_STR);
        $stmt->bindValue(':notes', $this->notes, PDO::PARAM_STR);
        $stmt->bindValue(':notify', $this->notify, PDO::PARAM_BOOL);
        $stmt->bindValue(':appointment_type_id', $this->appointment_type_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getById($id)
    {
        $sql = 'SELECT * FROM appointments WHERE id = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getByRange($start_datetime, $end_datetime)
    {
        $sql = 'SELECT * FROM appointments WHERE start = :start_datetime AND end = :end_datetime';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':start_datetime', $start_datetime, PDO::PARAM_STR);
        $stmt->bindValue(':end_datetime', $end_datetime, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAllBetween($start_datetime, $end_datetime)
    {
        $sql = 'SELECT * 
                FROM appointments 
                WHERE start >= :start_datetime AND start < :end_datetime 
                ORDER BY start ASC';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':start_datetime', $start_datetime, PDO::PARAM_STR);
        $stmt->bindValue(':end_datetime', $end_datetime, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getOverlappingCount($new_start_datetime, $new_end_datetime)
    {
        $sql = 'SELECT COUNT(*) 
                FROM appointments 
                WHERE start < :new_end_datetime AND end > :new_start_datetime';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':new_start_datetime', $new_start_datetime, PDO::PARAM_STR);
        $stmt->bindValue(':new_end_datetime', $new_end_datetime, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_NUM);
        $stmt->execute();
        return $stmt->fetch()[0];
    }

    public function serialize()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'patient_id' => $this->patient_id,
            'start' => $this->start,
            'end' => $this->end,
            'notes' => $this->notes,
            'notify' => $this->notify,
            'appointment_type_id' => $this->appointment_type_id
        ];
    }
}