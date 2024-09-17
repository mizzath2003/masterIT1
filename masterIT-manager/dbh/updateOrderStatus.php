<?php
session_start();
include('dbdata.php');

if (isset($_POST['HaltAccess'])) {
    $orderLessonID = $conn->real_escape_string($_POST['HaltAccess']);

    $sql = "UPDATE `order_lesson` SET `Status`='4' WHERE `ID`='$orderLessonID'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['status'] = "Student access updated successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }else{
        $_SESSION['status'] = "Student access not updated";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
if (isset($_POST['EnableAccess'])) {
    $orderLessonID = $conn->real_escape_string($_POST['EnableAccess']);

    $sql = "UPDATE `order_lesson` SET `Status`='2' WHERE `ID`='$orderLessonID'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['status'] = "Student access updated successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }else{
        $_SESSION['status'] = "Student access not updated";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}