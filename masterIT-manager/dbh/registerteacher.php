<?php
session_start();
include('dbdata.php');

if (isset($_POST['register_teacher'])) {
    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $mobile = $conn->real_escape_string($_POST['mobile']);    
    $dob = $conn->real_escape_string($_POST['dob']);
    $email = $conn->real_escape_string($_POST['email']);
    $district = $conn->real_escape_string($_POST['district']);
    $address = $conn->real_escape_string($_POST['address']);

    $sql = "INSERT INTO `teachers`(`address`, `date`, `district`, `dob`, `email`, `fname`, `lname`, `mobile`) VALUES ('$address','$date_post','$district','$dob','$email','$fname',' $lname','$mobile')";
    $result = $conn->query($sql);

    if ($result == TRUE) {
        $_SESSION['status'] = "Teacher registered successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }else{
        $_SESSION['status'] = "Teacher not registered";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
