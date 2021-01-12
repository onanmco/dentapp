<?php

namespace app\utility;

use app\constant\Messages;
use app\model\Personel;
use core\Router;
use Exception;

class Auth
{
    public static function getAuthPersonel()
    {
        if (!isset($_SESSION['personel_id'])) {
            return false;
        }
        $auth_personel = Personel::findById($_SESSION['personel_id']);
        if ($auth_personel === false) {
            $message = Messages::BILINMEYEN_HATA;
            throw new Exception($message['message'], $message['code']);
        }
        return $auth_personel;
    }

    public static function isLoggedIn()
    {
        return self::getAuthPersonel() !== false;
    }

    public static function login($personel)
    {
        session_regenerate_id(true);
        $_SESSION['personel_id'] = $personel->getId();
    }

    public static function setLastVisit()
    {
        $_SESSION['last_visit'] = $_SERVER['REQUEST_URI'];
    }

    public static function getLastVisit()
    {
        return (isset($_SESSION['last_visit'])) ? $_SESSION['last_visit'] : '/';
    }

    public static function loginRequired()
    {
        if (!self::isLoggedIn()) {
            Popup::add(Messages::ERISIM_KISITLANDI);
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