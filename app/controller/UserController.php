<?php

namespace app\controller;

use app\constant\Messages;
use app\constant\Responses;
use app\model\Group;
use app\model\Language;
use app\model\User;
use app\utility\Auth;
use app\utility\CommonValidator;
use app\utility\Popup;
use config\Config;
use core\Controller;
use core\Request;
use core\Router;
use core\View;
use Exception;

class UserController extends Controller
{
    public function signupAction()
    {
        View::render('signup.php');
    }

    public function registerAction()
    {
        if (Request::method() !== 'post') {
            $error = Responses::BAD_REQUEST(Messages::POST_METHOD());
            throw new Exception($error['message'], $error['code']);
        }

        $required_fields = ['first_name', 'last_name', 'email', 'password', 'group_id'];
        
        $missing_fields = array_diff($required_fields, array_keys($_POST));

        if (!empty($missing_fields)) {
            $error = Responses::BAD_REQUEST(Messages::MISSING_FIELDS($missing_fields));
            throw new Exception($error['message'], $error['code']);
        }
        
        $errors = [];

        if (!isset($_POST['language_preference'])) {
            $default_lang = Language::findByLanguage(Config::DEFAULT_LANGUAGE);
            $default_lang = $default_lang !== false ? $default_lang->getId() : 1;
            $_POST['language_preference'] = $default_lang;
        }

        $existing_user = new User($_POST);

        foreach ($required_fields as $field_name) {
            if (empty($_POST[$field_name])) {
                $errors[] = Messages::CANNOT_BE_EMPTY($field_name);
            }
        }

        if (!empty($errors)) {
            View::render('signup.php', [
                'user' => $existing_user,
                'errors' => $errors
            ]);
            exit;
        }

        $name_validation = CommonValidator::isValidName($_POST['first_name'], 'first_name');
        if ($name_validation !== true) {
            foreach ($name_validation as $error_msg) {
                $errors[] = $error_msg;
            }
        }
        $surname_validation = CommonValidator::isValidName($_POST['last_name'], 'last_name');
        if ($surname_validation !== true) {
            foreach ($surname_validation as $error_msg) {
                $errors[] = $error_msg;
            }
        }
        $email_validation = CommonValidator::isValidEmail($_POST['email'], 'email');
        if ($email_validation !== true) {
            foreach ($email_validation as $error_msg) {
                $errors[] = $error_msg;
            }
        }
        $password_validation = CommonValidator::isValidPassword($_POST['password'], 'password');
        if ($password_validation !== true) {
            foreach ($password_validation as $error_msg) {
                $errors[] = $error_msg;
            }
        }
        if (!preg_match('/(^$)|(^\d{11}$)/', $_POST['tckn'])) {
            $errors[] = Messages::TCKN_REGEXP('tckn');
        }
        if (!Group::isExist($_POST['group_id'])) {
            $errors[] = Messages::USER_GROUP_ID_NOT_EXIST();
        }
        if (!User::isEmailAvailable($_POST['email'])) {
            $errors[] = Messages::EMAIL_ALREADY_USED();
        }
        if (empty($errors)) {
            $_POST['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $user = new User($_POST);
            $result = $user->save();
            if ($result === false) {
                throw new Exception(Messages::USER_COULD_NOT_BE_SAVED($user->getEmail()));
            }
            Popup::add(Responses::SUCCESS(Messages::REGISTER_SUCCESSFUL()));
            unset($user);
            Router::redirectAfterPost('/');
        } else {
            View::render('signup.php', ['user' => $existing_user, 'errors' => $errors]);
        }            
    }

    public function loginAction()
    {
        View::render('login.php');
    }

    public function authAction()
    {
        $errors = [];

        $requested_user = new User($_POST);
        $required_fields = ['email', 'password'];

        foreach ($required_fields as $field_name) {
            if (empty($_POST[$field_name])) {
                $errors[] = Messages::CANNOT_BE_EMPTY($field_name);
            }
        }

        if (!empty($errors)) {
            View::render('login.php', [
                'user' => $requested_user,
                'errors' => $errors
            ]);
            exit;
        }

        $existing_user = User::findByEmail($_POST['email']);
        if ($existing_user === false) {
            $errors[] = Messages::ACCOUNT_CANNOT_FOUND();
            View::render('login.php', ['errors' => $errors, 'user' => $requested_user]);
            exit;
        }
        if (!password_verify($_POST['password'], $existing_user->getPasswordHash())) {
            $errors[] = Messages::WRONG_PASSWORD();
            $requested_user = new User($_POST);
            View::render('login.php', ['errors' => $errors, 'user' => $requested_user]);
            exit;
        }
        Auth::login($existing_user);
        Popup::add(Responses::SUCCESS(Messages::LOGIN_SUCCESSFUL()));
        Router::redirectAfterPost(Auth::getLastVisit());
    }

    public function logoutAction()
    {
        Auth::logout();
        Router::redirectAfterPost('/user/bye');
    }
    
    public function byeAction()
    {
        Popup::add(Responses::SUCCESS(Messages::LOGOUT_SUCCESSFUL()));
        Router::redirectAfterPost('/');
    }
}