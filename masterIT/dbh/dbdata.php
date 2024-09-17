<?php
define("CLIENT_URL", "http://localhost/masterIT/");
define("MANAGER_URL", "http://localhost/masterIT-manager/");
date_default_timezone_set("Asia/Colombo");
$date_post = date("Y-m-d H:i:s");

$dbservername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "masterit";

$con = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (!isset($_SESSION['email'])) {
    if (isset($_COOKIE['email'])) {
        $_SESSION['email'] = base64_decode($_COOKIE['email']);;
    }
}
function requireLogin($type, $page = CLIENT_URL . "login")
{
    if ($type == "yes") {
        if (!isset($_SESSION['email'])) {
            header("Location:$page");
            die();
        }
    } else if ($type == "no") {
        if (isset($_SESSION['email'])) {
            header("Location:$page");
            die();
        }
    }
}
if (isset($_SESSION['email'])) {
    $studentEmail = strtolower($con->real_escape_string($_SESSION['email']));

    $sqlStudent = "SELECT `ID`,`Fname`,`Lname`,`ImgLink`,`Status` FROM `students` WHERE `Email`='$studentEmail'";
    $resultStudent = $con->query($sqlStudent);
    if ($resultStudent->num_rows > 0) {
        while ($rowStudent = $resultStudent->fetch_assoc()) {
            $studentID = $rowStudent['ID'];
            $profileImg = $rowStudent['ImgLink'];
            $firstName = $rowStudent['Fname'];
            $lastName = $rowStudent['Lname'];
            if ($rowStudent['Status'] == '2') {
                header("Location:" . CLIENT_URL . "logout'");
            }
        }
    }
}

//PayHere Credintials
$merchant_id = "";
$merchant_secret = "";
$currency = "";

//SMTP Credintials
$smtpServer = "";
$smtpUsername = "";
$smtpPassword = "";
$smtpPort = 465;
$emailname = "";

//SMS API Info
$smsUsername = "";
$smsPassword = "";
$smsSourceAddress = "";
$SMSURLMessageKey = "";
