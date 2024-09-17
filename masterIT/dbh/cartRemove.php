<?php
session_start();
include('dbdata.php');
requireLogin("yes", "../login");
if (!isset($_POST['delFromCart'])) {
    header("Location:../login");
    die();
}

$id = $con->real_escape_string($_POST['delFromCart']);

if (isset($_POST['delFromCart'])) {
    foreach ($_SESSION['cart'] as $keys => $values) {
        if ($values['lessonID'] == $id) {
            unset($_SESSION['cart'][$keys]);
            echo "Success";
        }
    }
}
