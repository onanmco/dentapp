<?php

namespace app\controller\api;

use app\constant\Constants;
use app\constant\Fields;
use app\constant\Messages;
use app\constant\Responses;
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
            $error = Responses::BAD_REQUEST(Messages::POST_METHOD());
            Response::json([$error], $error['code']);
            exit;
        }

        $request_body = false;
        try {
            $request_body = json_decode(file_get_contents("php://input"), true);
        } catch (\Throwable $th) {
            $error = Responses::BAD_REQUEST(Messages::APPLICATION_JSON());
            Response::json([$error], $error['code']);
            exit;
        }
        if ($request_body == false) {
            $error = Responses::BAD_REQUEST(Messages::JSON_DECODING_ERROR());
            Response::json([$error], $error['code']);
            exit;
        }

        if (!isset($request_body[Fields::FULL_NAME])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::FULL_NAME));
        }
        if (!isset($request_body[Fields::EMAIL])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::EMAIL));
        }
        if (!isset($request_body[Fields::MESSAGE])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::MESSAGE));
        }

        if (!empty($errors)) {
            Response::json($errors, Responses::VALIDATION_ERROR()['code']);
            exit;
        }

        $content  = "<p>Sayın sistem yöneticisi, yeni bir mesajınız var:</p>";
        $content .= "<p><b>İsim:</b>"    . $request_body['full_name'] . "</p>";
        $content .= "<p><b>E-posta:</b>" . $request_body['email']     . "</p>";
        $content .= "<p><b>Mesaj:</b>"   . $request_body['message']   . "</p>";

        $payload = [
            'from'    => Config::CLIENT_EMAIL_OUTGOING,
            'to'      => Config::CLIENT_EMAIL_INCOMING,
            'subject' => Constants::CONTACT_EMAIL_TITLE,
            'content' => $content
        ];

        $mail = new Mail($payload);

        $validation = $mail->validate();

        if ($validation === false) {
            Response::json($mail->getErrors(), Responses::VALIDATION_ERROR()['code']);
            exit;
        }

        $result = $mail->send();
        
        if ($result === false) {
            Response::json($mail->getErrors(), Responses::UNKNOWN_ERROR()['code']);
            exit;
        }

        $payload = Responses::SUCCESS(Messages::MAIL_SENT_SUCCESSFULLY());

        Response::json($payload, $payload['code']);
        exit;
    }
}