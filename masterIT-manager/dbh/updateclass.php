<?php
session_start();
include('dbdata.php');

function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

if (isset($_POST['update_class'])) {
    $className = $conn->real_escape_string($_POST['className']);
    $classDiscount = $conn->real_escape_string($_POST['classDiscount']);
    $classCurriculam = $conn->real_escape_string($_POST['classCurriculam']);
    $classDiscountCount = $conn->real_escape_string($_POST['classDiscountCount']);
    $classColour = $conn->real_escape_string($_POST['classColour']);
    $classannouncement = $conn->real_escape_string($_POST['announcement']);
    $classStatus = $conn->real_escape_string($_POST['classStatus']);
    $update_class = $conn->real_escape_string($_POST['update_class']);
    $uniqueToken = uniqid();

    if (!file_exists($_FILES['fileToUpload']['tmp_name'])) {
        $sql = "UPDATE `classes` SET `Status`='$classStatus',`Name`='$className',`announcement`='$classannouncement',`discount`='$classDiscount',`curriculam`='$classCurriculam',`discountCount`='$classDiscountCount',`colour`='$classColour'  WHERE `ID`='$update_class'";
        $result = $conn->query($sql);

        if ($result == True) {
            $_SESSION['status'] = "Class updated successfully";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $_SESSION['status'] = "Class not updated";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    } else {
        $uploadOk = 1;
        $target_dir = "../images/classes/";
        $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
        $target_file_compressed = $target_dir . $uniqueToken . 'compressed.'  . $imageFileType;
        $compressed_image = compressImage($_FILES["fileToUpload"]["tmp_name"], $target_file_compressed, 50); // Adjust the quality as needed (50 is just an example)

        if (!$compressed_image) {
            $_SESSION['status'] = "Error compressing the image. ";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            die();
        } else {
            $imgName = "images/classes/" . $uniqueToken . 'compressed.' . $imageFileType;
        }


        $sql = "UPDATE `classes` SET `Status`='$classStatus',`Name`='$className',`Image`='$imgName',`announcement`='$classannouncement',`discount`='$classDiscount',`curriculam`='$classCurriculam',`discountCount`='$classDiscountCount',`colour`='$classColour' WHERE `ID`='$update_class'";
        $result = $conn->query($sql);

        if ($result == True) {
            $_SESSION['status'] = "Class updated successfully";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $_SESSION['status'] = "Class not updated";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}
