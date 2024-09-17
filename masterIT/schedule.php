<?php
session_start();
require "dbh/dbdata.php";
requireLogin("yes");
define("PAGE_TITLE", "My Schedule");
require "includes/header.php";
require "components/navbar.php";
require "components/topBar.php";
require "components/lessonCard.php";
require "components/scheduleDate.php";
require "components/scheduleLesson.php";
?>

<style>
    .lesson-card-text h5 {
        font-size: 0.8rem;
        background-color: var(--bgcard);
        max-width: fit-content;
        padding: 3px 12px;
        border-radius: 6px;
        margin-bottom: 12px;
    }

    .lesson-content p {
        border: 2px dotted hsl(254.29deg 9.5% 43.33%);
        padding: 5px;
        border-radius: 7px;
    }

    /* Schedule */
    .schedule-section-date p {
        color: hsl(46.67deg 85.07% 60.59%);
        font-size: 13px;
    }

    .schedule-section-date span {
        font-weight: 800;
        background-color: hsl(46.67deg 85.07% 60.59%);
        border-radius: 50%;
        padding: 13px;
    }

    .past-task a {
        padding: 10px 18px;
        border-radius: 10px;
    }

    .past-task a:hover {
        background-color: hsla(47, 85%, 61%, 0.333) !important;
        color: hsl(46.67deg 85.07% 60.59%) !important;
        border: 1px solid hsl(46.67deg 85.07% 60.59%);

    }

    .schedule-section-info p {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 1px;
    }

    /* .schedule-section-button {
        padding: 10px;
    } */

    @media (max-width: 768px) {
        .past-task a {
            padding: 9px 17px;
            border-radius: 10px;
            font-size: 1.1rem !important;
        }

        .schedule-section-info h4 {
            font-size: 0.8rem !important;
        }

        .schedule-section-info span {
            font-size: 13px;
        }

        .schedule-section-button {
            padding: 12px 0;
        }
    }
</style>

<section class="main-section container-xl mx-auto px-1 mb-5 ">
    <!-- TopBar -->
    <?= topBar("My Schedule") ?>

    <div class="row mx-auto g-5 gap-md-5 gap-0">
        <div class="col-sm-6 col-md-4">
            <h2 class="mb-4">Ongoing</h2>

            <!-- If no lessons availabele  -->
            <div class="d-flex flex-column inputColor rounded-4 justify-content-center align-items-center">
                <img src="assets/images/robot1.png" alt="robot" width="250" height="250">
                <p class="fs-3 fw-semibold smalltextColor">No Ongoing Lessons</p>
            </div>
            <?php
            $i = 0;
            $lessonsArray = array();
            $lessonsArray1 = array();
            $lessonsArray2 = array();
            $sql = "SELECT `LessonID`, `JoinUrl` FROM `order_lesson` WHERE `Status`='2' AND `UserID`='$studentID'";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $lessonID = $row['LessonID'];
                    $lessonJoinUrl = $row['JoinUrl'];

                    $sql2 = "SELECT l.MeetingID, l.ClassID, l.Name, l.StartTime, l.Duration, l.Price, l.ImgLink, c.Name AS ClassName
                    FROM lessons l LEFT JOIN classes c ON l.ClassID = c.ID WHERE l.Status='1' AND l.ID='$lessonID' ";
                    $result2 = $con->query($sql2);
                    if ($result2->num_rows > 0) {
                        while ($row2 = $result2->fetch_assoc()) {
                            $startDateTime = new DateTime($row2['StartTime']);
                            $startDate = $startDateTime->format("M d, Y");
                            $startDay = $startDateTime->format("j");
                            $monthYear = $startDateTime->format("M Y");
                            $durationTime = $startDateTime->format("g:i") . " - " . $startDateTime->add(new DateInterval('PT' . $row2['Duration'] . 'M'))->format("g:i A");

                            if (((new DateTime()) <= $startDateTime)) {
                                if (((new DateTime()) >= (new DateTime($row2['StartTime']))->modify('-15 minutes'))) {
                                    lessonCard($lessonID, MANAGER_URL . $row2['ImgLink'], $row2['ClassName'], $row2['Price'], $row2['Name'], $startDate, $durationTime, 3, $lessonJoinUrl);
                                }
                                $lessonsArray1[$i++] = [
                                    'date' => $startDay,
                                    'monthYear' => $monthYear,
                                    'duration' => $durationTime,
                                    'lessonName' => $row2['Name'],
                                    'className' => $row2['ClassName'],
                                    'lessonID' => $lessonID
                                ];
                            }
                            if (($startDateTime <= (new DateTime()))) {
                                $lessonsArray2[$i++] = [
                                    'date' => $startDay,
                                    'monthYear' => $monthYear,
                                    'duration' => $durationTime,
                                    'lessonName' => $row2['Name'],
                                    'className' => $row2['ClassName'],
                                    'lessonID' => $lessonID
                                ];
                            }
                        }
                    }
                }
            }
            if (!isset($_GET['pastTask']) and (!empty($lessonsArray1) or !empty($lessonsArray2))) {
                $lessonsArray = $lessonsArray1;
                $sectionText = "Up Next";
                $btnText = "Past Tasks";
                $btnLink = CLIENT_URL . "schedule/past-tasks";
            } else {
                $lessonsArray = $lessonsArray2;
                $sectionText = "Past Tasks";
                $btnText = "Up Next";
                $btnLink = CLIENT_URL . "schedule";
            }


            ?>
        </div>

        <div class="col-sm-6 col-md-7">
            <div class="row mb-3 d-flex">
                <h2 class="col-auto"><?= $sectionText ?></h2>
                <a class="col-auto btn-custom btn-custom-gray ms-auto" href="<?= $btnLink ?>"><?= $btnText ?></a>
            </div>
            <?php
            if (!empty($lessonsArray)) {
            ?>
                <div class="schedule-section py-4 px-md-4 inputColor rounded-4 row gap-2">
                    <?php
                    // Sort lessons by date
                    usort($lessonsArray, function ($a, $b) {
                        return strtotime($a['monthYear'] . ' ' . $a['date']) - strtotime($b['monthYear'] . ' ' . $b['date']);
                    });

                    $previousDate = null;

                    foreach ($lessonsArray as $lesson) {

                        $currentDate = $lesson['date'];
                        $monthYear = $lesson['monthYear'];

                        if ($currentDate !== $previousDate) {
                            scheduleDate($currentDate, $monthYear);
                        }
                        scheduleLesson($lesson['duration'], $lesson['lessonName'], $lesson['className'], $lesson['lessonID']);
                        $previousDate = $currentDate;
                    }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>

    </div>
</section>
<!-- END OF LESSONS CLASS -->



</section>

<?php
require "includes/footer.php";
?>