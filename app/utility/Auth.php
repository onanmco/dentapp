<?php

namespace app\utility;

use app\constant\Fields;
use app\constant\Messages;
use app\constant\Responses;
use app\model\Personel;
use core\Router;
use Exception;

class Auth
{
    public static function getAuthStaff()
    {
        if (!isset($_SESSION[Fields::STAFF_ID])) {
            return false;
        }
        $auth_staff = Personel::findById($_SESSION[Fields::STAFF_ID]);
        if ($auth_staff === false) {
            $error = Responses::UNKNOWN_ERROR();
            throw new Exception(Messages::DB_READ_ERROR(), $error['code']);
        }
        return $auth_staff;
    }

    public static function isLoggedIn()
    {
        return self::getAuthStaff() !== false;
    }

    public static function login($staff)
    {
        session_regenerate_id(true);
        $_SESSION[Fields::STAFF_ID] = $staff->getId();
    }

    public static function setLastVisit()
    {
        $_SESSION[Fields::LAST_VISIT] = $_SERVER['REQUEST_URI'];
    }

    public static function getLastVisit()
    {
        return (isset($_SESSION[Fields::LAST_VISIT])) ? $_SESSION[Fields::LAST_VISIT] : '/';
    }

    public static function loginRequired()
    {
        if (!self::isLoggedIn()) {
            Popup::add(Responses::UNAUTHORIZED_ACCESS(Messages::UNAUTHORIZED_ACCESS()));
            self::setLastVisit($_SERVER['REQUEST_URI']);
            Router::redirectAfterPost('/personel/giris');
        }
    }

    public static function logout()
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}