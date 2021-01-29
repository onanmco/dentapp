<?php

namespace app\controller\api;

use app\constant\Messages;
use app\model\Hasta;
use core\Controller;
use core\Request;
use core\Response;

class HastaController extends Controller
{
    public function kayitAction()
    {
        $errors = [];
        // Check if request method is correct
        if (Request::method() !== 'post') {
            $error = Messages::POST_METHOD;
            Response::json_failure($error['title'], [$error['message']], $error['code']);
            exit;
        }
        // Parse request body
        $request_body = false;
        try {
            $request_body = json_decode(file_get_contents("php://input"), true);
        } catch (\Throwable $th) {
            $error = Messages::BILINMEYEN_HATA;
            Response::json_failure($error['title'], [$error['message']], $error['code']);
            exit;
        }
        if ($request_body == false) {
            $error = Messages::BILINMEYEN_HATA;
            Response::json_failure($error['title'], [$error['message']], $error['code']);
            exit;
        }
        if (!isset($request_body['isim'])) {
            $errors[] = 'isim alanı eksik.';
        }
        if (!isset($request_body['soyisim'])) {
            $errors[] = 'soyisim alanı eksik.';
        }
        if (!isset($request_body['telefon'])) {
            $errors[] = 'telefon alanı eksik.';
        }
        if (!isset($request_body['tckn'])) {
            $errors[] = 'TCKN alanı eksik.';
        }
        if (!empty($errors)) {
            Response::json_failure('Hatalı istek', $errors);
            exit;
        }
        // validation
        if (strlen($request_body['isim']) < 1) {
            $errors[] = 'İsim alanı boş bırakılamaz.';
        }
        if (!preg_match('/^[a-zA-Z\s\.\'\-ğüşöçİĞÜŞÖÇ]*$/', $request_body['isim'])) {
            $errors[] = 'İsim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire karakterleri içerebilir.';
        }
        if (strlen($request_body['soyisim']) < 1) {
            $errors[] = 'Soyisim alanı boş bırakılamaz.';
        }
        if (!preg_match('/^[a-zA-Z\s\.\'\-ğüşöçİĞÜŞÖÇ]*$/', $request_body['soyisim'])) {
            $errors[] = 'Soyisim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire karakterleri içerebilir.';
        }
        if (!preg_match('/^0\d{10}$/', $request_body['telefon'])) {
            $errors[] = 'Lütfen geçerli bir telefon girin.';
        }
        if (strlen($request_body['tckn']) < 1) {
            $errors[] = 'TCKN alanı boş bırakılamaz.';
        }
        if (!preg_match('/^\d{11}$/', $request_body['tckn'])) {
            $errors[] = 'Lütfen geçerli bir TCKN girin.';
        }
        $existing_hasta = Hasta::findByTckn($request_body['tckn']);
        if ($existing_hasta) {
            $errors[] = 'Bu TCKN ile zaten kayıtlı bir hasta mevcut.';
        }
        if (!empty($errors)) {
            Response::json_failure('Doğrulama hatası', $errors);
            exit;
        }
        // registration
        $hasta = new Hasta($request_body);
        $result = $hasta->save();
        if (!$result) {
            $error = Messages::BILINMEYEN_HATA;
            Response::json_failure($error['title'], [$error['message']], $error['code']);
            exit;
        }
        $saved_hasta = Hasta::findByTckn($hasta->getTckn());
        if (!$saved_hasta) {
            $error = Messages::BILINMEYEN_HATA;
            Response::json_failure($error['title'], [$error['message']], $error['code']);
            exit;
        }
        // return the saved record
        Response::json_success('Hasta başarıyla kaydedildi.', $saved_hasta->serialize());
        exit;
    }
}