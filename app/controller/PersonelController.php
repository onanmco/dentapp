<?php

namespace app\controller;

use app\constant\Messages;
use app\model\ApiToken;
use app\model\Meslek;
use app\model\Personel;
use app\utility\Auth;
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
        if (!isset($_POST['isim'])) {
            throw new Exception('personel kayit formunda isim alani eksik.', 500);
        }
        if (!isset($_POST['soyisim'])) {
            throw new Exception('personel kayit formunda soyisim alani eksik.', 500);
        }
        if (!isset($_POST['email'])) {
            throw new Exception('personel kayit formunda email alani eksik.', 500);
        }
        if (!isset($_POST['sifre'])) {
            throw new Exception('personel kayit formunda sifre alani eksik.', 500);
        }
        if (!isset($_POST['tckn'])) {
            throw new Exception('personel kayit formunda tckn alani eksik.', 500);
        }

        $_POST['isim'] = trim($_POST['isim'], " \t");
        $_POST['isim'] = preg_replace('/\s+/', ' ', $_POST['isim']);
        $_POST['soyisim'] = trim($_POST['soyisim'], " \t");
        $_POST['soyisim'] = preg_replace('/\s+/', ' ', $_POST['soyisim']);

        $errors = [];

        if (strlen($_POST['isim']) < 1) {
            $errors[] = 'İsim alanı boş bırakılamaz.';
        }
        if (!preg_match('/^[a-zA-Z\s\.\'\-ığüşöçİĞÜŞÖÇ]*$/', $_POST['isim'])) {
            $errors[] = 'İsim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire içerebilir.';
        }
        if (strlen($_POST['soyisim']) < 1) {
            $errors[] = 'Soyisim alanı boş bırakılamaz.';
        }
        if (!preg_match('/^[a-zA-Z\s\.\'\-ığüşöçİĞÜŞÖÇ]*$/', $_POST['soyisim'])) {
            $errors[] = 'Soyisim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire içerebilir.';
        }
        if (strlen($_POST['email']) < 1) {
            $errors[] = 'Email alanı boş bırakılamaz.';
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Lütfen geçerli bir email adresi girin.';
        }
        if (strlen($_POST['sifre']) < 8) {
            $errors[] = 'Şifre alanı en az 8 karakter içermelidir.';
        }
        if (strlen($_POST['sifre']) > 20) {
            $errors[] = 'Şifre alanı en fazla 20 karakter içermelidir.';
        }
        if (!preg_match('/^[\@\!\^\+\%\/\(\)\=\?\_\*\-\<\>\#\$\½\{\[\]\}\\\|\w]*$/', $_POST['sifre'])) {
            $errors[] = 'Şifre alanı yalnızca harf, sayı, özel karakterler, tire ve nokta içerebilir.';
        }
        if (!preg_match('/[A-Z]/', $_POST['sifre'])) {
            $errors[] = 'Şifre alanı en az bir adet büyük harf içermelidir.';
        }
        if (!preg_match('/[\d]/', $_POST['sifre'])) {
            $errors[] = 'Şifre alanı en az bir adet rakam içermelidir.';
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