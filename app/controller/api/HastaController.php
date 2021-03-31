<?php

namespace app\controller\api;

use app\constant\Constants;
use app\constant\Constraints;
use app\constant\Environment;
use app\constant\Fields;
use app\constant\Messages;
use app\constant\Responses;
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
            $error = Responses::BAD_REQUEST(Messages::POST_METHOD());
            Response::json([$error], $error['code']);
            exit;
        }
        // Parse request body
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
        if (!isset($request_body[Fields::NAME])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::NAME));
        }
        if (!isset($request_body[Fields::SURNAME])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::SURNAME));
        }
        if (!isset($request_body[Fields::PHONE])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::PHONE));
        }
        if (!isset($request_body[Fields::TCKN])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::TCKN));
        }
        if (!empty($errors)) {
            Response::json($errors, Responses::VALIDATION_ERROR()['code']);
            exit;
        }
        // validation
        $name_validation = CommonValidator::isValidName($request_body[Fields::NAME], Fields::NAME);
        if ($name_validation !== true) {
            foreach ($name_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $surname_validation = CommonValidator::isValidName($request_body[Fields::SURNAME], Fields::SURNAME);
        if ($surname_validation !== true) {
            foreach ($surname_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $phone_validation = CommonValidator::isValidPhone($request_body[Fields::PHONE], Fields::PHONE);
        if ($phone_validation !== true) {
            foreach ($phone_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $tckn_validation = CommonValidator::isValidTckn($request_body[Fields::TCKN], Fields::TCKN);
        if ($tckn_validation !== true) {
            foreach ($tckn_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $existing_patient = Hasta::findByTckn($request_body[Fields::TCKN]);
        if ($existing_patient) {
            $errors[] = Responses::VALIDATION_ERROR(Messages::PATIENT_WITH_TCKN_ALREADY_EXISTS());
        }
        if (!empty($errors)) {
            Response::json($errors, Responses::VALIDATION_ERROR()['code']);
            exit;
        }
        // registration
        $patient = new Hasta($request_body);
        $result = $patient->save();
        if (!$result) {
            $error = Responses::UNKNOWN_ERROR(Messages::DB_WRITE_ERROR());
            Response::json([$error], $error['code']);
            exit;
        }
        $saved_patient = Hasta::findByTckn($patient->getTckn());
        if (!$saved_patient) {
            $error = Responses::UNKNOWN_ERROR(Messages::DB_READ_ERROR());
            Response::json([$error], $error['code']);
            exit;
        }
        // return the saved record
        $data = Responses::SUCCESS(Messages::PATIENT_REGISTERED_SUCCESSFULLY());
        $data['kaydedilen_hasta'] = $saved_patient->serialize();
        Response::json($data, $data['code']);
        exit;
    }

    public function araAction()
    {
        $errors = [];
        // Check if request method is correct
        if (Request::method() !== 'post') {
            $error = Responses::BAD_REQUEST(Messages::POST_METHOD());
            Response::json([$error], $error['code']);
            exit;
        }
        // Parse request body
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
        if (!isset($request_body['value'])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::SEARCH_TERM));
        }
        if (!empty($errors)) {
            Response::json($errors, Responses::MISSING_FIELD()['code']);
            exit;
        }
        $rule = Constraints::COMPOSITE_SEARCH_REGEXP(Fields::COMPOSITE_SEARCH);
        if (!preg_match($rule['value'], $request_body['value'])) {
            $errors[] = Responses::VALIDATION_ERROR($rule['message']);
        }
        $request_body['value'] = preg_replace('/\s+/', ' ', trim($request_body['value'], " \t"));
        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }
        $rule = Constraints::COMPOSITE_SEARCH_MIN_LEN(Fields::COMPOSITE_SEARCH);
        if (strlen($request_body['value']) < $rule['value']) {
            $errors[] = Responses::VALIDATION_ERROR($rule['message']);
        }
        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }
        $splitted = explode(' ', $request_body['value']);
        $existing_records = [];
        foreach ($splitted as $value) {
            $search_results = Hasta::findByIsimOrSoyisimOrTckn($value);
            if ($search_results === false) {
                $error = Responses::UNKNOWN_ERROR(Messages::DB_READ_ERROR());
                Response::json([$error], $error['code']);
                exit;
            }
            foreach ($search_results as $search_result) {
                $is_exist = false;
                foreach ($existing_records as $existing_record) {
                    if ($existing_record['id'] == $search_result->getId()) {
                        $is_exist = true;
                        break;
                    }
                }
                if (!$is_exist) {
                    $existing_records[] = $search_result->serialize();
                }
            }
        }
        
        $data = Responses::SUCCESS(Messages::SEARCH_COMPLETED());
        $data['sonuclar'] = $existing_records;
        Response::json($data, Responses::SUCCESS()['code']);
        exit;
    }
}