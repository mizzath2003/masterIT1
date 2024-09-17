<?php
session_start();
$pgname = 'Lessons';
include('includes/allpages.php');
if (isset($_GET['class_id'])) {
    $classID = $conn->real_escape_string($_GET['class_id']);
    $sql2 = "SELECT * FROM `classes` WHERE `ID`='$classID' AND `Status`='1'";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows > 0) {
        while ($row2 = $result2->fetch_assoc()) {
            $className = $row2['Name'];
        }
    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
?>
<section class="home-section">
    <div class="section-header">
        <div class="text">Lessons</div>
    </div>
    <div class="section-body">
        <div class="options-section row">
            <div class="col-md-auto">
                <input class="searchbar shadow-none" id="myInput" type="text" placeholder="Search...">
            </div>
            <div class="col-auto ms-auto">
                <span id="exportButtonArea">
                    <button id="exportButton" class="ms-auto btn-main mt-2 mt-md-0" onClick="exportTableToExcel('#tableMain','Lessons')">Export Data</button>
                </span>
                <button class="btn-main ms-2 mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#NewLessonModal">New Lesson</button>
            </div>
        </div>
        <div class="teachers-section">
            <div class="table-responsive">
                <table id="tableMain" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="table-head" style="padding-left: 12px;">ID</th>
                            <th class="table-head">Lesson Image</th>
                            <th class="table-head">Class name</th>
                            <th class="table-head">Lesson Name</th>
                            <th class="table-head">Price</th>
                            <th class="table-head">Start Time</th>
                            <th class="table-head">Duration</th>
                            <th class="table-head">Meeting ID</th>
                            <th class="table-head">Passcode</th>
                            <th class="table-head">Tute</th>
                            <th class="table-head">Recording</th>
                            <th class="table-head">Status</th>
                            <th class="table-head no-sort">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="table-data-row">
                        <?php
                        $result_array = array();
                        $sql = "SELECT * FROM `lessons` WHERE `ClassID`='$classID' ORDER BY `StartTime` DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $i = 01;
                            while ($row = $result->fetch_assoc()) {
                                $key = $row['ID'];
                                $result_array[] = $row;
                                $lessonMeetingID = $row['MeetingID'];
                                $lessonPasscode = $row['Passcode'];
                                $lessonName = $row['Name'];
                                $lessonStartTime = $row['StartTime'];
                                $lessonDuration = $row['Duration'];
                                $lessonPrice = $row['Price'];
                                $lessonTuteID = $row['TuteID'];
                                $lessonRecordingLink = $row['RecordingLink'];
                                $lessonstatus = $row['Status'];
                                $lessonImgLink = $row['ImgLink'];
                                ($lessonstatus == '1') ? $LessonActiveStatus = "Active" : $LessonActiveStatus = "Inative";
                                ($lessonstatus == '1') ? $LessonActiveColour = "success" : $LessonActiveColour = "warning";

                                $sql3 = "SELECT * FROM `tutes` WHERE `ID`='$lessonTuteID' AND `Status`='1'";
                                $result3 = $conn->query($sql3);
                                if ($result3->num_rows > 0) {
                                    while ($row3 = $result3->fetch_assoc()) {
                                        $TuteName = $row3['Name'];
                                        $TuteLink = $row3['Link'];
                                    }
                                } else {
                                    $TuteName = "Undefined";
                                    $TuteLink = "";
                                }

                        ?>
                                <tr>
                                    <td class="table-data"> <?= str_pad($i++, 2, "0", STR_PAD_LEFT); ?></td>
                                    <td class="table-data"><img width="100px" class="rounded-3" src="<?= $websiteURLFull . $lessonImgLink ?>" alt="<?= $lessonName ?>"></td>
                                    <td class="table-data" onclick="copyData('<?= $className ?>')"><?= $className ?></td>
                                    <td class="table-data" onclick="copyData('<?= $lessonName ?>')"><?= $lessonName ?></td>
                                    <td class="table-data" onclick="copyData('<?= $lessonPrice ?>')"><?= $lessonPrice ?></td>
                                    <td class="table-data" onclick="copyData('<?= $lessonStartTime ?>')"><?= $lessonStartTime ?></td>
                                    <td class="table-data" onclick="copyData('<?= $lessonDuration ?>')"><?= $lessonDuration ?> Min</td>
                                    <td class="table-data" onclick="copyData('<?= $lessonMeetingID ?>')"><?= $lessonMeetingID ?></td>
                                    <td class="table-data" onclick="copyData('<?= $lessonPasscode ?>')"><?= $lessonPasscode ?></td>
                                    <td class="table-data"><a href="<?= $websiteURLFull . $TuteLink ?>"><button class="btn btn-outline-primary btn-sm"><?= $TuteName ?></button></a></td>
                                    <td class="table-data"><a href="<?= $lessonRecordingLink ?>"><button class="btn btn-outline-danger btn-sm">Recording</button></a></td>
                                    <td class="table-data" onclick="copyData('<?= $LessonActiveStatus ?>')"><button class="btn btn-outline-<?= $LessonActiveColour ?> btn-sm"><?= $LessonActiveStatus ?></button></td>

                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic outlined example">
                                            <a href="<?= $websiteURLFull . "lessons/students/" . $lessonMeetingID ?>" class="btn btn-outline-secondary shadow-none">Students</a>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#EditLessonModal<?= $key ?>" class="btn btn-outline-secondary shadow-none">Edit</button>
                                            <!-- <button class="btn btn-outline-secondary shadow-none deleteBtnOC" data-token="<?= $key ?>">Delete</button> -->
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
foreach ($result_array as $row2) {
    if (!empty($row2)) {
        $key = $row2['ID'];
        $lessonMeetingID = $row2['MeetingID'];
        $lessonPasscode = $row2['Passcode'];
        $lessonName = $row2['Name'];
        $lessonStartTime = $row2['StartTime'];
        $lessonDuration = $row2['Duration'];
        $lessonPrice = $row2['Price'];
        $lessonTuteID = $row2['TuteID'];
        $lessonRecordingLink = $row2['RecordingLink'];
        $lessonstatus = $row2['Status'];
        $lessonImgLink = $row2['ImgLink'];
?>
        <!-- Update Class Modal -->
        <div class="modal fade" id="EditLessonModal<?= $key ?>" tabindex="-1" aria-labelledby="EditclassesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                <div class="modal-content modal-section">
                    <div class="modal-body p-0">
                        <section class="home-section" style="min-height: auto!important;left: 0;width:100%;">
                            <div class="section-header">
                                <div class="text" style="padding-left: 25px;">Edit Lesson</div>
                            </div>
                            <div class="section-body">
                                <form method="POST" action="../dbh/updatelesson" enctype="multipart/form-data">
                                    <div class="row mx-auto">
                                        <div class="col-md-4">
                                            <label class="mb-0 mt-3 center-head">Class</label>
                                            <input class="form-control searchbar shadow-none" type="text" name="className" value="<?= $className ?>" required disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="mb-0 mt-3 center-head">Lesson Status</label>
                                            <select class="form-select searchbar shadow-none" name="lessonStatus" required>
                                                <option value="1" <?php equalCheck($lessonstatus, '1', 'selected') ?>>Active</option>
                                                <option value="0" <?php equalCheck($lessonstatus, '0', 'selected') ?>>Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="mb-0 mt-3 center-head">Tute</label>
                                            <select class="form-select searchbar shadow-none" name="LessonTute">
                                                <option value="#">Select Tute</option>
                                                <?php
                                                $i = 0;

                                                $sql4 = "SELECT * FROM `tutes` WHERE `Status`='1'";
                                                $result4 = $conn->query($sql4);
                                                if ($result4->num_rows > 0) {
                                                    while ($row4 = $result4->fetch_assoc()) {
                                                        $TuteID = $row4['ID'];
                                                        $TuteName = $row4['Name'];
                                                ?>
                                                        <option value="<?= $TuteID ?>" <?php equalCheck($lessonTuteID, $TuteID, 'selected') ?>><?= $TuteName ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-3 center-head">Lesson Name</label>
                                            <input class="form-control searchbar shadow-none" type="text" name="LessonName" value="<?= $lessonName ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="mb-0 mt-3 center-head">Start Time</label>
                                            <input class="form-control searchbar shadow-none" type="datetime-local" name="LessonStartTime" value="<?= $lessonStartTime ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="mb-0 mt-3 center-head">Duration</label>
                                            <input class="form-control searchbar shadow-none" type="number" name="LessonDuration" placeholder="Enter the lessons duration in minutes" value="<?= $lessonDuration ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="mb-0 mt-3 center-head">Price</label>
                                            <input class="form-control searchbar shadow-none" type="number" name="LessonPrice" placeholder="Enter the lessons price in LKR" value="<?= $lessonPrice ?>" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-3 center-head">Recording Link</label>
                                            <input class="form-control searchbar shadow-none" type="text" name="LessonRecordingLink" placeholder="Enter the recordings link" value="<?= $lessonRecordingLink ?>">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="mb-0 mt-3 center-head">Thumbnail</label>
                                            <input type="file" class="form-control form-control-lg shadow-none" name="fileToUpload" id="fileToUpload" accept="image/gif,image/jpeg,image/jpg,image/png,application/pdf" style="color: white !important;background-color: var(--bg-secondary-blue) !important;border: none !important;font-size: 1rem !important;font-weight: 400 !important;border-radius: 0.5rem !important;">
                                        </div>
                                        <div class="col-auto mx-auto mt-5">
                                            <button type="submit" name="updateLesson" value="<?= $lessonMeetingID ?>" class="col-auto btn-main">Update Lesson</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
<?php }
}
?>
<!-- New Classs Modal -->
<div class="modal fade" id="NewLessonModal" tabindex="-1" aria-labelledby="classesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content modal-section">
            <div class="modal-body p-0">
                <section class="home-section" style="min-height: auto!important;left: 0;width:100%;">
                    <div class="section-header">
                        <div class="text" style="padding-left: 25px;">New Lesson</div>
                    </div>
                    <div class="section-body">
                        <form method="POST" action="../dbh/createlesson" enctype="multipart/form-data">
                            <div class="row mx-auto">
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Class</label>
                                    <input class="form-control searchbar shadow-none" type="text" name="className" value="<?= $className ?>" required readonly="readonly">
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Lesson Status</label>
                                    <select class="form-select searchbar shadow-none" name="lessonStatus" required>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Tute</label>
                                    <select class="form-select searchbar shadow-none" name="LessonTute">
                                        <option value="#" selected>Select Tute</option>
                                        <?php
                                        $i = 0;

                                        $sql4 = "SELECT * FROM `tutes` WHERE `Status`='1'";
                                        $result4 = $conn->query($sql4);
                                        if ($result4->num_rows > 0) {
                                            while ($row4 = $result4->fetch_assoc()) {
                                                $TuteID = $row4['ID'];
                                                $TuteName = $row4['Name'];
                                        ?>
                                                <option value="<?= $TuteID ?>"><?= $TuteName ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0 mt-3 center-head">Lesson Name</label>
                                    <input class="form-control searchbar shadow-none" type="text" name="LessonName" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Start Time</label>
                                    <input class="form-control searchbar shadow-none" type="datetime-local" name="LessonStartTime" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Duration</label>
                                    <input class="form-control searchbar shadow-none" type="number" name="LessonDuration" placeholder="Enter the lessons duration in minutes" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Price</label>
                                    <input class="form-control searchbar shadow-none" type="number" name="LessonPrice" placeholder="Enter the lessons price in LKR" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0 mt-3 center-head">Thumbnail</label>
                                    <input type="file" class="form-control form-control-lg shadow-none" name="fileToUpload" id="fileToUpload" accept="image/gif,image/jpeg,image/jpg,image/png,application/pdf" style="color: white !important;background-color: var(--bg-secondary-blue) !important;border: none !important;font-size: 1rem !important;font-weight: 400 !important;border-radius: 0.5rem !important;">
                                </div>
                                <div class="col-auto mx-auto mt-5">
                                    <button type="submit" name="createLesson" value="<?= $classID ?>" class="col-auto btn-main">Create Lesson</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- Delete Class Modal -->
<div class="modal fade" id="deleteElement" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content modal-section">
            <div class="modal-body p-0">
                <section class="home-section" class="home-section" style="min-height: auto!important;left: 0;width:100%;">
                    <div class="section-header">
                        <div class="text" style="padding-left: 25px;">Delete Lesson</div>
                    </div>
                    <div class="section-body">
                        <div style="padding-left: 25px;">
                            Are you sure you want to delete this lesson? Note that all records relavant to the selected lesson will be deleted once confirmed.
                        </div>
                        <div class="d-flex justify-content-end mt-5 gap-2">
                            <button type="button" class="btn-main" data-bs-dismiss="modal">Cancel</button>
                            <form method="POST" action="dbh/deletelesson">
                                <button type="submit" name="DeleteLesson" id="deleteElementButton" class="btn btn-danger shadow-none" style="border-radius: 0.5rem; padding: 0.75rem 1.25rem;">Delete</button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<?php
include('includes/footer.php');
?>