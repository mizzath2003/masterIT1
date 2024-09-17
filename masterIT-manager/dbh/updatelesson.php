<?php
session_start();
include('dbdata.php');

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

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
function updateZoomMeeting($meeting_id, $mtopic, $mtime, $mduration)
{
    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'https://api.zoom.us',
    ]);

    $response = $client->request('PATCH', '/v2/meetings/' . $meeting_id, [
        "headers" => [
            "Authorization" => "Bearer " . getZoomAccessToken()
        ],
        'json' => [
            "topic" => $mtopic,
            "type" => 2,
            "start_time" => $mtime . ":00",
            "duration" => $mduration,
            "default_password" => true,
            "settings" => [
                "approval_type" => 1,
                "registration_type" => 1,
                "mute_upon_entry" => true,
                "allow_multiple_devices" => false,
                "registrants_email_notification" => false,
                "registrants_confirmation_email" => false,
                "waiting_room" => false,
            ],
        ],
    ]);
}

if (isset($_POST['updateLesson'])) {
    $LessonMeetingID = $conn->real_escape_string($_POST['updateLesson']);
    $lessonStatus = $conn->real_escape_string($_POST['lessonStatus']);
    $LessonTute = $conn->real_escape_string($_POST['LessonTute']);
    $LessonName = $conn->real_escape_string($_POST['LessonName']);
    $LessonTLessonStartTime = $conn->real_escape_string($_POST['LessonStartTime']);
    $LessonDuration = $conn->real_escape_string($_POST['LessonDuration']);
    $LessonPrice = $conn->real_escape_string($_POST['LessonPrice']);
    $LessonRecordingLink = $conn->real_escape_string($_POST['LessonRecordingLink']);
    $uniqueToken = uniqid();

    updateZoomMeeting($LessonMeetingID, $LessonName, $LessonTLessonStartTime, $LessonDuration);

    if (!file_exists($_FILES['fileToUpload']['tmp_name'])) {
        $sql = "UPDATE `lessons` SET `Name`='$LessonName',`StartTime`='$LessonTLessonStartTime',`Duration`='$LessonDuration',`Price`='$LessonPrice',`TuteID`='$LessonTute',
        `RecordingLink`='$LessonRecordingLink',`status`='$lessonStatus' WHERE `MeetingID`='$LessonMeetingID'";
        $result = $conn->query($sql);

        if ($result == True) {
            $_SESSION['status'] = "Lesson updated successfully";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $_SESSION['status'] = "Lesson not updated";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    } else {
        $uploadOk = 1;
        $target_dir = "../images/lessons/";
        $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
        $target_file_compressed = $target_dir . $uniqueToken . 'compressed.'  . $imageFileType;
        $compressed_image = compressImage($_FILES["fileToUpload"]["tmp_name"], $target_file_compressed, 50); // Adjust the quality as needed (50 is just an example)

        if (!$compressed_image) {
            $_SESSION['status'] = "Error compressing the image. ";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        } else {
            $imgName = "images/lessons/" . $uniqueToken . 'compressed.' . $imageFileType;
        }

        $sql = "UPDATE `lessons` SET `Name`='$LessonName',`StartTime`='$LessonTLessonStartTime',`Duration`='$LessonDuration',`Price`='$LessonPrice',`TuteID`='$LessonTute',
        `RecordingLink`='$LessonRecordingLink',`status`='$lessonStatus', `ImgLink`='$imgName' WHERE `MeetingID`='$LessonMeetingID'";
        $result = $conn->query($sql);

        if ($result == True) {
            $_SESSION['status'] = "Lesson updated successfully";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $_SESSION['status'] = "Lesson not updated";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}
