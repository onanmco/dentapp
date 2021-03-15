<?php

namespace app\controller;

use app\constant\Messages;
use app\model\ApiToken;
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

        $isim_validation = CommonValidator::isValidName($_POST['isim'], 'İsim');
        if ($isim_validation !== true) {
            array_merge($errors, $isim_validation);
        }
        $soyisim_validation = CommonValidator::isValidName($_POST['soyisim'], 'Soyisim');
        if ($soyisim_validation !== true) {
            array_merge($errors, $soyisim_validation);
        }
        $email_validation = CommonValidator::isValidEmail($_POST['email'], 'E-mail');
        if ($email_validation !== true) {
            array_merge($errors, $email_validation);
        }
        $sifre_validation = CommonValidator::isValidPassword($_POST['sifre'], 8, 20, 'Şifre');
        if ($sifre_validation !== true) {
            array_merge($errors, $sifre_validation);
        }
        if (!preg_match('/(^$)|(^\d{11}$)/', $_POST['tckn'])) {
            $errors[] = 'Lütfen geçerli bir TCKN girin.';
        }
        if (!Meslek::isExist($_POST['meslek_id'])) {
            $errors[] = 'Seçmiş olduğunuz meslek türü sistemimizde yer almamaktadır.';
        }
        if (!Personel::isEmailAvailable($_POST['email'])) {
            $errors[] = Messages::INVALID_EMAIL['message'];
        }
        if (empty($errors)) {
            $_POST['password_hash'] = password_hash($_POST['sifre'], PASSWORD_DEFAULT);
            $token = new Token();
            $_POST['api_token'] = $token->getValue();
            $personel = new Personel($_POST);
            $result = $personel->save();
            if ($result === false) {
                throw new Exception("An error occurred when inserting a valid new personel to db.\nEmail: " . $personel->getEmail());
            }
            $saved_personel = Personel::findByApiToken($token->getValue());
            if ($saved_personel === false) {
                throw new Exception("An error occurred when finding a saved personel by its api_token value.\nemail: " . $personel->getEmail() . "\napi_token: " . $personel->getApiToken(), 500);
            }
            $api_token_record = new ApiToken([
                'personel_id' => $saved_personel->getId(),
                'api_token_hash' => $token->getHash()
            ]);
            $result = $api_token_record->save();
            if ($result === false) {
                $saved_personel->delete();
                throw new Exception("An error occurred when inserting api_token_hash of a saved personel to api_tokens table. Deleting user.\nDeleted user email: " . $saved_personel->getEmail() . "\nDeleted user id: " . $saved_personel->getId(), 500);
            }
            Popup::add(Messages::REGISTER_SUCCESSFUL);
            Router::redirectAfterPost('/');
        } else {
            $personel = new Personel($_POST);
            View::render('signup.php', ['personel' => $personel, 'errors' => $errors]);
        }            
    }

    public function girisAction()
    {
        View::render('login.php');
    }

    public function authAction()
    {
        $errors = [];
        $existing_personel = Personel::findByEmail($_POST['email']);
        if ($existing_personel === false) {
            $errors[] = Messages::ACCOUNT_CANNOT_FOUND['message'];
            $requested_personel = new Personel($_POST);
            View::render('login.php', ['errors' => $errors, 'personel' => $requested_personel]);
            exit;
        }
        if (!password_verify($_POST['sifre'], $existing_personel->getPasswordHash())) {
            $errors[] = Messages::WRONG_PASSWORD['message'];
            $requested_personel = new Personel($_POST);
            View::render('login.php', ['errors' => $errors, 'personel' => $requested_personel]);
            exit;
        }
        Auth::login($existing_personel);
        Popup::add(Messages::WELCOME);
        Router::redirectAfterPost(Auth::getLastVisit());
    }

    public function cikisAction()
    {
        Auth::logout();
        Router::redirectAfterPost('/personel/bye');
    }
    
    public function byeAction()
    {
        Popup::add(Messages::LOGOUT_SUCCESSFUL);
        Router::redirectAfterPost('/');
    }
}