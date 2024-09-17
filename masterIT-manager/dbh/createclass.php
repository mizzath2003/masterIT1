<?php
session_start();
include('dbdata.php');

if (isset($_POST['className'])) {
    $className = $conn->real_escape_string($_POST['className']);
    $classannouncement = $conn->real_escape_string($_POST['announcement']);
    $classStatus = $conn->real_escape_string($_POST['classStatus']);
    $classCurriculam = $conn->real_escape_string($_POST['classCurriculam']);
    $classDiscountCount = $conn->real_escape_string($_POST['classDiscountCount']);
    $classDiscount = $conn->real_escape_string($_POST['classDiscount']);
    $classColour = $conn->real_escape_string($_POST['classColour']);
    $uniqueToken = uniqid();

    $uploadOk = 1;
    $target_dir = "../images/classes/";
    $target_file = $target_dir . $uniqueToken . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 10000000) {
        $uploadOk = 0;
        $_SESSION['status'] = "Thumbnail file size is too large. Please compress and try uploading it";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "pdf"
    ) {
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $imgName = "images/classes/" . $uniqueToken . basename($_FILES["fileToUpload"]["name"]);
        } else {
            $_SESSION['status'] = "Oh no! some error occured when uploading the thumbnail. Please contact admin for support.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    $sql = "INSERT INTO `classes`(`Status`, `Name`, `Image`,`announcement`,`colour`,`discountCount`,`curriculam`,`discount`) VALUES ('$classStatus','$className','$imgName','$classannouncement','$classColour','$classDiscountCount','$classCurriculam',' $classDiscount')";
    $result = $conn->query($sql);

    if ($result == TRUE) {
        $_SESSION['status'] = "Class created successfully";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['status'] = "Class not created";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
