<?php

namespace app\controller\api;

use app\constant\Messages;
use app\model\Hasta;
use app\model\Personel;
use app\model\Randevu;
use core\Controller;
use core\Request;
use core\Response;

class RandevuController extends Controller
{
    public function olusturAction()
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
        if (!isset($request_body['personel_id'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'personel_id alanı eksik.',
                'code' => 400,
            ];
        }
        if (!isset($request_body['hasta_id'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'hasta_id alanı eksik.',
                'code' => 400
            ];
        }
        if (!isset($request_body['baslangic'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'Başlangıç tarihi-zaman alanı eksik.',
                'code' => 400
            ];
        }
        if (!isset($request_body['bitis'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'Bitiş tarihi-zaman alanı eksik.',
                'code' => 400
            ];
        }
        if (!isset($request_body['notlar'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'notlar alanı eksik.',
                'code' => 400
            ];
        }
        if (!isset($request_body['hatirlat'])) {
            $errors[] = [
                'title' => 'Eksik Alan',
                'message' => 'hatirlat alanı eksik.',
                'code' => 400
            ];
        }        
        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }
        //validation
        if (!preg_match('/\d+/', $request_body['personel_id'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Personel id 0\'dan büyük bir sayı olmalıdır.',
                'code' => 400
            ];
        }
        if (!Personel::findById($request_body['personel_id'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Sistemimize ' . $request_body['personel_id'] . ' id\'siyle kayıtlı bir personel bulunamadı.',
                'code' => 400
            ];
        }
        if (!preg_match('/\d+/', $request_body['hasta_id'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Hasta id 0\'dan büyük bir sayı olmalıdır.',
                'code' => 400
            ];
        }
        if (!Hasta::findById($request_body['hasta_id'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Sistemimize ' . $request_body['hasta_id'] . ' id\'siyle kayıtlı bir hasta bulunamadı.',
                'code' => 400
            ];
        }
        if (!(((string) (int) $request_body['baslangic'] === $request_body['baslangic']) 
        && ((int) $request_body['baslangic'] <= PHP_INT_MAX)
        && ((int) $request_body['baslangic'] >= ~PHP_INT_MAX))) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Başlangıç tarihi UNIX timestamp formatında olmalıdır.',
                'code' => 400
            ];
        }
        if (!(((string) (int) $request_body['bitis'] === $request_body['bitis']) 
        && ((int) $request_body['bitis'] <= PHP_INT_MAX)
        && ((int) $request_body['bitis'] >= ~PHP_INT_MAX))) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Bitiş tarihi UNIX timestamp formatında olmalıdır.',
                'code' => 400
            ];
        }
        if ($request_body['baslangic'] >= $request_body['bitis']) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Başlangıç saati bitiş saatinden önce olmalıdır.',
                'code' => 400
            ];
        }
        if ($request_body['baslangic'] < time()) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Geçmiş tarihe randevu veremezsiniz.',
                'code' => 400
            ];
        }
        $request_body['baslangic'] = date('Y-m-d H:i:s', $request_body['baslangic']);
        $request_body['bitis'] = date('Y-m-d H:i:s', $request_body['bitis']);
        if (Randevu::getOverlappingCount($request_body['baslangic'], $request_body['bitis']) > 0) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Girmiş olduğunuz aralıkta başka bir randevu var.',
                'code' => 400
            ];
        }
        if (!is_string($request_body['notlar'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Notlar sadece String(yazı) formatında olmalıdır.',
                'code' => 400
            ];
        }
        if (!is_bool($request_body['hatirlat'])) {
            $errors[] = [
                'title' => 'Doğrulama Hatası',
                'message' => 'Hatırlat alanı sadece boolean true false değerlerini alabilir.',
                'code' => 400
            ];
        }
        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }
        // registration
        $randevu = new Randevu($request_body);
        if (!$randevu->save()) {
            $error = Messages::DB_WRITE_ERROR;
            Response::json([$error], 500);
        }
        $saved_randevu = Randevu::getByRange($request_body['baslangic'], $request_body['bitis']);
        if (!$saved_randevu) {
            $error = Messages::DB_READ_ERROR;
            Response::json([$error], 500);
        }
        $data = [
            'title' => 'Başarılı',
            'message' => 'Randevu başarıyla kaydedildi.',
            'code' => 200,
            'kaydedilen_randevu' => $saved_randevu->serialize()
        ];
        Response::json($data, 200);
        exit;
    }
}