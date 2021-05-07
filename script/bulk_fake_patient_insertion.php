<?php

/**
 * @author M. Cem Onan
 * - Multiple fake patient data can be generated and inserted into db by using this script.
 * - Usage:
 *   $ php <script_name>.php <number_of_patients>
 */

namespace script;

use app\model\Patient;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

function fetchName()
{
    $ch = curl_init('https://api.namefake.com/turkish-turkey');

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);

    $response = curl_exec($ch);
    if (curl_errno($ch) != 0) {
        curl_close($ch);
        echo "Curl failed to send request.\n";
        $payload = null;
        return false;
    }
    
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (!($code >= 200 && $code < 300)) {
        curl_close($ch);
        echo "Request failed with code: $code \n";
        $payload = null;
        return false;        
    }

    $code = null;

    try {
        $response = json_decode($response, true);
        preg_match('/^(?P<first_name>.*)(?P<last_name> [^ ]+)$/', $response['name'], $matches_name);
        preg_match_all('/(?P<digits>\d)/', $response['phone_w'], $matches_phone_w);
        $pn = '';
        if (isset($matches_phone_w['digits'])) {
            foreach ($matches_phone_w['digits'] as $digit) {
                $pn .= $digit;
            }
        }
        preg_match('/^\+?9?(?P<phone>0\d+)$/', $pn, $matches_phone);
        $payload = [];
        $payload['first_name'] = $matches_name['first_name'];
        $payload['last_name'] = $matches_name['last_name'];
        $payload['phone'] = $matches_phone['phone'];
        $response = null;
        $matches_name = null;
        $matches_phone = null;
        $matches_phone_w = null;
    } catch (\Throwable $th) {
        $msg = $th->getMessage();
        echo "Failed to parse response: $msg\n";
        $msg = null;
        curl_close($ch);
        $payload = null;
        return false;
    }
    if (!preg_match('/\w/', $payload['first_name'])) {
        echo "Illegal first name";
        curl_close($ch);
        $payload = null;
        return false;
    }
    if (!preg_match('/\w/', $payload['last_name'])) {
        echo "Illegal first name";
        curl_close($ch);
        $payload = null;
        return false;
    }
    if (!preg_match('/^\d{11}$/', $payload['phone'])) {
        echo "Illegal phone number";
        curl_close($ch);
        $payload = null;
        return false;
    }
    $tckn = '' . rand(1, 9);
    for ($i = 0; $i < 10; $i++) {
        $tckn .= rand(0, 9);
    }
    $payload['tckn'] = $tckn;
    $tckn = null;
    echo "insertion:\n";
    echo 'first name: ' . $payload['first_name'] . ', last name: ' . $payload['last_name'] . ', phone: ' . $payload['phone'] . ', tckn: ' . $payload['tckn'] . "\n";
    return $payload;    
}

if ($argc != 2) {
    echo "You must specify the number of insertions as cli argument. \n";
    exit;
}

if (!preg_match('/^\d+$/', $argv[1])) {
    echo "Argument must be an integer. \n";
    exit;
}

$i = 0;
try {
    while ($i < (int)$argv[1]) { 
        $current = $i + 1;
        $upper = $argv[1];
        echo "$current/$upper. ";
        $current = null;
        $upper = null;
        $args = fetchName();
        if ($args === false) {
            continue;
        }
        $patient = new Patient($args);
        $result = $patient->save();
        if ($result === false) {
            echo "An error occurred when inserting data to db.\n";
        } else {
            $result = null;
            echo "Insert successful.\n";
        }
        $i++;
    }
} catch (\Throwable $th) {
    echo "An error occurred when processing $i. iteration. Skipping to next.";
}