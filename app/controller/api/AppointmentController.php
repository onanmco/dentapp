<?php

namespace app\controller\api;

use app\constant\Fields;
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
        $required_fields = [
            'user_id' => Fields::USER_ID(),
            'patient_id' => Fields::PATIENT_ID(),
            'start' => Fields::START(),
            'end' => Fields::END(),
            'notes' => Fields::NOTES(),
            'notify' => Fields::NOTIFY(),
            'appointment_type_id' => Fields::APPOINTMENT_TYPE_ID()
        ];
        foreach (array_keys($required_fields) as $key) {
            if (!isset($request_body[$key])) {
                $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD($required_fields[$key]));
            }
        }
        $invalid_keys = array_diff(array_keys($request_body), array_keys($required_fields));
        foreach ($invalid_keys as $v) {
            $errors[] = Responses::INVALID_FIELD(Messages::INVALID_FIELD($v));
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
        $patient_id_validation = CommonValidator::isValidId($request_body['patient_id'], Fields::PATIENT_ID());
        if ($patient_id_validation !== true) {
            foreach ($patient_id_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        if (!Patient::findById($request_body['patient_id'])) {
            $patient_id = $request_body['patient_id'];
            $errors[] = Responses::VALIDATION_ERROR(Messages::PATIENT_ID_CANNOT_FOUND($patient_id));
        }

        $start_validation = CommonValidator::isValidUnixTimestamp($request_body['start'], Fields::START());
        if ($start_validation !== true) {
            foreach ($start_validation as $error_msg) {
                $errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }
        $end_validation = CommonValidator::isValidUnixTimestamp($request_body['end'], Fields::END());
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
            $errors[] = Responses::VALIDATION_ERROR(Messages::SHOULD_BE_STRING(Fields::NOTES()));
        }
        if (!is_bool($request_body['notify'])) {
            $errors[] = Responses::VALIDATION_ERROR(Messages::SHOULD_BE_BOOLEAN(Fields::NOTIFY()));
        }
        $appointment_type_id_validation = CommonValidator::isValidId($request_body['appointment_type_id'], Fields::APPOINTMENT_TYPE_ID());
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
        $data = Responses::OK(Messages::APPOINTMENT_SAVED());
        $data['saved_appointment'] = $saved_appointment->serialize();
        $patient = Patient::findById($data['saved_appointment']['patient_id']);
        $data['saved_appointment']['title'] = $patient->getFullName();
        Response::json($data, $data['code']);
        exit;
    }

    public function getAllByRangeAction()
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

        $required_fields = [
            'start' => Fields::START(),
            'end' => Fields::END()
        ];

        foreach (array_keys($required_fields) as $key) {
            if (!isset($request_body[$key])) {
                $errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD($required_fields[$key]));
            }
        }

        $invalid_keys = array_diff(array_keys($request_body), array_keys($required_fields));

        foreach ($invalid_keys as $v) {
            $errors[] = Responses::INVALID_FIELD(Messages::INVALID_FIELD($v));
        }

        if (!empty($errors)) {
            Response::json($errors, Responses::MISSING_FIELD()['code']);
            exit;
        }

        $start_validation = CommonValidator::isValidUnixTimestamp($request_body['start'], Fields::START());
        
        if ($start_validation !== true) {
            foreach ($start_validation as $error_msg) {
                $errors[] = $error_msg;
            }
        }
        
        $end_validation = CommonValidator::isValidUnixTimestamp($request_body['end'], Fields::END());
        
        if ($start_validation !== true) {
            foreach ($start_validation as $error_msg) {
                $errors[] = $error_msg;
            }
        }

        if (!empty($errors)) {
            Response::json($errors, Responses::VALIDATION_ERROR()['code']);
            exit;
        }


        $start = date('Y-m-d H:i:s', $request_body['start']);
        $end = date('Y-m-d H:i:s', $request_body['end']);
        
        $all_appointments = Appointment::getAllBetween($start, $end);

        $serialized_list = [];

        foreach ($all_appointments as $appointment) {
            $appointment_arr = $appointment->serialize();
            $patient = Patient::findById($appointment_arr['patient_id']);
            $appointment_arr['title'] = $patient->getFullName();
            $appointment_arr['start'] = strtotime($appointment_arr['start']) * 1000;
            $appointment_arr['end'] = strtotime($appointment_arr['end']) * 1000;
            $serialized_list[] = $appointment_arr;
        }

        $payload = Responses::OK(Messages::APPOINTMENTS_FETCHED());
        $payload['appointment_list'] = $serialized_list;
        Response::json($payload, $payload['code']);

    }
}