<?php
session_start();
include('dbdata.php');
requireLogin("yes", "../login");
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
    'address'
];
foreach ($fields as $field) {
    if (isset($_POST[$field]) && !empty($_POST[$field])) {
        ${$field} = $con->real_escape_string($_POST[$field]);
    } else {
        $_SESSION['error'] = "Please fill all fields.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }
}

$email = strtolower($con->real_escape_string($_SESSION['email']));
$password = sha1($con->real_escape_string($_POST['password']) . $email);

$sql = "UPDATE `students` SET `Fname` = '$fname', `Lname` = '$lname', `Batch` = '$olbatch', `DOB` = '$dob', `Gender` = '$gender', 
`School` = '$school', `Mobile` = '$mobile', `PMobile` = '$pmobile', `District` = '$district', `Address` = '$address',`PWD`='$password' WHERE `Email` = '$email'";
$result = $con->query($sql);

if ($result == TRUE) {
    $_SESSION['success'] = "Profile updated successfully.";
    header("Location:./profile");
} else {
    $_SESSION['error'] = "Oh no! Profile update failed." . mysqli_error($con);
    header("Location:./profile/edit");
}
$con->close();
