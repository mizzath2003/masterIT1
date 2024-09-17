<?php
session_start();
if (!isset($_POST['email'])) {
    header("Location:../login");
    die();
}

include('dbdata.php');

$e = strtolower($conn->real_escape_string($_POST['email']));
$p = sha1($conn->real_escape_string($_POST['pwd']) . $e);

$sql = "SELECT `Status`,`Fname`,`Lname` FROM `students` WHERE Email='$e' AND `Admin`='1'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['Status'] == '1') {
            $sql2 = "SELECT * FROM `students` WHERE Email='$e' AND `PWD`='$p' AND `Status`='1'";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {
                $encodedName = base64_encode($row['Fname'] . " " . $row['Lname']);
                $encodedEmail = base64_encode($e);
                setcookie("name", $encodedName, time() + (86400 * 30 * 365), "/");
                setcookie("admin", $encodedEmail, time() + (86400 * 30 * 365), "/");
                $_SESSION['admin'] = $e;
                $_SESSION['name'] = $row['Fname'] . " " . $row['Lname'];
                $_SESSION['status'] = "Login Successful!";
                header("Location:../");
            } else {
                $_SESSION['status'] = "Incorrect password";
                header("Location:../login");
            }
        } elseif ($row['Status'] == '0') {
            $_SESSION['status'] = "Account not verified. Please verify by the email verification sent to $e";
            header("Location:../login");
        } elseif ($row['Status'] == '2') {
            $_SESSION['status'] = "Account deactivated. Contact admin to enable account.";
            header("Location:../login");
        }
    }
} else {
    $_SESSION['status'] = "Account not found";
    header("Location:/signup");
}
$conn->close();
