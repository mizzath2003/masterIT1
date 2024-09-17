<?php
session_start();
include('dbdata.php');

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

(isset($_POST['ApprovePaymentID'])) ? $paymentID = $conn->real_escape_string($_POST['ApprovePaymentID']) : "";
(isset($_POST['RejectPaymentID'])) ? $paymentID = $conn->real_escape_string($_POST['RejectPaymentID']) : "";
$orderID = $paymentID;
$paymentOrderlessons = "";
$sql2 = "SELECT l.ClassID, COUNT(*) AS count FROM order_lesson ol JOIN lessons l ON ol.LessonID = l.ID WHERE ol.orderID = '$paymentID' GROUP BY l.ClassID";
$result2 = $conn->query($sql2);
if ($result2->num_rows > 0) {
    while ($row2 = $result2->fetch_assoc()) {
        $classID = $row2['ClassID'];
        $occurrence = $row2['count'];
        $resultClass = $conn->query("SELECT `Name`, `Discount`, `discountCount` FROM `classes` WHERE `ID`='$classID'");

        if ($resultClass->num_rows > 0) {
            $classData = $resultClass->fetch_assoc();
            $className = $classData['Name'];
            $discount = $classData['Discount'];
            $discountCount = $classData['discountCount'];
            $sql3 = "SELECT `LessonID` FROM `order_lesson` ol WHERE `orderID` = '$orderID'";
            $result3 = $conn->query($sql3);

            if ($result3->num_rows > 0) {
                while ($row3 = $result3->fetch_assoc()) {
                    $lessonID = $row3['LessonID'];
                    $sql4 = "SELECT `Name`, `Price` FROM `lessons` WHERE `ID`='$lessonID'";
                    $result4 = $conn->query($sql4);

                    if ($result4->num_rows > 0) {
                        while ($row4 = $result4->fetch_assoc()) {
                            $lessonName = $row4['Name'];
                            $listItemName = $className . " - " . $lessonName;
                            $lessonPrice = $row4['Price'];
                            $divNumL = (int)($occurrence / $discountCount);

                            if ($divNumL >= 1) {
                                $lessonsToDiscount = $discountCount * $divNumL;
                                $counter = $lessonsToDiscount;

                                if ($counter > 0) {
                                    $discountedPrice = $lessonPrice - $discount;
                                    $counter--;
                                    $paymentOrderlessons .= "$listItemName - $discountedPrice\n";
                                } else {
                                    $paymentOrderlessons .= "$listItemName - $lessonPrice\n";
                                }
                            } else {
                                $paymentOrderlessons .= "$listItemName - $lessonPrice\n";
                            }
                        }
                    }
                }
            }
        }
    }
}
$phoneNumbers = "";
$i = 01;
$lessonsApproved = "";

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
    global $SMSURLMessageKey, $smsSourceAddress;
    $smsSend = file_get_contents("https://e-sms.dialog.lk/api/v1/message-via-url/create/url-campaign?esmsqk=$SMSURLMessageKey&source_address=" . urlencode($smsSourceAddress) . "&message=" . urlencode($message) . "&list=$phone");
    echo $smsSend;
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

function registerZoomMeeting($meeting_id, $userEmail, $userFname, $userLName, $LessonID)
{
    global $conn, $paymentID;
    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'https://api.zoom.us',
    ]);

    $response = $client->request('POST', "/v2/meetings/$meeting_id/registrants", [
        "headers" => [
            "Authorization" => "Bearer " . getZoomAccessToken()
        ],
        'json' => [
            "email" => "$userEmail",
            "first_name" => "$userFname",
            "last_name" => "$userLName",
            "auto_approve" => "true"
        ],
    ]);
    if (201 == $response->getStatusCode()) {
        $data = json_decode($response->getBody());
        $j_url = $data->join_url;
        $updsql = "UPDATE `order_lesson` SET `JoinUrl`='$j_url',`Status`='2' WHERE `orderID`='$paymentID' AND `LessonID`='$LessonID'";
        $updsql_run = $conn->query($updsql);
        if ($updsql_run == true) {
            $qwerty = "";
        }
    }
}
if (isset($_POST['ApprovePaymentID'])) {

    $sql = "UPDATE `orders` SET `Status`='2' WHERE `ID`='$paymentID'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $sql2 = "SELECT * FROM `order_lesson` RIGHT JOIN `lessons` ON order_lesson.LessonID = lessons.ID WHERE order_lesson.orderID='$paymentID' AND order_lesson.Status='1'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $LessonID = $row2['LessonID'];
                $UserID = $row2['UserID'];
                $mid = $row2['MeetingID'];


                $sqlStudent = "SELECT `Fname`,`Lname`,`Email`,`Mobile` FROM `students` WHERE `ID`='$UserID'";
                $resultStudent = $conn->query($sqlStudent);
                if ($resultStudent->num_rows > 0) {
                    while ($rowStudent = $resultStudent->fetch_assoc()) {
                        $userEmail = $rowStudent['Email'];
                        $userFname = $rowStudent['Fname'];
                        $userLName = $rowStudent['Lname'];
                        $studentmobile = substr($rowStudent['Mobile'], -9);
                        $phoneNumbers .= $studentmobile . ",";
                    }
                }

                registerZoomMeeting($mid, $userEmail, $userFname, $userLName, $LessonID);
            }
            $lessonsApproved .= str_pad($i++, 2, "0", STR_PAD_LEFT) . ". " . $paymentOrderlessons . "\n";
        }
        $smsMessageToSend = "Dear $userFname $userLName, \nYour payment is approved.\n\nLessons purchased:\n$paymentOrderlessons\nFor any inquiries drop a WhatsApp message to 0772779798\n#TeamMasterIT";
        createSMScapaign($smsMessageToSend, rtrim($phoneNumbers, ","));
        $_SESSION['status'] = "Payment $paymentID approved successfully";
    } else {
        $_SESSION['status'] = "Payment $paymentID  not approved";
    }
}

if (isset($_POST['RejectPaymentID'])) {

    $sql = "UPDATE `orders` SET `Status`='3' WHERE `ID`='$paymentID'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $sql2 = "UPDATE `order_lesson` SET `Status`='3' WHERE `orderID`='$paymentID' AND `Status`='1'";
        $result2 = $conn->query($sql2);
        if ($result2 == true) {
            $sql3 = "SELECT * FROM `order_lesson` RIGHT JOIN `lessons` ON order_lesson.LessonID = lessons.ID WHERE order_lesson.orderID='$paymentID' AND order_lesson.Status='3'";
            $result3 = $conn->query($sql3);
            if ($result3->num_rows > 0) {
                while ($row3 = $result3->fetch_assoc()) {
                    $LessonID = $row3['LessonID'];
                    $UserID = $row3['UserID'];
                    $mid = $row3['MeetingID'];


                    $sqlStudent = "SELECT `Fname`,`Lname`,`Email`,`Mobile` FROM `students` WHERE `ID`='$UserID'";
                    $resultStudent = $conn->query($sqlStudent);
                    if ($resultStudent->num_rows > 0) {
                        while ($rowStudent = $resultStudent->fetch_assoc()) {
                            $userEmail = $rowStudent['Email'];
                            $userFname = $rowStudent['Fname'];
                            $userLName = $rowStudent['Lname'];
                            $studentmobile = substr($rowStudent['Mobile'], -9);
                            $phoneNumbers .= $studentmobile . ",";
                        }
                    }
                }
            }
            $smsMessageToSend = "Dear $userFname $userLName, \nYour payment is rejected.\n\nLessons rejected:\n$paymentOrderlessons\nFor any inquiries drop a WhatsApp message to 0772779798\n#TeamMasterIT";
            createSMScapaign($smsMessageToSend, rtrim($phoneNumbers, ","));
            echo rtrim($phoneNumbers, ",");
        }
        $_SESSION['status'] = "Payment $paymentID rejected successfully";
    } else {
        $_SESSION['status'] = "Payment $paymentID  not rejected";
    }
}
$conn->close();
