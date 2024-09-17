<?php
session_start();
include('dbdata.php');

if (isset($_POST['deleteteTute'])) {
    $DeleteTuteID = $conn->real_escape_string($_POST['deleteteTute']);

    $sql = "DELETE FROM `tutes` WHERE `ID`='$DeleteTuteID'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['status'] = "Tute deleted successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }else{
        $_SESSION['status'] = "Tute not deleted";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}