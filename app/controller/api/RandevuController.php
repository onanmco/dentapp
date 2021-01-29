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
        if (!isset($request_body['personel_id'])) {
            $errors[] = 'Personel id alanı eksik.';
        }
        if (!isset($request_body['hasta_id'])) {
            $errors[] = 'Hasta id alanı eksik.';
        }
        if (!isset($request_body['baslangic'])) {
            $errors[] = 'Başlangıç tarih-zamanı eksik.';
        }
        if (!isset($request_body['bitis'])) {
            $errors[] = 'Bitiş tarih-zamanı eksik.';
        }
        if (!isset($request_body['notlar'])) {
            $errors[] = 'Notlar alanı eksik.';
        }
        if (!isset($request_body['hatirlat'])) {
            $errors[] = 'Hatırlat alanı eksik.';
        }        
        if (!empty($errors)) {
            Response::json_failure('Hatalı istek', $errors);
            exit;
        }
        //validation
        if (!preg_match('/\d+/', $request_body['personel_id'])) {
            $errors[] = 'Personel id 0\'dan büyük bir sayı olmalıdır.';
        }
        if (!Personel::findById($request_body['personel_id'])) {
            $errors[] = 'Sistemimize ' . $request_body['personel_id'] . ' id\'siyle kayıtlı bir personel bulunamadı.';
        }
        if (!preg_match('/\d+/', $request_body['hasta_id'])) {
            $errors[] = 'Hasta id 0\'dan büyük bir sayı olmalıdır.';
        }
        if (!Hasta::findById($request_body['hasta_id'])) {
            $errors[] = 'Sistemimize ' . $request_body['hasta_id'] . ' id\'siyle kayıtlı bir hasta bulunamadı.';
        }
        if (((string) (int) $request_body['baslangic'] === $request_body['baslangic']) 
        && ($request_body['baslangic'] <= PHP_INT_MAX)
        && ($request_body['baslangic'] >= ~PHP_INT_MAX)) {
            $errors[] = 'Başlangıç tarihi UNIX timestamp formatında olmalıdır.';
        }
        if (((string) (int) $request_body['bitis'] === $request_body['bitis']) 
        && ($request_body['bitis'] <= PHP_INT_MAX)
        && ($request_body['bitis'] >= ~PHP_INT_MAX)) {
            $errors[] = 'Bitiş tarihi UNIX timestamp formatında olmalıdır.';
        }
        if ($request_body['baslangic'] >= $request_body['bitis']) {
            $errors[] = 'Başlangıç saati bitiş saatinden önce olmalıdır.';
        }
        if ($request_body['baslangic'] < time()) {
            $errors[] = 'Geçmiş tarihe randevu veremezsiniz.';
        }
        if (Randevu::getOverlappingCount($request_body['baslangic'], $request_body['bitis']) > 0) {
            $errors[] = 'Girmiş olduğunuz aralıkta başka bir randevu var.';
        }
        if (!is_string($request_body['notlar'])) {
            $errors[] = 'Notlar sadece String(yazı) formatında olmalıdır.';
        }
        if (!is_bool($request_body['hatirlat'])) {
            $errors[] = 'Hatırlat alanı sadece boolean true false değerlerini alabilir.';
        }
        if (!empty($errors)) {
            Response::json_failure('Doğrulama hatası', $errors);
            exit;
        }
        // registration
        $randevu = new Randevu($request_body);
        if (!$randevu->save()) {
            $error = Messages::BILINMEYEN_HATA;
            Response::json_failure($error['title'], [$error['message']], $error['code']);
        }
        $saved_randevu = Randevu::getBetween($request_body['baslangic'], $request_body['bitis']);
        if (!$saved_randevu) {
            $error = Messages::BILINMEYEN_HATA;
            Response::json_failure($error['title'], [$error['message']], $error['code']);
        }
        $saved_randevu = $saved_randevu[0];
        // return the saved result
        Response::json_success('Randevu başarıyla kaydedildi.', $saved_randevu->serialize());
        exit;
    }
}