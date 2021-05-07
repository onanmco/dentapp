<?php

namespace app\model;

use core\Model;
use PDO;

class CsrfToken extends Model
{
    private $id = '';
    private $last_session_id = '';
    private $user_id = '';
    private $csrf_token_hash = '';

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

    public function getLastSessionId()
    {
        return $this->last_session_id;
    }

    public function getCsrfTokenHash()
    {
        return $this->csrf_token_hash;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public static function getByLastSessionId($last_session_id)
    {
        $sql = 'SELECT * FROM `csrf_tokens` WHERE `last_session_id` = :last_session_id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':last_session_id', $last_session_id, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public function save()
    {
        $sql = 'INSERT INTO `csrf_tokens` (`last_session_id`, `user_id`, `csrf_token_hash`)
                VALUES (:last_session_id, :user_id, :csrf_token_hash)';
        
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':last_session_id', $this->last_session_id, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':csrf_token_hash', $this->csrf_token_hash, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function delete()
    {
        $sql = 'DELETE FROM `csrf_tokens` WHERE `id` = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update()
    {
        $sql = 'UPDATE `csrf_tokens`
                SET `last_session_id` = :last_session_id,
                    `user_id` = :user_id,
                    `csrf_token_hash` = :csrf_token_hash
                WHERE `id` = :id';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':last_session_id', $this->last_session_id, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':csrf_token_hash', $this->csrf_token_hash, PDO::PARAM_STR);
        return $stmt->execute();
    }
}