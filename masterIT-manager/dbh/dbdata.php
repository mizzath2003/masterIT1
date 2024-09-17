<?php
$pagePathName = basename($_SERVER['PHP_SELF'], '.php');
if (($pagePathName == 'loginuser') or ($pagePathName == 'login') or ($pagePathName == 'zoom_start')) {
    $null = "null";
} else {
    if (!isset($_SESSION['admin'])) {
        if (isset($_COOKIE['admin'])) {
            $_SESSION['admin'] = base64_decode($_COOKIE['admin']);
            $_SESSION['name'] = base64_decode($_COOKIE['name']);
        } else {
            header('Location:login');
            die();
        }
    }
}
date_default_timezone_set("Asia/Colombo");
$date_post = date("Y-m-d H:i:s");
$dateTodayCurrent = date("Y-m-d\TH:i");
$day = date('D');
// $websiteURLFull = "https://manager.masterit.lk/";
// $clientWebsiteURLFull = "https://masterit.lk/";
$websiteURLFull = "http://localhost/masterIT-manager/";
$clientWebsiteURLFull = "http://localhost/masterIT/";

//Telegram API Key
$TelegramApiToken = "";

//SMS API Info
$smsUsername = "";
$smsPassword = "";
$smsSourceAddress = "";
$SMSURLMessageKey = "";

//Server Credintials
// $dbservername = "";
// $dbusername = "";
// $dbpassword = "";
// $dbname = "";

//Server Credintials
$dbservername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "masterit";

// Create connection
$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//ZOOM credintials
define('ZOOM_API_ACCOUNT_ID', "");
define('ZOOM_API_CLIENT_ID', "");
define('ZOOM_API_CLIENT_SECRET', "");
