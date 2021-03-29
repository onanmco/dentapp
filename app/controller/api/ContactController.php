<?php

namespace app\controller\api;

use app\constant\Messages;
use app\utility\CommonValidator;
use app\utility\Mail;
use config\Config;
use core\Controller;
use core\Request;
use core\Response;

class ContactController extends Controller
{
    public function gonderAction() 
    {
        $errors = [];

        if (Request::method() !== 'post') {
            $error = Messages::POST_METHOD;
            Response::json([$error], $error['code']);
            exit;
        }

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

        if (!isset($request_body['full_name'])) {
            $errors[] = [
                'title' => 'Eksik alan',
                'message' => 'İsim alanı eksik.',
                'code' => 400
            ];
        }
        if (!isset($request_body['email'])) {
            $errors[] = [
                'title' => 'Eksik alan',
                'message' => 'E-mail alanı eksik.',
                'code' => 400
            ];
        }
        if (!isset($request_body['message'])) {
            $errors[] = [
                'title' => 'Eksik alan',
                'message' => 'Mesaj alanı eksik.',
                'code' => 400
            ];
        }

        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }

        $full_name_validation = CommonValidator::isValidName($request_body['full_name']);
        if ($full_name_validation !== true) {
            foreach ($full_name_validation as $errorMsg) {
                $errors[] = [
                    'title' => 'Doğrulama Hatası',
                    'message' => $errorMsg,
                    'code' => 400                    
                ];
            }
        }

        $email_validation = CommonValidator::isValidEmail($request_body['email'], 'E-mail');
        if ($email_validation !== true) {
            foreach ($email_validation as $errorMsg) {
                $errors[] = [
                    'title' => 'Doğrulama Hatası',
                    'message' => $errorMsg,
                    'code' => 400
                ];
            }
        }

        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }

        $from = Config::CLIENT_EMAIL_OUTGOING;
        $to = Config::CLIENT_EMAIL_INCOMING;
        $subject = 'New Contact Form';
        $content = 
        'Name: '    . $request_body['full_name'] . '\n' .
        'E-mail: '  . $request_body['email']     . '\n' .
        'Message: ' . $request_body['message'];

        $result = Mail::send($to, $from, $subject, $content);
        
        if (!$result) {
            $errors[] = Messages::UNKNOWN_ERROR;
            Response::json($errors, Messages::UNKNOWN_ERROR['code']);
            exit;
        }

        $payload = Messages::MAIL_SENT_SUCCESSFULLY;

        Response::json($payload, Messages::MAIL_SENT_SUCCESSFULLY['code']);
        exit;
    }
}