<?php

use app\utility\Auth;

namespace app\utility;

use Exception;

class CSRFToken
{
    public static function generate()
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
        echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
    }

    public static function check()
    {
        if (isset($_SESSION['csrf_token']) && isset($_POST['csrf_token'])) {
            if ($_SESSION['csrf_token'] === $_POST['csrf_token']) {
                return;
            }
        }
        $message = \app\constant\en\Messages::INVALID_CSRF_TOKEN();
        $error = \app\constant\en\Responses::UNAUTHORIZED_ACCESS($message);
        throw new Exception($error['message'], $error['code']);
    }
}