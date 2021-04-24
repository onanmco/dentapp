<?php

namespace app\utility;

use app\constant\Messages;
use app\constant\Responses;
use app\model\ApiToken;
use app\model\Language;
use app\model\User;
use config\Config;
use core\Router;

class Auth
{
    public static function getAuthUser()
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        return  User::findById($_SESSION['user_id']);
    }

    public static function isLoggedIn()
    {
        return self::getAuthUser() !== false;
    }

    public static function login($user)
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->getId();

        self::deleteApiToken();

        self::setApiToken($user->getId());
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
            Popup::add(Responses::UNAUTHORIZED_ACCESS(Messages::UNAUTHORIZED_ACCESS()));
            self::setLastVisit($_SERVER['REQUEST_URI']);
            Router::redirectAfterPost('/user/login');
        }
    }

    public static function logout()
    {
        if (isset($_COOKIE['last_session_id'])) {
            $existing_api_token_record = ApiToken::findBySessionId($_COOKIE['last_session_id']);
            if ($existing_api_token_record !== false) {
                $existing_api_token_record->delete();
            }
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

    public static function setApiToken($user_id)
    {
        $new_session_id = session_id();

        setcookie('last_session_id', $new_session_id, time() + Datetime::WEEK, '/');

        $api_token = new Token();

        $api_token_record = new ApiToken([
            'last_session_id' => $new_session_id,
            'user_id' => $user_id,
            'api_token_hash' => $api_token->getHash()
        ]);

        $api_token_record->save();

        setcookie('api_token', $api_token->getValue(), time() + Datetime::WEEK, '/');
    }

    public static function deleteApiToken()
    {
        if (isset($_COOKIE['last_session_id'])) {
            $existing_api_token_record = ApiToken::findBySessionId($_COOKIE['last_session_id']);
            if ($existing_api_token_record !== false) {
                $existing_api_token_record->delete();
            }
        }
    }

    public static function getUserLanguage()
    {
        $user = self::getAuthUser();

        if ($user === false) {
            $language = Config::DEFAULT_LANGUAGE;

            if (isset($_COOKIE['language_preference'])) {
                $language = $_COOKIE['language_preference'];
            }

            return $language;
        }

        $language_row = Language::findById($user->getLanguagePreference());

        if ($language_row === false) {
            $language = Config::DEFAULT_LANGUAGE;

            if (isset($_COOKIE['language_preference'])) {
                $language = $_COOKIE['language_preference'];
            }

            return $language;
        }

        if (isset($_COOKIE['language_preference'])) {
            unset($_COOKIE['language_preference']);
            setcookie("langauge_preference", null, time() - 3600, "/");
        }

        setcookie("language_preference", $language_row->getLanguage(), time() + Datetime::DAY * 30, "/");

        return $language_row->getLanguage();
    }
}