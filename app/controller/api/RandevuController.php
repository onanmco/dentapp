<?php

namespace app\controller\api;

use app\constant\Fields;
use app\constant\Messages;
use app\constant\Responses;
use app\model\Hasta;
use app\model\Personel;
use app\model\Randevu;
use app\utility\CommonValidator;
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
        if (!isset($request_body[Fields::STAFF_ID])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::STAFF_ID));
        }
        if (!isset($request_body[Fields::PATIENT_ID])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::PATIENT_ID));
        }
        if (!isset($request_body[Fields::START])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::START));
        }
        if (!isset($request_body[Fields::END])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::END));
        }
        if (!isset($request_body[Fields::NOTES])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::NOTES));
        }
        if (!isset($request_body[Fields::NOTIFY])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::NOTIFY));
        }        
        if (!isset($request_body[Fields::APPOINTMENT_TYPE_ID])) {
            $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Fields::APPOINTMENT_TYPE_ID));
        }
        if (!empty($errors)) {
            Response::json($errors, Responses::MISSING_FIELD()['code']);
            exit;
        }
        //validation
        $field_name = Fields::STAFF_ID;
        $staff_id_validation = CommonValidator::isValidId($request_body[$field_name], $field_name);
        if ($staff_id_validation !== true) {
            foreach ($staff_id_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        if (!Personel::findById($request_body[$field_name])) {
            $staff_id = $request_body[Fields::STAFF_ID];
            $errors[] = Responses::VALIDATION_ERROR(Messages::STAFF_ID_CANNOT_FOUND($staff_id));
        }
        $patient_id_validation = CommonValidator::isValidId($request_body[Fields::PATIENT_ID], Fields::PATIENT_ID);
        if ($patient_id_validation !== true) {
            foreach ($patient_id_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        if (!Hasta::findById($request_body[Fields::PATIENT_ID])) {
            $patient_id = $request_body[Fields::PATIENT_ID];
            $errors[] = Responses::VALIDATION_ERROR(Messages::PATIENT_ID_CANNOT_FOUND($patient_id));
        }

        $start_validation = CommonValidator::isValidUnixTimestamp($request_body[Fields::START], Fields::START);
        if ($start_validation !== true) {
            foreach ($start_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $end_validation = CommonValidator::isValidUnixTimestamp($request_body[Fields::END], Fields::END);
        if ($end_validation !== true) {
            foreach ($end_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        if ($request_body[Fields::START] >= $request_body[Fields::END]) {
            $errors[] = Responses::VALIDATION_ERROR(Messages::INVALID_START_END_HOURS());
        }
        if ($request_body[Fields::START] < time()) {
            $errors[] = Responses::VALIDATION_ERROR(Messages::APPOINTMENT_TO_PAST());
        }
        $request_body[Fields::START] = date('Y-m-d H:i:s', $request_body[Fields::START]);
        $request_body[Fields::END] = date('Y-m-d H:i:s', $request_body[Fields::END]);
        if (Randevu::getOverlappingCount($request_body[Fields::START], $request_body[Fields::END]) > 0) {
            $errors[] = Responses::VALIDATION_ERROR(Messages::OVERLAPPING_APPOINTMENT());
        }
        if (!is_string($request_body['notlar'])) {
            $field_name = Fields::NOTES;
            $errors[] = Responses::VALIDATION_ERROR(Messages::SHOULD_BE_STRING($field_name));
        }
        if (!is_bool($request_body['hatirlat'])) {
            $field_name = Fields::NOTES;
            $errors[] = Responses::VALIDATION_ERROR(Messages::SHOULD_BE_BOOLEAN($field_name));
        }
        $appointment_type_id_validation = CommonValidator::isValidId($request_body[Fields::APPOINTMENT_TYPE_ID], Fields::APPOINTMENT_TYPE_ID);
        if ($appointment_type_id_validation !== true) {
            foreach ($appointment_type_id_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        if (!empty($errors)) {
            Response::json($errors, Responses::VALIDATION_ERROR()['code']);
            exit;
        }
        // registration
        $randevu = new Randevu($request_body);
        if (!$randevu->save()) {
            $error = Responses::UNKNOWN_ERROR(Messages::DB_WRITE_ERROR());
            Response::json([$error], Responses::UNKNOWN_ERROR()['code']);
        }
        $saved_randevu = Randevu::getByRange($request_body[Fields::START], $request_body[Fields::END]);
        if (!$saved_randevu) {
            $error = Responses::UNKNOWN_ERROR(Messages::DB_READ_ERROR());
            Response::json([$error], Responses::UNKNOWN_ERROR()['code']);
        }
        $data = Responses::SUCCESS(Messages::APPOINTMENT_SAVED());
        $data['kaydedilen_randevu'] = $saved_randevu->serialize();
        Response::json($data, $data['code']);
        exit;
    }
}