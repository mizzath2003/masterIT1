<?php
session_start();
include('dbdata.php');
require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

$sql = "SELECT COUNT(ID) FROM `campaigns`";
$result = $conn->query($sql);
if ($result == True) {
    while ($row = $result->fetch_row()) {
        $transactionID  = $row[0] + 100;
    }
} else {
    $transactionID  = 100;
}

function getSMSAccessToken()
{
    try {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://e-sms.dialog.lk',
        ]);

        $response = $client->request('POST', "/api/v1/login", [
            "headers" => [
                "Content-Type" => "application/json"
            ],
            'json' => [
                "username" => $GLOBALS['smsUsername'],
                "password" => $GLOBALS['smsPassword']
            ]
        ]);
        $data = json_decode($response->getBody());
        return $data->token;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        // print("<pre>" . print_r($e, true) . "</pre>");
    }
}
function createSMScapaign($message, $phone)
{
    global $transactionID;
    try {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://e-sms.dialog.lk',
        ]);

        $response = $client->request('POST', "/api/v2/sms", [
            "headers" => [
                "Authorization" => "Bearer " . getSMSAccessToken(),
                "Content-Type" => "application/json"
            ],
            'json' => [
                "sourceAddress" => $GLOBALS['smsSourceAddress'],
                "message" => $message,
                "transaction_id" => $transactionID,
                "payment_method" => "0",
                "msisdn" => $phone
            ]
        ]);
        $data = json_decode($response->getBody());
        // print("<pre>" . print_r($data, true) . "</pre>");
        return true;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        // echo $response->getStatusCode();
        // print("<pre>" . print_r($e, true) . "</pre>");
        echo "SMS delivery failed";
    }
}

$campaignName = "Test Campaign 3";
$message = "Final checkup 3";
$phoneNumbers = array();
$sql7 = "SELECT * FROM `campaign_sms`";
$result7 = $conn->query($sql7);
if ($result7->num_rows > 0) {
    while ($row7 = $result7->fetch_assoc()) {
        $phone = $row7['phone'];
        $phoneNumbers[] = array("mobile" => $phone);
    }
}

if (createSMScapaign($message, $phoneNumbers)) {
    $sql2 = "INSERT INTO `campaigns` (`date`, `name`,`message`) VALUES ('$date_post','$campaignName','$message')";
    $result2 = $conn->query($sql2);
    if ($result2 == True) {
        $last_id = $conn->insert_id;
        foreach ($phoneNumbers as $x => $val) {
            $smsmPhone = $val['mobile'];
            $sql3 = "INSERT INTO `campaign_sms`(`cID`, `phone`) VALUES ('$last_id','$smsmPhone')";
            $result3 = $conn->query($sql3);            
        }
    }
}