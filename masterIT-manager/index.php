<?php
session_start();
$pgname = 'Dashboard';
include('includes/allpages.php');

$date_startTime = date("Y-m-d") . "T00:00";
$date_EndTime  = date("Y-m-d", strtotime("+1 day")) . "T00:00";
$month_startTime = date("Y-m-01 00:00:00");
$month_EndTime  = date("Y-m-01 00:00:00", strtotime("+1 month"));

$sql = "SELECT COUNT(ID) FROM `students` WHERE `Status`='1';";
$sql .= "SELECT COUNT(ID) FROM `classes` WHERE `Status`='1';";
$sql .= "SELECT COUNT(ID) FROM `lessons` WHERE `Status`='1' AND `StartTime`>'$dateTodayCurrent';";
$sql .= "SELECT COUNT(ID) FROM `orders` WHERE `Status`='1';";

$i = 0;
// Execute multi query
if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            while ($row = $result->fetch_row()) {
                $achievement[$i++]  = $row[0];
            }
            $result->free_result();
        }
        //Prepare next result set
    } while ($conn->next_result());
}

//Check Dialog E SMS Balance
$smsBalance = substr(strrchr(rtrim(file_get_contents("https://e-sms.dialog.lk/api/v1/message-via-url/check/balance?esmsqk=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6ODk5MSwiaWF0IjoxNjcwNjY0OTIwLCJleHAiOjQ3OTQ4NjczMjB9.VwZhkJBST-jjIUGi9EtbRTvEFGZVj_AGy2pg4ZXh1UY"), '|'), '|'), 1);
?>
<section class="home-section">
    <div class="section-header">
        <div class="text">Home</div>
    </div>
    <div class="section-body">
        <div class="achievements-section row gap-3" style="margin-top: 50px;">
            <div class="achievements">
                <h1><?= number_format($achievement[0]) ?></h1><span>Students</span>
            </div>
            <div class="achievements">
                <h1><?= number_format($achievement[1]) ?></h1><span>Classes</span>
            </div>
            <div class="achievements">
                <h1><?= number_format($achievement[2]) ?></h1><span>Upcoming Lessons</span>
            </div>
            <div class="achievements">
                <h1><?= number_format($achievement[3]) ?></h1><span>Pending Invoices</span>
            </div>
            <div class="achievements">
                <h1><?= number_format($smsBalance) ?></h1><span>SMS Balance</span>
            </div>
        </div>
        <div class="classes-section">
            <h3>Lessons Today</h3>
            <span><?php echo date("F j, Y | l"); ?></span>
            <div class="classes row">
                <?php
                $sql2 = "SELECT * FROM `lessons` WHERE `Status`='1' AND `StartTime` BETWEEN '$date_startTime' AND '$date_EndTime' ORDER BY `StartTime` ASC";
                $result2 = $conn->query($sql2);
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        $lessonImgLink = $websiteURLFull . $row2['ImgLink'];
                        $lessonID = $row2['ID'];
                        $lessonMeetingID = $row2['MeetingID'];
                        $lessonName = $row2['Name'];
                        $lessonStartTime = $row2['StartTime'];
                        $lessonDateTime = new DateTime("$lessonStartTime");
                        $lessonDate =  $lessonDateTime->format('d/m/Y');
                        $lessonTime =  $lessonDateTime->format('h:i A');
                ?>
                        <div class="col-xxl-2 col-xl-3 col-lg-4 col-sm-6 p-0">
                            <a href="lessons/students/<?= $lessonMeetingID ?>">
                                <div class="class-card">
                                    <div class="ratio ratio-1x1">
                                        <img src="<?= $lessonImgLink ?>" class="img-fluid" alt="">
                                    </div>
                                    <h4><?= $lessonName ?></h4>
                                    <span>
                                        Date: <?= $lessonDate ?> </br>
                                        Time: <?= $lessonTime ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>
<?php
include('includes/footer.php');
?>