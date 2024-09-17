<?php
session_start();
include('dbdata.php');

if (isset($_POST['Deletetudent'])) {
    $DeletetudentID = $conn->real_escape_string($_POST['Deletetudent']);

    $sql = "DELETE FROM `students` WHERE `ID`='$DeletetudentID'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['status'] = "Student deleted successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }else{
        $_SESSION['status'] = "Student not deleted";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}