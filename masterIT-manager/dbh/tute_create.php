<?php
session_start();
include('dbdata.php');

if (isset($_POST['createTute'])) {
    $TuteName = $conn->real_escape_string($_POST['tuteName']);
    $tuteStatus = $conn->real_escape_string($_POST['tuteStatus']);
    $TuteLInk = $conn->real_escape_string($_POST['tuteLink']);

    $sql = "INSERT INTO `tutes`(`Name`, `Link`, `Status`) VALUES ('$TuteName','$TuteLInk','$tuteStatus')";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['status'] = "Tute created successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['status'] = "Tute not created";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
