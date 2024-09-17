<?php
session_start();
$pgname = 'Lessons Students';
include('includes/allpages.php');
$meetingIDGET = $conn->real_escape_string($_GET['meetingID']);
$sql3 = "SELECT * FROM `lessons` WHERE `MeetingID`='$meetingIDGET '";
$result3 = $conn->query($sql3);
if ($result3->num_rows > 0) {
    while ($row3 = $result3->fetch_assoc()) {
        $lessonNameMTID = $row3['Name'];
    }
}
?>
<section class="home-section">
    <div class="section-header">
        <div class="text">Students</div>
        </br>
        <span class="text-secondary"><?= $lessonNameMTID  ?></span>
    </div>
    <div class="section-body mt-5">
        <div class="options-section row">
            <div class="col-md-auto">
                <input class="searchbar shadow-none" id="myInput" type="text" placeholder="Search...">
            </div>
            <div class="col-auto ms-auto">
                <span id="exportButtonArea">
                    <button id="exportButton" class="ms-auto btn-main mt-2 mt-md-0" onClick="exportTableToExcel('#tableMain','Students - <?= $lessonNameMTID ?>')">Export Data</button>
                </span>
            </div>
        </div>
        <div id="teachers-section" class="teachers-section">
            <div class="table-responsive">
                <table id="tableMain" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="table-head" style="padding-left: 12px;">ID</th>
                            <th class="table-head">First Name</th>
                            <th class="table-head">Last Name</th>
                            <th class="table-head">Gender</th>
                            <th class="table-head">School</th>
                            <th class="table-head">Mobile</th>
                            <th class="table-head">Parents Mobile</th>
                            <th class="table-head">District</th>
                            <th class="table-head">Address</th>
                            <th class="table-head">Email</th>
                            <th class="table-head">Join URL</th>
                            <th class="table-head no-sort">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="table-data-row">
                        <?php
                        $i = 01;
                        $sql2 = "SELECT * FROM `order_lesson` WHERE `MeetingID`='$meetingIDGET' AND `Status`='2'";
                        $result2 = $conn->query($sql2);
                        if ($result2->num_rows > 0) {
                            while ($row2 = $result2->fetch_assoc()) {
                                $key = $row2['ID'];
                                $orderLessonStudentID = $row2['UserID'];
                                $orderLessonStudentJoinURL = $row2['JoinUrl'];
                                $orderLessonStatus = $row2['Status'];
                                $sql = "SELECT * FROM `students` WHERE `ID`='$orderLessonStudentID'";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $studentFname = $row['Fname'];
                                        $studentLname = $row['Lname'];
                                        $studentGender = $row['Gender'];
                                        $studentSchool = $row['School'];
                                        $studentMobile = $row['Mobile'];
                                        $studentPMobile = $row['PMobile'];
                                        $studentDistriict = $row['Distriict'];
                                        $studentAddress = $row['Address'];
                                        $studentEmail = $row['Email'];
                                    }
                                }
                        ?>
                                <tr>
                                    <td class="table-data"> <?= str_pad($i++, 2, "0", STR_PAD_LEFT); ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentFname ?>')"><?= $studentFname ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentLname ?>')"><?= $studentLname ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentGender ?>')"><?= $studentGender ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentSchool ?>')"><?= $studentSchool ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentMobile ?>')"><?= $studentMobile ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentPMobile ?>')"><?= $studentPMobile ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentDistriict ?>')"><?= $studentDistriict ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentAddress ?>')"><?= $studentAddress ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentEmail ?>')"><?= $studentEmail ?></td>
                                    <td class="table-data" onclick="copyData('<?= $orderLessonStudentJoinURL ?>')"><a href="<?= $orderLessonStudentJoinURL ?>"><button class="btn btn-outline-success btn-sm">Join URL</button></a></td>
                                    <td>
                                        <div id="orderLesson<?= $key ?>" class="btn-group btn-group-sm" role="group" aria-label="Basic outlined example">
                                            <?php
                                            if ($orderLessonStatus == '2') {
                                            ?>
                                                <button class="btn btn-outline-secondary shadow-none" onClick="HaltAccess('<?= $key ?>')">Halt Access</button>
                                            <?php
                                            } elseif ($orderLessonStatus == '4') {
                                            ?>
                                                <button class="btn btn-outline-secondary shadow-none" onClick="EnableAccess('<?= $key ?>')">Grant Access</button>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php
include('includes/footer.php');
?>