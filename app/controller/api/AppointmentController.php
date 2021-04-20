<?php

namespace app\controller\api;

use app\constant\Messages;
use app\constant\Responses;
use app\model\Appointment;
use app\model\Patient;
use app\model\User;
use app\utility\CommonValidator;
use core\Controller;
use core\Request;
use core\Response;

class AppointmentController extends Controller
{
    public function createAction()
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
        $required_fields = [
            'user_id',
            'patient_id',
            'start',
            'end',
            'notes',
            'notify',
            'appointment_type_id'
        ];
        foreach ($required_fields as $key) {
            if (!isset($request_body[$key])) {
                $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD($key));
            }
        }
        $invalid_keys = array_diff(array_keys($request_body), $required_fields);
        foreach ($invalid_keys as $v) {
            $errors[] = Responses::VALIDATION_ERROR(Messages::INVALID_FIELD($v));
        }
        if (!empty($errors)) {
            Response::json($errors, Responses::MISSING_FIELD()['code']);
            exit;
        }
        //validation
        $field_name = 'user_id';
        $user_id_validation = CommonValidator::isValidId($request_body[$field_name], $field_name);
        if ($user_id_validation !== true) {
            foreach ($user_id_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        if (!User::findById($request_body[$field_name])) {
            $user_id = $request_body['user_id'];
            $errors[] = Responses::VALIDATION_ERROR(Messages::USER_ID_CANNOT_FOUND($user_id));
        }
        $patient_id_validation = CommonValidator::isValidId($request_body['patient_id'], 'patient_id');
        if ($patient_id_validation !== true) {
            foreach ($patient_id_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        if (!Patient::findById($request_body['patient_id'])) {
            $patient_id = $request_body['patient_id'];
            $errors[] = Responses::VALIDATION_ERROR(Messages::PATIENT_ID_CANNOT_FOUND($patient_id));
        }

        $start_validation = CommonValidator::isValidUnixTimestamp($request_body['start'], 'start');
        if ($start_validation !== true) {
            foreach ($start_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $end_validation = CommonValidator::isValidUnixTimestamp($request_body['end'], 'end');
        if ($end_validation !== true) {
            foreach ($end_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        if ($request_body['start'] >= $request_body['end']) {
            $errors[] = Responses::VALIDATION_ERROR(Messages::INVALID_START_END_HOURS());
        }
        if ($request_body['start'] < time()) {
            $errors[] = Responses::VALIDATION_ERROR(Messages::APPOINTMENT_TO_PAST());
        }
        $request_body['start'] = date('Y-m-d H:i:s', $request_body['start']);
        $request_body['end'] = date('Y-m-d H:i:s', $request_body['end']);
        if (Appointment::getOverlappingCount($request_body['start'], $request_body['end']) > 0) {
            $errors[] = Responses::VALIDATION_ERROR(Messages::OVERLAPPING_APPOINTMENT());
        }
        if (!is_string($request_body['notes'])) {
            $field_name = 'notes';
            $errors[] = Responses::VALIDATION_ERROR(Messages::SHOULD_BE_STRING($field_name));
        }
        if (!is_bool($request_body['notify'])) {
            $field_name = 'notes';
            $errors[] = Responses::VALIDATION_ERROR(Messages::SHOULD_BE_BOOLEAN($field_name));
        }
        $appointment_type_id_validation = CommonValidator::isValidId($request_body['appointment_type_id'], 'appointment_type_id');
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
        $appointment = new Appointment($request_body);
        if (!$appointment->save()) {
            $error = Responses::UNKNOWN_ERROR(Messages::DB_WRITE_ERROR());
            Response::json([$error], Responses::UNKNOWN_ERROR()['code']);
        }
        $saved_appointment = Appointment::getByRange($request_body['start'], $request_body['end']);
        if (!$saved_appointment) {
            $error = Responses::UNKNOWN_ERROR(Messages::DB_READ_ERROR());
            Response::json([$error], Responses::UNKNOWN_ERROR()['code']);
        }
        $data = Responses::SUCCESS(Messages::APPOINTMENT_SAVED());
        $data['saved_appointment'] = $saved_appointment->serialize();
        Response::json($data, $data['code']);
        exit;
    }
}