<?php
session_start();
include('dbdata.php');
requireLogin("no", "../");

$email = strtolower($con->real_escape_string($_POST['email']));
$password = sha1($con->real_escape_string($_POST['password']) . $email);

$sql = "SELECT `Email`, `PWD`, `Status` FROM `students` WHERE `Email`='$email'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if ($row['PWD'] == $password) {
        if ($row['Status'] == "1") {
            $encodedEmail = base64_encode($email);
            setcookie("email", $encodedEmail, time() + (86400 * 30 * 365), "/");
            $_SESSION['email'] = $email;
            header("Location:../");
        } else if ($row['Status'] === "2") {
            $_SESSION['error'] = "Account Disabled. Contact admin for support.";
            header("Location:../login");
        } else if ($row['Status'] === "0") {
            $_SESSION['error'] = "Account not verified. Please verify by the email verification sent to $email";
            header("Location:../login");
        }
    } else {
        $_SESSION['error'] = "Incorrect password";
        header("Location:../login");
    }
} else {
    $_SESSION['error'] = "Account not found. Please register.";
    header("Location:../register");
    die();
}
$con->close();
