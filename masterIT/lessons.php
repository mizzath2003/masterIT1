<?php
session_start();
require "dbh/dbdata.php";
requireLogin("yes");
define("PAGE_TITLE", "Lessons");
require "includes/header.php";
include_once "components/navbar.php";
include_once "components/topBar.php";
include_once "components/lessonCard.php";

$decodedClassID = base64_decode(str_rot13(urldecode(str_replace(['-', '--'], ['%', '='], $_GET['classID']))));
?>


<!-- LESSONS SECTION -->
<section class="main-section container-xl mx-auto mb-5 ">
    <!-- TopBar -->
    <?= topBar("Lessons") ?>
    <div id="lessonSection">
        <div class="row mx-auto g-4 pb-5">
            <?php
            $sql = "SELECT l.ID, l.MeetingID, l.ClassID, l.Name, l.StartTime, l.Duration, l.Price, l.ImgLink, c.colour,c.Name AS ClassName
        FROM lessons l LEFT JOIN classes c ON l.ClassID = c.ID WHERE l.Status='1' AND l.ClassID='$decodedClassID'";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $lessonID = $row['ID'];
                    $thumbnailColour = $row['colour'];

                    $startDateTime = new DateTime($row['StartTime']);
                    $startDate = $startDateTime->format("M d, Y");
                    $durationTime = $startDateTime->format("g:i") . " - " . $startDateTime->add(new DateInterval('PT' . $row['Duration'] . 'M'))->format("g:i A");

                    $sql4 = "SELECT `Status` FROM `order_lesson` WHERE `UserID`='$studentID' AND `LessonID`='$lessonID' AND `Status`!=3";
                    $result4 = $con->query($sql4);
                    $OrderLessonStatus = ($result4->num_rows > 0) ? $result4->fetch_assoc()['Status'] : 0;

                    if (isset($_SESSION['cart'])) {
                        $id_array = array_column($_SESSION['cart'], 'lessonID');
                        (in_array($lessonID, $id_array)) ? $OrderLessonStatus = 4 : "";
                    }
                    if (new DateTime() < (new DateTime($row['StartTime']))) {
                        // Lessons Card
            ?>
                        <div class="col-sm-6 col-md-4">
                            <?php
                            lessonCard($lessonID, MANAGER_URL . $row['ImgLink'], $row['ClassName'], $row['Price'], $row['Name'], $startDate, $durationTime, $OrderLessonStatus, '#', $thumbnailColour);
                            ?>
                        </div>
            <?php
                    }
                }
            }
            ?>
        </div>
    </div>
</section>
<!-- END OF LESSONS CLASS -->

<?php
require "includes/footer.php";
?>