<?php
session_start();
require "dbh/dbdata.php";
requireLogin("yes");

$receivedString = $_GET['meetingID'];
$decodedMeetingID = $con->real_escape_string(base64_decode(str_rot13(urldecode(str_replace(['-', '--'], ['%', '='], $receivedString)))));

$sql = "SELECT `JoinUrl` FROM `order_lesson` WHERE `Status`='2' AND `UserID`='$studentID' AND `LessonID`='$decodedMeetingID'";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sql2 = "SELECT l.MeetingID, l.RecordingLink,l.ClassID, l.Name, l.StartTime, l.Duration, l.Price, l.ImgLink, c.Name AS ClassName
                    FROM lessons l LEFT JOIN classes c ON l.ClassID = c.ID WHERE l.Status='1' AND l.ID='$decodedMeetingID'";
        $result2 = $con->query($sql2);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $startDateTime = new DateTime($row2['StartTime']);
                $startDate = $startDateTime->format("M d, Y");
                $durationTime = $startDateTime->format("g:i") . " - " . $startDateTime->add(new DateInterval('PT' . $row2['Duration'] . 'M'))->format("g:i A");

                if ((new DateTime()) > $startDateTime) {
                    $btnText = "View Recording";
                    $btnLink = $row2['RecordingLink'];
                } else {
                    $btnText = "Join Meeting";
                    $btnLink = $row['JoinUrl'];
                }

                $imgLink = $row2['ImgLink'];
                $className = $row2['ClassName'];
                $lessonName = $row2['Name'];
            }
        } else {
            $_SESSION['error'] = "Lesson inactive";
            header('Location: ' . CLIENT_URL . "classes");
            die();
        }
    }
} else {
    $_SESSION['error'] = "Lesson not purchased";
    header('Location: ' . CLIENT_URL . "classes");
    die();
}

define("PAGE_TITLE", "Lesson Details");
require "includes/header.php";
include_once "components/navbar.php";
include_once "components/topBar.php";
?>


<!-- LESSONS SECTION -->
<section class="main-section container-xl mx-auto mb-5 ">
    <?= topBar("Lesson Overview") ?>

    <div class="row mb-5">
        <div class="col-md-6">
            <img src="<?= MANAGER_URL . $imgLink ?>" alt="Lesson Image" class="img-fluid rounded-4">
        </div>
        <div class="col-md-6 lessonOverview">
            <h5 class="flex-fill mt-1 ClassName mt-4"><?= $className ?></h5>
            <h4 class=" text-start" style="margin-bottom: 12px;"><?= $lessonName ?></h4>
            <div class="d-flex flex-wrap gap-3 mb-5">
                <p class="textGray mb-0 lh-1 me-2"><?= $startDate ?></br><span class="smallFont">Date</span></p>
                <p class="textGray mb-0 lh-1"><?= $durationTime ?></br><span class="smallFont">Time</span></p>
            </div>
            <a class="jmt-link d-flex btn-custom btn-custom-orange fw-bold fs-5 me-md-5 w-100 justify-content-center" href="<?= $btnLink ?>" target="_blank"><?= $btnText ?></a>

            <nav class="nav nav-pills flex-row">
                <button type=" button" class="flex-fill text-sm-center nav-link tute" onClick="showTab('tute')">Tute</button>
                <button type="button" class="flex-fill text-sm-center nav-link materials" onClick="showTab('materials')">Materials</button>
            </nav>
            <!-- tab content -->
            <div id="tute" class="mt-4 ms-3 box">
                <!-- Tute Request -->
                <?php
                $sql3 = "SELECT tutes.Name, tutes.Link, tutes.dateAdded FROM tute_lesson INNER JOIN tutes ON tute_lesson.tuteId = tutes.ID WHERE tute_lesson.lessonId = '$decodedMeetingID' AND tutes.Status = '1'";
                $result3 = $con->query($sql3);

                if ($result->num_rows > 0) {
                    while ($row3 = $result3->fetch_assoc()) {
                ?>
                        <div class="row mt-4">
                            <div class="col-7 col-lg-6">
                                <p class="mb-0"><?= $row3['Name'] ?></p>
                                <span class="smallFont">Published On: <?= $row3['dateAdded'] ?></span>
                            </div>
                            <div class="col-auto text-end">
                                <a href="<?= $row3['Link'] ?>" target="_blank" class="btn btn-custom btn-custom-outline-orange2">View</a>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <div id="materials" class="mt-4 ms-3 box">
                <?php
                $sql4 = "SELECT materials.name, materials.link , materials.dateCreated FROM materials_lesson INNER JOIN materials ON materials_lesson.materialID = materials.id WHERE materials_lesson.lessonID = '$decodedMeetingID' AND materials.status = '1'";
                $result4 = $con->query($sql4);

                if ($result4->num_rows > 0) {
                    while ($row4 = $result4->fetch_assoc()) {
                ?>
                        <div class="materials-list col-12 col-lg-8 row  position-relative">
                            <p class="col text-start mt-2 "><?= $row4['name'] ?></p>
                            <a class="col text-end  stretched-link me-2 mt-1" href="<?= $row4['link'] ?>" target="_blank">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                <?php
                    }
                }
                ?>

            </div>
        </div>


</section>
<script>
    showTab('tute')

    function showTab(tabName) {
        $(".box").not(tabName).hide();
        $(".nav-link").not(tabName).removeClass("active");
        $("." + tabName).addClass("active");
        $("#" + tabName).show();
    }
</script>
<?php
require "includes/footer.php";
?>