<?php

namespace app\model;

use core\Model;
use PDO;

class ApiToken extends Model
{
    private $last_session_id = '';
    private $personel_id = '';
    private $api_token_hash = '';

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

    public function getSessionId()
    {
        return $this->last_session_id;
    }

    public function getApiTokenHash()
    {
        return $this->api_token_hash;
    }

    public static function findBySessionId($last_session_id)
    {
        $sql = 'SELECT * FROM api_tokenler WHERE last_session_id = :last_session_id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':last_session_id', $last_session_id, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public function save()
    {
        $sql = 'INSERT INTO api_tokenler(last_session_id, personel_id, api_token_hash) VALUES(:last_session_id, :personel_id, :api_token_hash)';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':last_session_id', $this->last_session_id, PDO::PARAM_STR);
        $stmt->bindValue(':personel_id', $this->personel_id, PDO::PARAM_INT);
        $stmt->bindValue(':api_token_hash', $this->api_token_hash, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function delete()
    {
        $sql = 'DELETE FROM api_tokenler WHERE :last_session_id = last_session_id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':last_session_id', $this->last_session_id, PDO::PARAM_STR);
        return $stmt->execute();
    }
}