<?php

namespace app\controller\api;

use app\constant\Environment;
use app\constant\Messages;
use app\model\Hasta;
use app\utility\CommonValidator;
use core\Controller;
use core\Request;
use core\Response;

class HastaController extends Controller
{
    public function silAction()
    {
        $id = $this->args['id'];
        $existing_hasta = Hasta::findById($id);
        $result = $existing_hasta->delete();
        Response::json_dispatch($result, 200);
    }

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
        $isim_validation = CommonValidator::isValidName($request_body['isim'], 'İsim');
        if ($isim_validation !== true) {
            foreach ($isim_validation as $error_msg) {
                $errors[] = [
                    'title' => 'Doğrulama Hatası',
                    'message' => $error_msg,
                    'code' => 400
                ];
            }
        }
        $soyisim_validation = CommonValidator::isValidName($request_body['soyisim'], 'Soyisim');
        if ($soyisim_validation !== true) {
            foreach ($soyisim_validation as $error_msg) {
                $errors[] = [
                    'title' => 'Doğrulama Hatası',
                    'message' => $error_msg,
                    'code' => 400
                ];
            }
        }
        $telefon_validation = CommonValidator::isValidPhone($request_body['telefon'], 'telefon');
        if ($telefon_validation !== true) {
            foreach ($telefon_validation as $error_msg) {
                $errors[] = [
                    'title' => 'Doğrulama Hatası',
                    'message' => $error_msg,
                    'code' => 400
                ];
            }
        }
        $tckn_validation = CommonValidator::isValidTckn($request_body['tckn'], 'TCKN');
        if ($tckn_validation !== true) {
            foreach ($tckn_validation as $error_msg) {
                $errors[] = [
                    'title' => 'Doğrulama Hatası',
                    'message' => $error_msg,
                    'code' => 400
                ];
            }
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

    public function araAction()
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
        if (!isset($request_body['value'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'Aranacak değer eksik.',
                'code' => 400
            ];
        }
        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }
        if (!preg_match(Environment::COMPOSITE_SEARCH_CHARSET, $request_body['value'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Aranacak değer string(yazı) tipinde olmalıdır.',
                'code' => 400
            ];
        }
        $request_body['value'] = preg_replace('/\s+/', ' ', trim($request_body['value'], " \t"));
        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }
        if (strlen($request_body['value']) < Environment::COMPOSITE_SEARCH_MIN_LENGTH) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Aranacak değer en az ' . Environment::COMPOSITE_SEARCH_MIN_LENGTH .' karakter içermelidir.',
                'code' => 400
            ];
        }
        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }
        $splitted = explode(' ', $request_body['value']);
        $results = [];
        foreach ($splitted as $value) {
            $temp = Hasta::findByIsimOrSoyisimOrTckn($value);
            if ($temp === false) {
                $error = Messages::DB_READ_ERROR;
                Response::json([$error], $error['code']);
                exit;
            }
            foreach ($temp as $record) {
                $results[] = $record->serialize();
            }
        }
        $data = [
            'title' => 'Başarılı',
            'message' => 'Arama başarıyla tamamlandı.',
            'code' => 200,
            'sonuclar' => $results
        ];
        Response::json($data, 200);
        exit;
    }
}