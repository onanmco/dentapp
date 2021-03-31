<?php

namespace app\utility;

use app\constant\Constants;
use app\constant\Fields;
use app\constant\Messages;
use app\constant\Responses;
use config\Config;
use core\Response;

class Mail
{
    private $errors = [];
    private $to = [];
    private $from = '';
    private $subject = '';
    private $content = '';
    private $cc = [];
    private $bcc = [];

    public function __construct($args)
    {
        $class_vars = get_class_vars(get_called_class());
        foreach ($args as $key => $value) {
            if ($key === 'to' && is_string($value)) {
                $this->to = [$value];
                continue;
            }
            if ($key === 'cc' && is_string($value)) {
                $this->cc = [$value];
                continue;
            }
            if ($key === 'bcc' && is_string($value)) {
                $this->bcc = [$value];
                continue;
            }
            if (isset($class_vars[$key])) {
                $this->$key = $value;
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function validate()
    {
        $this->errors = [];

        if (empty($this->to)) {
            $this->errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Constants::RECIPIENT));
        }
        
        if (empty($this->from)) {
            $this->errors[] = Responses::MISSING_FIELD(Messages::MISSING_FIELD(Constants::SENDER));
        }

        if (!empty($this->errors)) {
            return false;
        }

        foreach ($this->to as $index => $email) {
            $validation_result = CommonValidator::isValidEmail($email, $index . '. ' . Constants::RECIPIENT);
            if ($validation_result !== true) {
                foreach ($validation_result as $error_msg) {
                    $this->errors[] = Responses::VALIDATION_ERROR($error_msg);
                }
            }
        }

        foreach ($this->cc as $index => $email) {
            $validation_result = CommonValidator::isValidEmail($email, $index . '. ' . Constants::CC);
            if ($validation_result !== true) {
                foreach ($validation_result as $error_msg) {
                    $this->errors[] = Responses::VALIDATION_ERROR($error_msg);
                }
            }
        }

        foreach ($this->bcc as $index => $email) {
            $validation_result = CommonValidator::isValidEmail($email, $index . '. ' . Constants::BCC);
            if ($validation_result !== true) {
                foreach ($validation_result as $error_msg) {
                    $this->errors[] = Responses::VALIDATION_ERROR($error_msg);
                }
            }
        }

        $from_validation_result = CommonValidator::isValidEmail($this->from, Constants::SENDER);
        if ($from_validation_result !== true) {
            foreach ($from_validation_result as $error_msg) {
                $this->errors[] = Responses::VALIDATION_ERROR($error_msg);
            }
        }

        return empty($this->errors) ? true : false;
    }

    public function send()
    {
        $this->errors = [];

        $url = Config::MAILGUN_BASE_URL . '/messages';
        $api_key = Config::MAILGUN_API_KEY;

        $content_identifier = CommonValidator::isHTML($this->content) ? 'html' : 'text';
        
        $payload = [
            'from'              => $this->from,
            'to'                => implode(",", $this->to),
            'subject'           => $this->subject,
            $content_identifier => $this->content
        ];

        if (!empty($this->cc)) {
            $payload['cc'] = implode(", ", $this->cc);
        }

        if (!empty($this->bcc)) {
            $payload['bcc'] = implode(", ", $this->bcc);
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            $this->errors = [Responses::SERVICE_UNAVAILABLE(Messages::SERVICE_UNAVAILABLE(Constants::MAILGUN_SERVICE_NAME))];
        }
        if ($response !== false && $status_code !== 200) {
            $this->errors = [Responses::UNKNOWN_ERROR(Messages::UNKNOWN_ERROR())];
        }
        if ($status_code === 200) {
            $this->errors = [];
        }

        return empty($this->errors) ? true : false;
    }
}