<?php

namespace app\constant\en;

class Messages
{
    public static function MIN_LEN($value, $field)
    {
        return "$field field must have at least $value characters.";
    }
    
    public static function MAX_LEN($value, $field)
    {
        return "$field field must have at most $value characters.";
    }

    public static function REGEXP_UPPERCASE($count, $field)
    {
        $character = $count > 1 ? 'characters' : 'character';
        return "$field field must have at least $count uppercase $character.";
    }

    public static function REGEXP_DIGIT($count, $field)
    {
        $digit = $count > 1 ? 'digits' : 'digit';
        return "$field field must have at least $count $digit.";
    }

    public static function PASSWORD_REGEXP($field)
    {
        return "$field field can have only letters, digits, special characters, dash and dots.";
    }

    public static function NAME_REGEXP($field)
    {
        return "$field field can have only letters, space, dot, apostrophe and dashes.";
    }

    public static function PHONE_REGEXP($field)
    {
        return "$field field must have in 0NNNNNNNNN format.";
    }

    public static function TCKN_REGEXP($field)
    {
        return "$field field must consist of 11 digits.";
    }

    public static function ID_REGEXP($field)
    {
        return "$field field must be an integer greater than 0.";
    }

    public static function COMPOSITE_SEARCH_REGEXP($field)
    {
        return "$field field can have only letters, digits, space, dot, apostrophe and dashes.";
    }

    public static function MISSING_FIELD($field)
    {
        return "$field field is missing.";
    }

    public static function SERVICE_UNAVAILABLE($service_name)
    {
        return "$service_name service is not responding. Please try later.";
    }

    public static function MAIL_SENT_SUCCESSFULLY()
    {
        return "Your mail has been sent succesfully.";
    }

    public static function JSON_DECODING_ERROR()
    {
        return "JSON body could not be parsed.";
    }

    public static function APPLICATION_JSON()
    {
        return "Data type must be 'application/json'.";
    }

    public static function POST_METHOD()
    {
        return "Request method must be 'POST'.";
    }

    public static function DB_READ_ERROR()
    {
        return "We cannot process your request. Please contact support.";
    }

    public static function DB_WRITE_ERROR()
    {
        return "We cannot process your request. Please contact support.";
    }

    public static function ACCOUNT_CANNOT_FOUND()
    {
        return "E-mail address cannot be found in records.";
    }

    public static function WRONG_PASSWORD()
    {
        return "Invalid password. Please try again.";
    }

    public static function EMAIL_ALREADY_USED()
    {
        return "E-mail is already using by another user.";
    }

    public static function REGISTER_SUCCESSFUL()
    {
        return "Register successful. Redirecting to homepage.";
    }

    public static function LOGIN_SUCCESSFUL()
    {
        return "Logged in. Redirecting to the last visit page.";
    }

    public static function LOGOUT_SUCCESSFUL()
    {
        return "Logged out. Redirecting to homepage.";
    }

    public static function UNAUTHORIZED_ACCESS()
    {
        return "Unauthorized access. Please log in to see the page.";
    }

    public static function UNKNOWN_ERROR()
    {
        return "We cannot process your request. Please contact support.";
    }

    public static function PATIENT_WITH_TCKN_ALREADY_EXISTS()
    {
        return "This ID is used by another patient.";
    }

    public static function PATIENT_REGISTERED_SUCCESSFULLY()
    {
        return "Patient has been registered.";    
    }

    public static function SEARCH_COMPLETED()
    {
        return "Search has been completed.";
    }

    public static function USER_ID_CANNOT_FOUND($user_id)
    {
        return "There is no registered user with ID of $user_id";
    }

    public static function PATIENT_ID_CANNOT_FOUND($user_id)
    {
        return "There is no registered patient with ID of $user_id";
    }

    public static function INVALID_START_END_HOURS()
    {
        return "Start hour must be earlier than the end hour.";
    }

    public static function APPOINTMENT_TO_PAST()
    {
        return "You cannot set an appointment to the past.";
    }

    public static function OVERLAPPING_APPOINTMENT()
    {
        return "There are overlapping appointments at the period selected.";
    }

    public static function SHOULD_BE_STRING($field)
    {
        return "$field can only be string.";
    }

    public static function SHOULD_BE_BOOLEAN($field)
    {
        return "$field can only be boolean(true/false)";
    }

    public static function APPOINTMENT_SAVED()
    {
        return "Appointment has been scheduled.";
    }

    public static function USER_COULD_NOT_BE_SAVED($email)
    {
        return "An error occurred when user is tried to be registered on database. E-mail: $email";
    }

    public static function FAILED_TO_GET_USER_BY_TOKEN($email, $token)
    {
        return "An error occurred when user is tried to be fetched from database by its token.\nE-mail: $email\nAPI Token: $token";
    }

    public static function USER_GROUP_ID_NOT_EXIST()
    {
        return "Invalid group id.";
    }

    public static function FAILED_TO_INSERT_API_TOKEN_HASH($email, $id)
    {
        return "An error occurred when inserting api_token_hash on users table. Rollback is processed.\nE-mail: $email\nId: $id";
    }

    public static function INVALID_EMAIL($field)
    {
        return "Please provide a valid email adddress for the $field field.";
    }

    public static function CANNOT_BE_EMPTY($field)
    {
        return "$field field cannot be empty.";
    }

    public static function PAGE_NOT_FOUND()
    {
        return "Page not found.";
    }

    public static function INVALID_TCKN_USER_SIGNUP($field)
    {
        return "$field field can have only digits.";
    }

    public static function PLEASE_SELECT_PATIENT()
    {
        return "Please select a patient.";
    }

    public static function SHOULD_BE_INTEGER($field)
    {
        return "$field can have only integer values.";
    }

    public static function SHOULD_BE_NUMERIC($field)
    {
        return "$field can have only numeric values.";
    }

    public static function SHOULD_BE_IN_BETWEEN($min, $max, $field)
    {
        return "$field field can have values between $min and $max.";
    }

    public static function INVALID_IP_ADDR($field)
    {
        return "Please provide a valid IP address for $field field.";
    }

    public static function MISSING_FIELDS($fields = [])
    {
        $str = implode(", ", $fields) . (count($fields) > 1 ? ' fields are' : ' field is');
        return "$str missing.";
    }

    public static function INVALID_FIELD($field)
    {
        return "$field field is invalid.";
    }

    public static function INVALID_CSRF_TOKEN()
    {
        return "Invalid CSRF token.";
    }
}