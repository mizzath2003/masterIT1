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
function createZoomMeeting($mtopic, $mtime, $mduration)
{
    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'https://api.zoom.us',
    ]);

    $response = $client->request('POST', "/v2/users/me/meetings", [
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
                "host_video" => false,
                "participant_video" => false,
                "focus_mode" => true,
            ],
        ],
    ]);

    $data = json_decode($response->getBody());
    $GLOBALS['meetingID'] = $data->id;
    $GLOBALS['meetingPassword'] = $data->password;
}
if (isset($_POST['createLesson'])) {
    $classID = $conn->real_escape_string($_POST['createLesson']);
    $className = $conn->real_escape_string($_POST['className']);
    $lessonStatus = $conn->real_escape_string($_POST['lessonStatus']);
    $LessonTute = $conn->real_escape_string($_POST['LessonTute']);
    $LessonName = $conn->real_escape_string($_POST['LessonName']);
    $LessonTLessonStartTime = $conn->real_escape_string($_POST['LessonStartTime']);
    $LessonDuration = $conn->real_escape_string($_POST['LessonDuration']);
    $LessonPrice = $conn->real_escape_string($_POST['LessonPrice']);
    $uniqueToken = uniqid();

    $uploadOk = 1;
    $target_dir = "../images/lessons/";
    $target_file = $target_dir . $uniqueToken . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 10000000) {
        $uploadOk = 0;
        $_SESSION['status'] = "Thumbnail file size is too large. Please compress and try uploading it";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "pdf"
    ) {
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $imgName = "images/lessons/" . $uniqueToken . basename($_FILES["fileToUpload"]["name"]);
        } else {
            $_SESSION['status'] = "Oh no! some error occured when uploading the thumbnail. Please contact admin for support.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
    createZoomMeeting(($className . " - " . $LessonName), $LessonTLessonStartTime, $LessonDuration);
    $sql = "INSERT INTO `lessons`(`MeetingID`, `Passcode`, `ClassID`, `Date_Created`, `Name`, `StartTime`, `Duration`, `Price`, `TuteID`, `RecordingLink`, `Status`, `ImgLink`)
    VALUES ('$meetingID','$meetingPassword','$classID','$date_post','$LessonName','$LessonTLessonStartTime','$LessonDuration','$LessonPrice','$LessonTute','#','$lessonStatus','$imgName')";
    $result = $conn->query($sql);

    if ($result == TRUE) {
        $_SESSION['status'] = "Lesson created successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['status'] = "Lesson not created";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}

if (isset($_POST['bulkschedule'])) {
    $file = fopen($_FILES['lessonScheduleFile']['tmp_name'], 'r');
    $header = fgetcsv($file); // Skip the header row

    $s = $f = 0;

    while (($data = fgetcsv($file)) !== FALSE) {
        $classID = $conn->real_escape_string(trim($data[0]));
        $lessonStatus = 1;
        $LessonTute = "#";
        $LessonName = $conn->real_escape_string(trim($data[1]));
        $LessonTLessonStartTime = $conn->real_escape_string(trim($data[2]));
        $LessonDuration = $conn->real_escape_string(trim($data[3]));
        $LessonPrice = $conn->real_escape_string(trim($data[4]));
        $imgName = "images/lessons/" . trim($data[5]);

        if (($LessonDuration != "") && ($LessonTLessonStartTime != "") && ($LessonName != "")) {
            createZoomMeeting(($className . " - " . $LessonName), $LessonTLessonStartTime, $LessonDuration);
            $sql = "INSERT INTO `lessons`(`MeetingID`, `Passcode`, `ClassID`, `Date_Created`, `Name`, `StartTime`, `Duration`, `Price`, `TuteID`, `RecordingLink`, `Status`, `ImgLink`)
                    VALUES ('$meetingID','$meetingPassword','$classID','$date_post','$LessonName','$LessonTLessonStartTime','$LessonDuration','$LessonPrice','$LessonTute','#','$lessonStatus','$imgName')";
            $result = $conn->query($sql);
            if ($result == TRUE) {
                $s++;
                $_SESSION['status'] = "$s Lesson created successfully";
            } else {
                $f++;
                $_SESSION['status'] = "$f Lesson not created";
            }
        }
    }
    fclose($file);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
