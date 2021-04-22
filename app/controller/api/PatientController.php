<?php

namespace app\controller\api;

use app\constant\Constraints;
use app\constant\Messages;
use app\constant\Responses;
use app\model\Patient;
use app\utility\CommonValidator;
use core\Controller;
use core\Request;
use core\Response;

class PatientController extends Controller
{
    public function deleteAction()
    {
        $id = $this->args['id'];
        $existing_patient = Patient::findById($id);
        $result = $existing_patient->delete();
        Response::json_dispatch($result, 200);
    }

    public function registerAction()
    {
        $errors = [];
        // Check if request method is correct
        if (Request::method() !== 'post') {
            $error = Responses::METHOD_NOT_ALLOWED(Messages::POST_METHOD());
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
        $required_fields = ['first_name', 'last_name', 'phone', 'tckn'];
        foreach ($required_fields as $key) {
            if (!isset($request_body[$key])) {
                $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD($key));
            }
        }
        $invalid_keys = array_diff(array_keys($request_body), $required_fields);
        foreach ($invalid_keys as $v) {
            $errors[] = Responses::INVALID_FIELD(Messages::INVALID_FIELD($v));
        }
        if (!empty($errors)) {
            Response::json($errors, Responses::VALIDATION_ERROR()['code']);
            exit;
        }
        // validation
        $first_name_validation = CommonValidator::isValidName($request_body['first_name'], 'first_name');
        if ($first_name_validation !== true) {
            foreach ($first_name_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $last_name_validation = CommonValidator::isValidName($request_body['last_name'], 'last_name');
        if ($last_name_validation !== true) {
            foreach ($last_name_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $phone_validation = CommonValidator::isValidPhone($request_body['phone'], 'phone');
        if ($phone_validation !== true) {
            foreach ($phone_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $tckn_validation = CommonValidator::isValidTckn($request_body['tckn'], 'tckn');
        if ($tckn_validation !== true) {
            foreach ($tckn_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $existing_patient = Patient::findByTckn($request_body['tckn']);
        if ($existing_patient) {
            $errors[] = Responses::VALIDATION_ERROR(Messages::PATIENT_WITH_TCKN_ALREADY_EXISTS());
        }
        if (!empty($errors)) {
            Response::json($errors, Responses::VALIDATION_ERROR()['code']);
            exit;
        }
        // registration
        $patient = new Patient($request_body);
        $result = $patient->save();
        if (!$result) {
            $error = Responses::UNKNOWN_ERROR(Messages::DB_WRITE_ERROR());
            Response::json([$error], $error['code']);
            exit;
        }
        $saved_patient = Patient::findByTckn($patient->getTckn());
        if (!$saved_patient) {
            $error = Responses::UNKNOWN_ERROR(Messages::DB_READ_ERROR());
            Response::json([$error], $error['code']);
            exit;
        }
        // return the saved record
        $data = Responses::OK(Messages::PATIENT_REGISTERED_SUCCESSFULLY());
        $data['saved_patient'] = $saved_patient->serialize();
        Response::json($data, $data['code']);
        exit;
    }

    public function searchAction()
    {
        $errors = [];
        // Check if request method is correct
        if (Request::method() !== 'post') {
            $error = Responses::METHOD_NOT_ALLOWED(Messages::POST_METHOD());
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
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD('aranacak değer'));
        }
        if (!empty($errors)) {
            Response::json($errors, Responses::MISSING_FIELD()['code']);
            exit;
        }
        $rule = Constraints::COMPOSITE_SEARCH_REGEXP('arama terimi');
        if (!preg_match($rule['value'], $request_body['value'])) {
            $errors[] = Responses::VALIDATION_ERROR($rule['message']);
        }
        $request_body['value'] = preg_replace('/\s+/', ' ', trim($request_body['value'], " \t"));
        if (!empty($errors)) {
            Response::json($errors, 400);
            exit;
        }
        $rule = Constraints::COMPOSITE_SEARCH_MIN_LEN('arama terimi');
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
            $search_results = Patient::findByNameOrTckn($value);
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
        
        $data = Responses::OK(Messages::SEARCH_COMPLETED());
        $data['results'] = $existing_records;
        Response::json($data, Responses::OK()['code']);
        exit;
    }
}