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
            Response::json([$error], $error['code']);
            exit;
        }
        // Parse request body
        $request_body = false;
        try {
            $request_body = json_decode(file_get_contents("php://input"), true);
        } catch (\Throwable $th) {
            $error = Messages::APPLICATION_JSON;
            Response::json([$error], $error['code']);
            exit;
        }
        if ($request_body == false) {
            $error = Messages::JSON_DECODING_ERROR;
            Response::json([$error], $error['code']);
            exit;
        }
        if (!isset($request_body['isim'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'isim alanı eksik.',
                'code' => 400
            ];
        }
        if (!isset($request_body['soyisim'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'soyisim alanı eksik.',
                'code' => 400
            ];
        }
        if (!isset($request_body['telefon'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'telefon alanı eksik.',
                'code' => 400
            ];
        }
        if (!isset($request_body['tckn'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'TCKN alanı eksik.',
                'code' => 400
            ];
        }
        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }
        // validation
        if (strlen($request_body['isim']) < 1) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'İsim alanı boş bırakılamaz.',
                'code' => 400
            ];
        }
        if (!preg_match('/^[a-zA-Z\s\.\'\-ğüşöçİĞÜŞÖÇ]*$/', $request_body['isim'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'İsim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire karakterleri içerebilir.',
                'code' => 400
            ];
        }
        if (strlen($request_body['soyisim']) < 1) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Soyisim alanı boş bırakılamaz.',
                'code' => 400
            ];
        }
        if (!preg_match('/^[a-zA-Z\s\.\'\-ğüşöçİĞÜŞÖÇ]*$/', $request_body['soyisim'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Soyisim alanı yalnızca harf, boşluk, nokta, kesme işareti ve tire karakterleri içerebilir.',
                'code' => 400
            ];
        }
        if (!preg_match('/^0\d{10}$/', $request_body['telefon'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Lütfen geçerli bir telefon girin.',
                'code' => 400
            ];
        }
        if (strlen($request_body['tckn']) < 1) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'TCKN alanı boş bırakılamaz.',
                'code' => 400
            ];
        }
        if (!preg_match('/^\d{11}$/', $request_body['tckn'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Lütfen geçerli bir TCKN girin.',
                'code' => 400
            ];
        }
        $existing_hasta = Hasta::findByTckn($request_body['tckn']);
        if ($existing_hasta) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Bu TCKN ile kayıtlı bir hasta zaten mevcut.',
                'code' => 400
            ];
        }
        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }
        // registration
        $hasta = new Hasta($request_body);
        $result = $hasta->save();
        if (!$result) {
            $error = Messages::DB_WRITE_ERROR;
            Response::json([$error], $error['code']);
            exit;
        }
        $saved_hasta = Hasta::findByTckn($hasta->getTckn());
        if (!$saved_hasta) {
            $error = Messages::DB_READ_ERROR;
            Response::json([$error], $error['code']);
            exit;
        }
        // return the saved record
        $data = [
            'title' => 'Başarılı',
            'message' => 'Hasta başarıyla kaydedildi.',
            'code' => 200,
            'kaydedilen_hasta' => $saved_hasta->serialize()
        ];
        Response::json($data, 200);
        exit;
    }
}