<?php
session_start();
include('dbdata.php');
require_once 'vendor/autoload.php';

use GuzzleHttp\Client;


function getZoomAccessToken()
{
    $account_ID = trim(ZOOM_API_ACCOUNT_ID);
    $client_id = trim(ZOOM_API_CLIENT_ID);
    $client_secret = trim(ZOOM_API_CLIENT_SECRET);

    // Creating base 64 encoded authkey
    $Auth_Key = $client_id . ":" . $client_secret;
    $encoded_Auth_Key = base64_encode($Auth_Key);

    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'https://api.zoom.us',
    ]);

    $response = $client->request('POST', "https://zoom.us/oauth/token?grant_type=account_credentials&account_id=$account_ID", [
        "headers" => [
            "Host" => "zoom.us",
            "Authorization" => "Basic  " . $encoded_Auth_Key
        ],
        'http_errors' => false
    ]);
    $data = json_decode($response->getBody());
    return $data->access_token;
}

function getZoomMeeting($meetingID)
{
    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'https://api.zoom.us',
    ]);

    $response = $client->request('GET', "/v2/meetings/$meetingID", [
        "headers" => [
            "Authorization" => "Bearer " . getZoomAccessToken()
        ] 
    ]);

    $data = json_decode($response->getBody());
    // print_r($data);
    $GLOBALS['start_url'] = $data->start_url;
}
$meetingID = $conn->real_escape_string($_GET['mid']); 
getZoomMeeting($meetingID);
?>
<script type="text/javascript">location.href = '<?=$start_url?>';</script>