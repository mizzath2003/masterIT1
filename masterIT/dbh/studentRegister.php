<?php
session_start();
include('dbdata.php');
requireLogin("no", "../");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

$fields = [
    'fname',
    'lname',
    'olbatch',
    'dob',
    'gender',
    'school',
    'mobile',
    'pmobile',
    'district',
    'address',
    'email',
    'password'
];
foreach ($fields as $field) {
    if (isset($_POST[$field]) && !empty($_POST[$field])) {
        ${$field} = $con->real_escape_string($_POST[$field]);
    } else {
        $_SESSION['error'] = "Please fill all fields.";
        header("Location: ../signup");
        die();
    }
}

$email = strtolower($con->real_escape_string($_POST['email']));
$password = sha1($con->real_escape_string($_POST['password']) . $email);
$emailVerificationCode = random_int(100000, 999999);
$mobileVerificationCode = random_int(100000, 999999);


$sql = "SELECT `Email` FROM `students` WHERE `Email`='$email'";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    $_SESSION['error'] = "Account already exist. Please login to continue.";
    header("Location:../login");
    die();
} else {
    $sql1 = "INSERT INTO `students` (`ImgLink`,`Fname`, `Lname`, `Batch`, `DOB`, `Gender`, `School`, `Mobile`, `PMobile`, `District`, `Address`, `Email`, `PWD`, `Status`, `EmailVerificationCode`, `Admin`) 
            VALUES ('assets/images/pfp/$gender.jpg','$fname', '$lname', '$olbatch', '$dob', '$gender', '$school', '$mobile', '$pmobile', '$district', '$address', '$email', '$password', 0, '$emailVerificationCode', 0)";
    $result1 = $con->query($sql1);
    if ($result1 == TRUE) {
        $encodedEmail = base64_encode($email);

        $emailsend = file_get_contents(CLIENT_URL . "dbh/studentVerification?token=$emailVerificationCode");

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $smtpServer; // Your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUsername; // Your SMTP username
            $mail->Password   = $smtpPassword; // Your SMTP password
            $mail->SMTPSecure = 'ssl'; // Use 'tls' or 'ssl'
            $mail->Port       = $smtpPort; // Your SMTP port

            //Recipients
            $mail->setFrom($smtpUsername, $emailname);
            $mail->addAddress($email, $fname . " " . $lname);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification Code | ' . $emailname;
            $mail->Body    = $emailsend;

            $mail->send();
            $_SESSION['success'] = 'Message has been sent';
        } catch (Exception $e) {
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        $messageOTP = "Dear Yousuf,\nyour mobile verification code is $mobileVerificationCode.\n\nFor any inquiries call 0772779798\nmasterit.lk";
        $smsSend = file_get_contents("https://e-sms.dialog.lk/api/v1/message-via-url/create/url-campaign?esmsqk=$SMSURLMessageKey&source_address=" . urlencode($smsSourceAddress) . "&message=" . urlencode($messageOTP) . "&list=$mobile");

        $_SESSION['success'] = "Registration successful. Please click on the email verification code sent to your email.";
        header("Location:../");
    } else {
        $_SESSION['error'] = "Oh no! Registration failed. Please contact admin for support." . mysqli_error($con);
        header("Location:../register");
    }
}
$con->close();
