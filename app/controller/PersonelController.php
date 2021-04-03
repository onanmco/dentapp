<?php

namespace app\controller;

use app\constant\Fields;
use app\constant\Messages;
use app\constant\Responses;
use app\model\Meslek;
use app\model\Personel;
use app\utility\Auth;
use app\utility\CommonValidator;
use app\utility\Popup;
use app\utility\Token;
use core\Controller;
use core\Router;
use core\View;
use Exception;

class PersonelController extends Controller
{
    public function kayitAction()
    {
        View::render('signup.php');
    }

    public function olusturAction()
    {
        $errors = [];
        $existing_staff = new Personel($_POST);

        $required_fields = [Fields::NAME(), Fields::SURNAME(), Fields::EMAIL(), Fields::PASSWORD(), Fields::STAFF_GROUP_ID()];

        foreach ($required_fields as $field_name) {
            if (empty($_POST[$field_name])) {
                $errors[] = Messages::CANNOT_BE_EMPTY($field_name);
            }
        }

        if (!empty($errors)) {
            View::render('signup.php', [
                'personel' => $existing_staff,
                'errors' => $errors
            ]);
            exit;
        }

        $name_validation = CommonValidator::isValidName($_POST[Fields::NAME()], Fields::NAME());
        if ($name_validation !== true) {
            foreach ($name_validation as $error_msg) {
                $errors[] = $error_msg;
            }
        }
        $surname_validation = CommonValidator::isValidName($_POST[Fields::SURNAME()], Fields::SURNAME());
        if ($surname_validation !== true) {
            foreach ($surname_validation as $error_msg) {
                $errors[] = $error_msg;
            }
        }
        $email_validation = CommonValidator::isValidEmail($_POST[Fields::EMAIL()], Fields::EMAIL());
        if ($email_validation !== true) {
            foreach ($email_validation as $error_msg) {
                $errors[] = $error_msg;
            }
        }
        $password_validation = CommonValidator::isValidPassword($_POST[Fields::PASSWORD()], Fields::PASSWORD());
        if ($password_validation !== true) {
            foreach ($password_validation as $error_msg) {
                $errors[] = $error_msg;
            }
        }
        if (!preg_match('/(^$)|(^\d{11}$)/', $_POST[Fields::TCKN()])) {
            $errors[] = Messages::TCKN_REGEXP(Fields::TCKN());
        }
        if (!Meslek::isExist($_POST[Fields::STAFF_GROUP_ID()])) {
            $errors[] = Messages::STAFF_GROUP_ID_NOT_EXIST();
        }
        if (!Personel::isEmailAvailable($_POST[Fields::EMAIL()])) {
            $errors[] = Messages::EMAIL_ALREADY_USED();
        }
        if (empty($errors)) {
            $_POST[Fields::PASSWORD_HASH()] = password_hash($_POST[Fields::PASSWORD()], PASSWORD_DEFAULT);
            $staff = new Personel($_POST);
            $result = $staff->save();
            if ($result === false) {
                throw new Exception(Messages::STAFF_COULD_NOT_BE_SAVED($staff->getEmail()));
            }
            Popup::add(Responses::SUCCESS(Messages::REGISTER_SUCCESSFUL()));
            Router::redirectAfterPost('/');
        } else {
            View::render('signup.php', ['personel' => $existing_staff, 'errors' => $errors]);
        }            
    }

    public function girisAction()
    {
        View::render('login.php');
    }

    public function authAction()
    {
        $errors = [];

        $requested_staff = new Personel($_POST);
        $required_fields = [Fields::EMAIL(), Fields::PASSWORD()];

        foreach ($required_fields as $field_name) {
            if (empty($_POST[$field_name])) {
                $errors[] = Messages::CANNOT_BE_EMPTY($field_name);
            }
        }

        if (!empty($errors)) {
            View::render('login.php', [
                'personel' => $requested_staff,
                'errors' => $errors
            ]);
            exit;
        }

        $existing_staff = Personel::findByEmail($_POST[Fields::EMAIL()]);
        if ($existing_staff === false) {
            $errors[] = Messages::ACCOUNT_CANNOT_FOUND();
            View::render('login.php', ['errors' => $errors, 'personel' => $requested_staff]);
            exit;
        }
        if (!password_verify($_POST[Fields::PASSWORD()], $existing_staff->getPasswordHash())) {
            $errors[] = Messages::WRONG_PASSWORD();
            $requested_staff = new Personel($_POST);
            View::render('login.php', ['errors' => $errors, 'personel' => $requested_staff]);
            exit;
        }
        Auth::login($existing_staff);
        Popup::add(Responses::SUCCESS(Messages::LOGIN_SUCCESSFUL()));
        Router::redirectAfterPost(Auth::getLastVisit());
    }

    public function cikisAction()
    {
        Auth::logout();
        Router::redirectAfterPost('/personel/bye');
    }
    
    public function byeAction()
    {
        Popup::add(Responses::SUCCESS(Messages::LOGOUT_SUCCESSFUL()));
        Router::redirectAfterPost('/');
    }
}