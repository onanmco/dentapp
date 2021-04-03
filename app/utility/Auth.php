<?php

namespace app\utility;

use app\constant\Fields;
use app\constant\Messages;
use app\constant\Responses;
use app\model\ApiToken;
use app\model\Personel;
use core\Router;

class Auth
{
    public static function getAuthStaff()
    {
        if (!isset($_SESSION[Fields::STAFF_ID])) {
            return false;
        }
        return Personel::findById($_SESSION[Fields::STAFF_ID]);
    }

    public static function isLoggedIn()
    {
        return self::getAuthStaff() !== false;
    }

    public static function login($staff)
    {
        session_regenerate_id(true);
        $_SESSION[Fields::STAFF_ID] = $staff->getId();
        
        $old_session_id = isset($_COOKIE['last_session_id']) ? $_COOKIE['last_session_id'] : false;
        
        if ($old_session_id !== false) {
            $will_be_deleted = ApiToken::findBySessionId($old_session_id);

            if ($will_be_deleted !== false) {
                $will_be_deleted->delete();
            }
        }

        $new_session_id = session_id();

        setcookie('last_session_id', $new_session_id, time() + Datetime::WEEK, '/');
        
        $api_token = new Token();

        $api_token_record = new ApiToken([
            'last_session_id' => $new_session_id,
            'personel_id' => $staff->getId(),
            'api_token_hash' => $api_token->getHash()
        ]);

        $api_token_record->save();

        setcookie('api_token', $api_token->getValue(), time() + Datetime::WEEK, '/');
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
        if (isset($_COOKIE['last_session_id'])) {
            $existing_api_token_record = ApiToken::findBySessionId($_COOKIE['last_session_id']);
            $existing_api_token_record->delete();
        }
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