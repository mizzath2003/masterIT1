<?php
session_start();
include('dbdata.php');
requireLogin("yes", "../login");

if (!isset($_POST['addToCart'])) {
    header("Location:../login");
    die();
}

$id = $con->real_escape_string($_POST['addToCart']);

$sql = "SELECT `ID`,`Name`,`Price`,`StartTime`,`ClassID` FROM `lessons` WHERE `ID`='$id' AND `Status`='1'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lesson = [
        'lessonID' => $row['ID'],
        'ClassID' => $row['ClassID']
    ];
}

if (isset($_SESSION['cart'])) {
    $id_array = array_column($_SESSION['cart'], 'id');

    if (!in_array($id, $id_array)) {
        $item = array_merge(['id' => $id, 'lessonID' => $lesson['lessonID']], $lesson);
        $_SESSION['cart'][] = $item;
        echo "Success";
    } else {
        echo "Failed";
    }
} else {
    $item = array_merge(['id' => $id, 'lessonID' => $lesson['lessonID']], $lesson);
    $_SESSION['cart'][] = $item;
    echo "Success";
}
