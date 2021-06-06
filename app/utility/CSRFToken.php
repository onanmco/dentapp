<?php

use app\utility\Auth;

namespace app\utility;

use Exception;

class CSRFToken
{
    public static function generate()
    {
        $_SESSION['_token'] = bin2hex(random_bytes(16));
        echo '<input type="hidden" name="_token" value="' . $_SESSION['_token'] . '">';
    }

    public static function check()
    {
        if (!isset($_SESSION['_token']) || $_SESSION['_token'] !== $_POST['_token']) {
            $message = \app\constant\en\Messages::INVALID_CSRF_TOKEN();
            $error = \app\constant\en\Responses::UNAUTHORIZED_ACCESS($message);
            Auth::setLastVisit('/');
            throw new Exception($error['message'], $error['code']);
        }    
    }
}