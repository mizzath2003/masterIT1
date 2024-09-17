<?php
session_start();
include('dbdata.php');

if (isset($_POST['updateTute'])) {
    $TuteName = $conn->real_escape_string($_POST['EditTuteName']);
    $tuteStatus = $conn->real_escape_string($_POST['tuteStatus']);
    $TuteLInk = $conn->real_escape_string($_POST['tuteLink']);
    $updateTuteID = $conn->real_escape_string($_POST['updateTute']);

    $sql = "UPDATE `tutes` SET `Name`='$TuteName',`Link`='$TuteLInk',`Status`='$tuteStatus' WHERE `ID`='$updateTuteID'";
    $result = $conn->query($sql);
    if ($result == TRUE) {
        $_SESSION['status'] = "Tute updated successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['status'] = "Tute not updated";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
