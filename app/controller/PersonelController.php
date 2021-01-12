<?php

namespace app\controller;

use app\constant\Messages;
use app\model\ApiToken;
use app\model\Personel;
use app\utility\Auth;
use app\utility\Popup;
use app\utility\Token;
use app\utility\UtilityFunctions;
use app\utility\Validator;
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
        $_POST['isim'] = UtilityFunctions::sanitizeName($_POST['isim']);
        $_POST['soyisim'] = UtilityFunctions::sanitizeName($_POST['soyisim']);
        $rules = [
            ['İsim', $_POST['isim'], 'REQUIRED'],
            ['İsim', $_POST['isim'], 'CAN', ['regexp' => 'a-zA-Z\s\.\'\-ğüşöçİĞÜŞÖÇ', 'verbal' => 'harf, boşluk, nokta, kesme işareti ve tire']],
            ['Soyisim', $_POST['soyisim'], 'REQUIRED'],
            ['Soyisim', $_POST['soyisim'], 'CAN', ['regexp' => 'a-zA-Z\s\.\'\-ğüşöçİĞÜŞÖÇ', 'verbal' => 'harf, boşluk, nokta, kesme işareti ve tire']],
            ['E-mail', $_POST['email'], 'REQUIRED'],
            ['E-mail', $_POST['email'], 'EMAIL'],
            ['Şifre', $_POST['sifre'], 'MIN', ['min' => 8]],
            ['Şifre', $_POST['sifre'], 'MAX', ['max' => 20]],
            ['Şifre', $_POST['sifre'], 'CAN', ['regexp' => '\@\!\^\+\%\/\(\)\=\?\_\*\-\<\>\#\$\½\{\[\]\}\\\|\w', 'verbal' => 'harf, sayı, özel karakterler, tire ve nokta']],
            ['Şifre', $_POST['sifre'], 'MUST', ['regexp' => 'A-Z', 'verbal' => 'büyük harf']],
            ['Şifre', $_POST['sifre'], 'MUST', ['regexp' => '\d', 'verbal' => 'rakam']],
            ['TCKN', $_POST['tckn'], 'TCKN'],
            ['Maaş', $_POST['maas'], 'NUMBER'],
            ['Meslek', $_POST['meslek'], 'MESLEK']
            
        ];
        $validator = new Validator();
        $errors = $validator->validateAll($rules);
        if (!Personel::isEmailAvailable($_POST['email'])) {
            $errors[] = Messages::GECERSIZ_EMAIL['message'];
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
            Popup::add(Messages::KAYIT_BASARILI);
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
            $errors[] = Messages::HESAP_BULUNAMADI;
            $requested_personel = new Personel($_POST);
            View::render('login.php', ['errors' => $errors, 'personel' => $requested_personel]);
            exit;
        }
        if (!password_verify($_POST['sifre'], $existing_personel->getPasswordHash())) {
            $errors[] = Messages::SIFRE_YANLIS;
            $requested_personel = new Personel($_POST);
            View::render('login.php', ['errors' => $errors, 'personel' => $requested_personel]);
        }
        Auth::login($existing_personel);
        Popup::add(Messages::HOSGELDINIZ);
        Router::redirectAfterPost(Auth::getLastVisit());
    }

    public function cikisAction()
    {
        Auth::logout();
        Router::redirectAfterPost('/personel/bye');
    }
    
    public function byeAction()
    {
        Popup::add(Messages::CIKIS_BASARILI);
        Router::redirectAfterPost('/');
    }
}