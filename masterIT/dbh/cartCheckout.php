<?php
session_start();
include('dbdata.php');
requireLogin("yes", "../login");
if (!isset($_SESSION['cart'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
}
$paymentToken = sha1(uniqid() . $_SESSION['email']) . "-";
$paymentbankName = $con->real_escape_string($_POST['bankName']);;

$uploadOk = 1;
$target_dir = "../assets/images/invoices/";
$target_file_ABC = $target_dir . $paymentToken . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file_ABC, PATHINFO_EXTENSION));
$target_file = $target_dir . $paymentToken . "." . $imageFileType;
// Check if image file is a actual image or fake image
if (isset($_POST["cart"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
}
// Check file size (10MB)
if ($_FILES["fileToUpload"]["size"] > 10000000) {
    $uploadOk = 0;
    $_SESSION['error'] = "Receipt attached is too large. Max file size = 10MB";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
}
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"  && $imageFileType != "pdf") {
    $uploadOk = 0;
    $_SESSION['error'] = "Oh no! Payment receipt invalid. $imageFileType";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
}
if ($uploadOk == 0) {
    $_SESSION['error'] = "Oh no! Payment receipt process error.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $imgName = "assets/images/invoices/" . $paymentToken . "." . $imageFileType;
    } else {
        $_SESSION['error'] = "Oh no! Payment receipt upload failed.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}

$finalTotal = 0;

$classOccurrences = array_count_values(array_column($_SESSION['cart'], 'ClassID'));

foreach ($classOccurrences as $classID => $occurrence) {
    $classData = getClassData($con, $classID);
    if ($classData) {
        $discount = $classData['Discount'];
        $discountCount = $classData['discountCount'];
        $lessonCount = $occurrence;
        $divNumL = (int)($lessonCount / $discountCount);

        foreach ($_SESSION['cart'] as $values) {
            if ($values['ClassID'] == $classID) {
                $lessonDetails = getLessonDetails($con, $values['lessonID']);
                if ($lessonDetails) {
                    $lessonPrice = $lessonDetails['Price'];
                    $lessonStartTime = $lessonDetails['StartTime'];

                    if (new DateTime() < (new DateTime($lessonStartTime))->modify('+5 minutes')) {
                        $finalTotal += calculatePrice($divNumL, $discount, $discountCount, $lessonPrice);
                    }
                }
            }
        }
    }
}

function getClassData($con, $classID)
{
    $resultClass = $con->query("SELECT `Name`, `Discount`, `discountCount` FROM `classes` WHERE `ID`='$classID'");
    if ($resultClass->num_rows > 0) {
        return $resultClass->fetch_assoc();
    }
    return null;
}

function getLessonDetails($con, $lessonID)
{
    $result = $con->query("SELECT `Price`,`StartTime` FROM `lessons` WHERE `Status`='1' AND `ID`='$lessonID'");
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

function calculatePrice($divNumL, $discount, $discountCount, $lessonPrice)
{
    if ($divNumL >= 1) {
        $lessonsToDiscount = $discountCount * $divNumL;
        $counter = $lessonsToDiscount;

        if ($counter > 0) {
            $discountedPrice = $lessonPrice - $discount;
            $counter--;
            return $discountedPrice;
        }
    }
    return $lessonPrice;
}



$sql = "INSERT INTO `orders`(`Date`, `Total`, `PaymentMethod`, `Receipt`, `Status`, `UserID`) 
        VALUES ('$date_post','$finalTotal ','$paymentbankName','$imgName','1','$studentID')";
$result = $con->query($sql);
if ($result == TRUE) {
    $last_id = $con->insert_id;
    foreach ($_SESSION['cart'] as $keys => $values) {
        $lessonInCartLessonID = $values['lessonID'];
        $sql = "INSERT INTO `order_lesson`(`LessonID`, `JoinUrl`, `Status`, `UserID`, `orderID`) 
                VALUES ('$lessonInCartLessonID','#','1','$studentID','$last_id')";
        $result = $con->query($sql);
        if ($result == TRUE) {
            unset($_SESSION['cart'][$keys]);
        }
    }
    $_SESSION['success'] = "Payment uploaded successfully. Please await for admins approval.";
    header('Location:../profile');
} else {
    $_SESSION['error'] = "Payment update failed. Please try again.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
