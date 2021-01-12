<?php

namespace core;

use config\Config;
use Exception;
use PDO;
use PDOException;

abstract class Model
{
    /**
     * Returns the database configuration information.
     * 
     * @return array
     * 
     * @throws Exception
     */
    private static function getConnectionInfo()
    {
        return [
            'dsn' => 'mysql:host=' . Config::DATABASE['HOST'] . ';port=' . Config::DATABASE['PORT'] . ';dbname=' . Config::DATABASE['NAME'],
            'user' => Config::DATABASE['USER'],
            'pass' => Config::DATABASE['PASS']
        ];
    }

    /**
     * Caches and returns the database connection
     * 
     * @return object
     * 
     * @throws PDOException
     */
    protected static function getDB()
    {
        static $db = null;
        if ($db === null) {
            $conn_info = self::getConnectionInfo();
            try {
                $db = new PDO($conn_info['dsn'], $conn_info['user'], $conn_info['pass']);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $db;
            } catch (PDOException $e) {
                throw new Exception($e);
            }
        }
        return $db;
    }
}