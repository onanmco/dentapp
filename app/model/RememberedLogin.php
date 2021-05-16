<?php 

namespace app\model;

use core\Model;
use PDO;

class RememberedLogin extends Model
{
    public $token_hash = '';
    public $user_id = '';
    public $expire_date = '';

    public function __construct($args = [])
    {
        foreach ($args as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function findByToken($token)
    {
        $sql = 'SELECT * FROM `remembered_logins` WHERE `token_hash` = :token_hash';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $token->getHash(), PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public function hasExpired()
    {
        return strtotime($this->expire_date) < time();
    }

    public function getUser()
    {
        return User::findById($this->user_id);
    }

    public function delete()
    {
        $sql = 'DELETE FROM `remembered_logins` WHERE `token_hash` = :token_hash';
        $db = self::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $this->token_hash, PDO::PARAM_STR);
        $stmt->execute();
    }
}