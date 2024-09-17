<?php
session_start();
require "dbh/dbdata.php";
requireLogin("yes");
define("PAGE_TITLE", "Profile");
require "includes/header.php";
include_once "components/navbar.php";
include_once "components/orderLesson.php";

$sectionName = isset($_GET['section']) ? $_GET['section'] : "fees-payments";

$sql5 = "SELECT studentID, SUM(amount) AS total_points FROM points WHERE studentID='$studentID '";
$result5 = $con->query($sql5);
if ($result5->num_rows > 0) {
    while ($row5 = $result5->fetch_assoc()) {
        $totalMasterPoints = $row5['total_points'] + 0;
    }
}
?>

<style>
    .img-cover {
        height: 200px;
        width: 100%;
        object-fit: cover;
        object-position: 50% 20%;
        border-radius: 35px;
    }

    .profile-image {
        margin-top: -40px;
        width: 170px;
        height: 170px;
        object-fit: cover;
        object-position: 50% 50%;
        border-radius: 50%;
        border: 10px solid var(--bgMain);
    }

    .profile-cont .accordion-item,
    .profile-cont .accordion-button {
        background-color: transparent !important;
        color: white;
    }

    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236b6c71'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");

    }

    .profile-cont .accordion-button.collapsed::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236b6c71'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
</style>


<!-- Profile SECTION -->
<section class="main-section container-xl mx-auto my-5 mb-5 " style="object-position: center;">
    <img src="assets/images/cover.jpg" alt="" class="img-cover">
    <div class="d-flex flex wrap flex-column flex-md-row flex-wrap text-center text-md-start mx-md-5">
        <img src="<?= $profileImg ?>" alt="" class="profile-image mx-auto">
        <div class="flex-grow-1 mt-3 ms-3 ">
            <h3><?= $firstName . " " . $lastName ?></h3>
            <p class="text-secondary lh-sm">
                Index No: <?= $studentID + 1000 ?>
                <span class="text-warning fw-normal"><br><i class="ri-copper-coin-line"></i> <?= $totalMasterPoints ?> XP</span>
            </p>
        </div>
        <div class="mt-2 mt-md-5">
            <a href="profile/edit" class="btn-custom btn-custom-outline-white">Edit Profile</a>
        </div>
    </div>
    <div class="profile-cont lessonOverview mx-md-5 px-md-3">
        <nav class="nav nav-pills nav-fill" style="max-width:100%">
            <a href="profile/fees-payments" class="text-center nav-link <?= checkActive($sectionName, "fees-payments") ?>">Payments</a>
            <a href="profile/points-overview" class="text-center nav-link <?= checkActive($sectionName,  "points-overview") ?>">Points</a>
            <a href="profile/exam-results" class="text-center nav-link <?= checkActive($sectionName, "exam-results") ?>">Resuts</a>
        </nav>
        <?php
        if ($sectionName == "fees-payments") {
        ?>
            <!-- FeesPayment Section -->
            <div class="accordion mt-5 mx-md-5" id="accordionExample">
                <?php
                $sql = "SELECT `ID`, `Date`, `Total`, `PaymentMethod`, `Status` FROM `orders` WHERE `UserID`='$studentID' ORDER BY `ID` DESC";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $InvoiceNumber = ($row['PaymentMethod'] == "Commercial") ? "#IB" . str_pad($row['ID'], 4, "0", STR_PAD_LEFT) : "#IC" . str_pad($row['ID'], 4, "0", STR_PAD_LEFT);
                        $status = ($row['Status'] == "1") ? "Pending" : (($row['Status'] == "2") ? "Approved" : "Rejected");
                        $statusColour = ($row['Status'] == "1") ? "bg-yellow-2" : (($row['Status'] == "2") ? "bg-green-2" : "bg-red-2");
                        $orderID = $row['ID'];

                ?>
                        <div class="accordion-item rounded-4 mt-4 border border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold d-flex align-items-center rounded-4 collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne<?= $orderID ?>" aria-expanded="true" aria-controls="collapseOne<?= $orderID ?>">
                                    <p class="mb-0 flex-grow-1"><?= $InvoiceNumber . " - Rs " . number_format($row['Total']) ?>
                                        <br>
                                        <span class="smallFont"><?= $row['Date'] ?></span>
                                    </p>
                                    <h5 class="ms-auto badge p-2 fs-6 <?= $statusColour ?> m-2"><?= $status ?></h5>
                                </button>
                            </h2>
                            <div id="collapseOne<?= $orderID ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body smalltextColor">
                                    <?php
                                    $sql2 = "SELECT l.ClassID, COUNT(*) AS count FROM order_lesson ol JOIN lessons l ON ol.LessonID = l.ID WHERE ol.orderID = '$orderID' GROUP BY l.ClassID";
                                    $result2 = $con->query($sql2);

                                    if ($result2->num_rows > 0) {
                                        while ($row2 = $result2->fetch_assoc()) {
                                            $classID = $row2['ClassID'];
                                            $occurrence = $row2['count'];
                                            $resultClass = $con->query("SELECT `Name`, `Discount`, `discountCount` FROM `classes` WHERE `ID`='$classID'");

                                            if ($resultClass->num_rows > 0) {
                                                $classData = $resultClass->fetch_assoc();
                                                $className = $classData['Name'];
                                                $discount = $classData['Discount'];
                                                $discountCount = $classData['discountCount'];
                                                $sql3 = "SELECT `LessonID` FROM `order_lesson` ol WHERE `orderID` = '$orderID'";
                                                $result3 = $con->query($sql3);

                                                if ($result3->num_rows > 0) {
                                                    while ($row3 = $result3->fetch_assoc()) {
                                                        $lessonID = $row3['LessonID'];
                                                        $sql4 = "SELECT `Name`, `Price` FROM `lessons` WHERE `ID`='$lessonID'";
                                                        $result4 = $con->query($sql4);

                                                        if ($result4->num_rows > 0) {
                                                            while ($row4 = $result4->fetch_assoc()) {
                                                                $lessonName = $row4['Name'];
                                                                $listItemName = $className . " - " . $lessonName;
                                                                $lessonPrice = $row4['Price'];
                                                                $divNumL = (int)($occurrence / $discountCount);

                                                                if ($divNumL >= 1) {
                                                                    $lessonsToDiscount = $discountCount * $divNumL;
                                                                    $counter = $lessonsToDiscount;

                                                                    if ($counter > 0) {
                                                                        $discountedPrice = $lessonPrice - $discount;
                                                                        $counter--;
                                                                        displayOrderLesson($listItemName, $discountedPrice, $lessonPrice);
                                                                    } else {
                                                                        displayOrderLesson($listItemName, $lessonPrice, $lessonPrice);
                                                                    }
                                                                } else {
                                                                    displayOrderLesson($listItemName, $lessonPrice, $lessonPrice);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <!-- End of FeesPayment Section -->
        <?php
        } else if ($sectionName == "points-overview") {
        ?>
            <div class="accordion mt-5 mx-md-5" id="accordionExample">
                <?php
                $sql6 = "SELECT `reference`, `dateTime`, `amount` FROM `points` WHERE studentID='$studentID '";
                $result6 = $con->query($sql6);
                if ($result6->num_rows > 0) {
                    while ($row6 = $result6->fetch_assoc()) {
                        $bdgName = ($row6['amount'] > 0) ? "Rewarded" : "Redeemed";
                        $bdgColour = ($row6['amount'] > 0) ? "bg-yellow-2" : "bg-red-2";
                ?>
                        <div class="accordion-item rounded-4 mt-4 border border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold d-flex align-items-center rounded-4 collapsed">
                                    <p class="mb-0 flex-grow-1"><?= $row6['reference'] . " | " . $row6['amount'] . " points" ?>
                                        <br>
                                        <span class="smallFont"><?= "Date: " . $row6['dateTime'] ?></span>
                                    </p>
                                    <h5 class="ms-auto badge p-2 fs-6 <?= $bdgColour ?> m-2"><?= $bdgName ?></h5>
                                </button>
                            </h2>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        <?php
        } else if ($sectionName == "exam-results") {
        ?>
            <div class="accordion mt-5 mx-md-5" id="accordionExample">
                <?php
                $sql7 = "SELECT `reference`, `marks`,`remark`,`dateAdded` FROM `results` WHERE  `studentID`='$studentID '";
                $result7 = $con->query($sql7);
                if ($result7->num_rows > 0) {
                    while ($row7 = $result7->fetch_assoc()) {
                        $markColour = ($row7['remark'] == "Fair") ? "bg-yellow-2" : (($row7['remark'] == "Good") ? "bg-green-2" : "bg-red-2");
                ?>
                        <div class="accordion-item rounded-4 mt-4 border border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold d-flex align-items-center rounded-4 collapsed">
                                    <p class="mb-0 flex-grow-1"><?= $row7['reference'] . " | " . $row7['marks'] . " Marks" ?>
                                        <br>
                                        <span class="smallFont"><?= "Date: " . $row7['dateAdded'] ?></span>
                                    </p>
                                    <h5 class="ms-auto badge p-2 fs-6 <?= $markColour ?> m-2"><?= $row7['remark'] ?></h5>
                                </button>
                            </h2>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        <?php
        }
        ?>
    </div>
</section>

<?php
require "includes/footer.php";
?>