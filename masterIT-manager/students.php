<?php
session_start();
$pgname = 'Students';
include('includes/allpages.php');
?>
<section class="home-section">
    <div class="section-header">
        <div class="text">Students</div>
    </div>
    <div class="section-body">
        <div class="options-section row">
            <div class="col-md-auto">
                <input class="searchbar shadow-none" id="myInput" type="text" placeholder="Search...">
            </div>
            <div class="col-auto ms-auto">
                <span id="exportButtonArea">
                    <button id="exportButton" class="ms-auto btn-main mt-2 mt-md-0" onClick="exportTableToExcel('#tableMain','Students')">Export Data</button>
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
                            <th class="table-head">Batch</th>
                            <th class="table-head">D.O.B</th>
                            <th class="table-head">Gender</th>
                            <th class="table-head">School</th>
                            <th class="table-head">Mobile</th>
                            <th class="table-head">Parents Mobile</th>
                            <th class="table-head">District</th>
                            <th class="table-head">Address</th>
                            <th class="table-head">Email</th>
                            <th class="table-head">Email Verification Code</th>
                            <th class="table-head">Status</th>
                            <th class="table-head no-sort">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="table-data-row">
                        <?php
                        $sql = "SELECT * FROM `students` ORDER BY `ID` DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $i = 01;
                            $result_array = array();
                            while ($row = $result->fetch_assoc()) {
                                $key = $row['ID'];
                                $result_array[] = $row;
                                $studentFname = $row['Fname'];
                                $studentLname = $row['Lname'];
                                $studentBatch = $row['Batch'];
                                $studentDOB = $row['DOB'];
                                $studentGender = $row['Gender'];
                                $studentSchool = $row['School'];
                                $studentMobile = $row['Mobile'];
                                $studentPMobile = $row['PMobile'];
                                $studentDistriict = $row['District'];
                                $studentAddress = $row['Address'];
                                $studentEmail = $row['Email'];
                                $studentStatus = $row['Status'];
                                $studentEmailVerificationCode = $row['EmailVerificationCode'];
                                if ($studentStatus == '1') {
                                    $studentActiveStatus = "Active";
                                    $studentActiveColour = "success";
                                } elseif ($studentStatus == '0') {
                                    $studentActiveStatus = "Pending verification";
                                    $studentActiveColour = "warning";
                                } elseif ($studentStatus == '2') {
                                    $studentActiveStatus = "Disabled";
                                    $studentActiveColour = "danger";
                                }
                        ?>
                                <tr>
                                    <td class="table-data"> <?= str_pad($i++, 2, "0", STR_PAD_LEFT); ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentFname ?>')"><?= $studentFname ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentLname ?>')"><?= $studentLname ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentBatch ?>')"><?= $studentBatch ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentDOB ?>')"><?= $studentDOB ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentGender ?>')"><?= $studentGender ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentSchool ?>')"><?= $studentSchool ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentMobile ?>')"><?= $studentMobile ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentPMobile ?>')"><?= $studentPMobile ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentDistriict ?>')"><?= $studentDistriict ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentAddress ?>')"><?= $studentAddress ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentEmail ?>')"><?= $studentEmail ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentEmailVerificationCode ?>')"><?= $studentEmailVerificationCode ?></td>
                                    <td class="table-data" onclick="copyData('<?= $studentActiveStatus ?>')"><button class="btn btn-outline-<?= $studentActiveColour ?> btn-sm"><?= $studentActiveStatus ?></button></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic outlined example">
                                            <?php
                                            if ($studentStatus == '1') {
                                            ?>
                                                <button class="btn btn-outline-secondary shadow-none" onClick="disableBtnOC('<?= $key ?>')">Disable</button>
                                            <?php
                                            } elseif ($studentStatus == '2' or $studentStatus == '0') {
                                            ?>
                                                <button class="btn btn-outline-secondary shadow-none" onClick="EnableBtnOC('<?= $key ?>')">Enable</button>
                                            <?php
                                            }
                                            ?>
                                            <button class="btn btn-outline-secondary shadow-none deleteBtnOC" onClick="deleteBtnOC('<?= $key ?>')">Delete</button>
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

<!-- Delete Class Modal -->
<div class="modal fade" id="deleteElement" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content modal-section">
            <div class="modal-body p-0">
                <section class="home-section" class="home-section" style="min-height: auto!important;left: 0;width:100%;">
                    <div class="section-header">
                        <div class="text" style="padding-left: 25px;">Delete Student</div>
                    </div>
                    <div class="section-body">
                        <div style="padding-left: 25px;">
                            Are you sure you want to delete this Student? Note that all records relavant to the selected student will be deleted once confirmed.
                        </div>
                        <div class="d-flex justify-content-end mt-5 gap-2">
                            <button type="button" class="btn-main" data-bs-dismiss="modal">Cancel</button>
                            <form method="POST" action="dbh/deletestudent">
                                <button type="submit" name="Deletetudent" id="deleteElementButton" class="btn btn-danger shadow-none" style="border-radius: 0.5rem; padding: 0.75rem 1.25rem;">Delete</button>
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