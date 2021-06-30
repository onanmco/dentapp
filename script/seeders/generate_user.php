<?php

namespace script\seeders;

use app\utility\CommonValidator;
use app\model\Group;
use app\model\User;
use app\model\Language;

require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

$first_name = readline('Enter first name: ');

$name_validation = CommonValidator::isValidName($first_name, 'First name');

if ($name_validation !== true) {
    foreach ($name_validation as $msg) {
        echo "Error: $msg \n";
    }
    exit;
}

$last_name = readline('Enter last name: ');

$name_validation = CommonValidator::isValidName($last_name, 'Last name');

if ($name_validation !== true) {
    foreach ($name_validation as $msg) {
        echo "Error: $msg \n";
    }
    exit;
}

$tckn = readline('Enter TCKN: ');

$tckn_validation = CommonValidator::isValidTckn($tckn, 'TCKN');

if ($tckn_validation !== true) {
    foreach ($tckn_validation as $msg) {
        echo "Error: $msg \n";
    }
    exit;
}

$email = readline('Enter e-mail: ');

$email_validation = CommonValidator::isValidEmail($email, 'E-mail');

if ($email_validation !== true) {
    foreach ($email_validation as $msg) {
        echo "Error: $msg \n";
    }
    exit;
}

$password = readline('Enter password: ');

$password_validation = CommonValidator::isValidPassword($password, 'Password');

if ($password_validation !== true) {
    foreach ($password_validation as $msg) {
        echo "Error: $msg \n";
    }
    exit;
}

if (! User::isEmailAvailable($email)) {
    echo "Error: $email has already taken.";
    exit;
}

try {
    $default_langauge = Language::findByLanguage('en');
    $lang_id = $default_langauge->getId();
} catch (\Throwable $th) {
    echo "An error occurred\n";
    echo $th->getMessage() . "\n";
    exit;
}

$user = new User([
    'first_name' => $first_name,
    'last_name' => $last_name,
    'tckn' => $tckn,
    'email' => $email,
    'password_hash' => password_hash($password, PASSWORD_BCRYPT),
    'group_id' => 3,
    'language_preference' => $lang_id
]);

if ($user->save() === false) {
    echo "An error occurred when inserting user to db.\n";
    exit;
}

echo "Insert successful.\n";