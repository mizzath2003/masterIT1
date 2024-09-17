<?php
session_start();
include('dbdata.php');

if (isset($_POST['EnablestdID'])) {
    $studentID = $conn->real_escape_string($_POST['EnablestdID']);

    $sql = "UPDATE `students` SET `Status`='1' WHERE `ID`='$studentID'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['status'] = "Student updated successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }else{
        $_SESSION['status'] = "Student not updated";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
if (isset($_POST['DisablestdID'])) {
    $studentID = $conn->real_escape_string($_POST['DisablestdID']);

    $sql = "UPDATE `students` SET `Status`='2' WHERE `ID`='$studentID'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['status'] = "Student updated successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }else{
        $_SESSION['status'] = "Student not updated";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}