<?php
session_start();
$pgname = 'Classes';
include('includes/allpages.php');
?>
<section class="home-section">
    <div class="section-header">
        <div class="text">Classes</div>
    </div>
    <div class="section-body">
        <div class="options-section row">
            <div class="col-md-auto">
                <input class="searchbar shadow-none" id="myInput" type="text" placeholder="Search...">
            </div>
            <div class="col-auto ms-auto">
                <button class="btn-main ms-auto mt-2 mt-md-0" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Bulk Schedule</button>
                <span id="exportButtonArea">
                    <button id="exportButton" class="ms-2 btn-main mt-2 mt-md-0" onClick="exportTableToExcel('#tableMain','Classes')">Export Data</button>
                </span>
                <button class="btn-main ms-2 mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#classesModal">New Class</button>
            </div>
        </div>
        <div class="teachers-section">
            <div class="table-responsive">
                <table id="tableMain" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="table-head" style="padding-left: 12px;">No</th>
                            <th class="table-head">Class Image</th>
                            <th class="table-head">Curriculam</th>
                            <th class="table-head">Class Name</th>
                            <th class="table-head">Announcement</th>
                            <th class="table-head">Discount (Per class)</th>
                            <th class="table-head">Discount Count</th>
                            <th class="table-head">Colour</th>
                            <th class="table-head">Class Status</th>
                            <th class="table-head no-sort">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="table-data-row">
                        <?php
                        $sql = "SELECT * FROM `classes` ORDER BY `ID` ASC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $i = 01;
                            $result_array = array();
                            while ($row = $result->fetch_assoc()) {
                                $key = $row['ID'];
                                $result_array[] = $row;
                                $className = $row['Name'];
                                $classcurriculam = $row['curriculam'];
                                $classannouncement = $row['Announcement'];
                                $classdiscount = $row['Discount'];
                                $classDiscountCount = $row['discountCount'];
                                $classcolour = $row['colour'];
                                $classImage = $row['Image'];
                                $classStatus = $row['Status'];
                                ($classStatus == '1') ? $ClassActiveStatus = "Active" : $ClassActiveStatus = "Inative";
                        ?>
                                <tr>
                                    <td class="table-data"> <?= str_pad($i++, 2, "0", STR_PAD_LEFT); ?></td>
                                    <td class="table-data"><img width="100px" class="rounded-3" src="<?= $websiteURLFull . $classImage ?>" alt="<?= $className ?>"></td>
                                    <td class="table-data" onclick="copyData('<?= $classcurriculam ?>')"><?= $classcurriculam ?></td>
                                    <td class="table-data" onclick="copyData('<?= $className ?>')"><?= $className ?></td>
                                    <td class="table-data" onclick="copyData('<?= $classannouncement ?>')"><?= $classannouncement ?></td>
                                    <td class="table-data" onclick="copyData('<?= $classdiscount ?>')"><?= $classdiscount ?></td>
                                    <td class="table-data" onclick="copyData('<?= $classDiscountCount ?>')"><?= $classDiscountCount ?></td>
                                    <td class="table-data" onclick="copyData('<?= $classcolour ?>')"><button class="btn  shadow-none" style="background-color:<?= $classcolour ?>"><?= $classcolour ?></button></td>
                                    <td class="table-data" onclick="copyData('<?= $ClassActiveStatus ?>')"><?= $ClassActiveStatus ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic outlined example">
                                            <a href="<?= $websiteURLFull . "lessons/" . $key ?>" class="btn btn-outline-secondary shadow-none">Lessons</a>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#EditClassModal<?= $key ?>" class="btn btn-outline-secondary shadow-none">Edit</button>
                                            <button class="btn btn-outline-secondary shadow-none deleteBtnOC" data-token="<?= $key ?>">Delete</button>
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
    $key = $row2['ID'];
    $className = $row2['Name'];
    $classannouncement = $row2['Announcement'];
    $classStatus = $row2['Status'];
    $classdiscount = $row2['Discount'];
    $classDiscountCount = $row2['discountCount'];
    $classcolour = $row2['colour'];
    $classcurriculam = $row2['curriculam'];
?>
    <!-- Update Class Modal -->
    <div class="modal fade" id="EditClassModal<?= $key ?>" tabindex="-1" aria-labelledby="EditclassesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content modal-section">
                <div class="modal-body p-0">
                    <section class="home-section" style="min-height: auto!important;left: 0;width:100%;">
                        <div class="section-header">
                            <div class="text" style="padding-left: 25px;">Edit Class</div>
                        </div>
                        <div class="section-body">
                            <form method="POST" action="dbh/updateclass" enctype="multipart/form-data">
                                <div class="row mx-auto">
                                    <div class="col-md-4">
                                        <label class="mb-0 mt-3 center-head">Class Name</label>
                                        <input class="form-control searchbar shadow-none" type="text" name="className" value="<?= $className ?>" placeholder="Enter class name" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="mb-0 mt-3 center-head">Class Curriculam</label>
                                        <input class="form-control searchbar shadow-none" type="text" name="classCurriculam" value="<?= $classcurriculam ?>" placeholder="Enter Class Curriculam" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="mb-0 mt-3 center-head">Class Status</label>
                                        <select class="form-select searchbar shadow-none" name="classStatus" value="<?= $classStatus ?>" required>
                                            <option value="1" <?php equalCheck($classStatus, '1', 'selected') ?>>Active</option>
                                            <option value="0" <?php equalCheck($classStatus, '0', 'selected') ?>>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="mb-0 mt-3 center-head">Class Colour</label>
                                        <input class="form-control form-control-color searchbar shadow-none" type="color" name="classColour" value="<?= $classcolour ?>" placeholder="Enter Class Theme Came" required style="min-width: 100%;min-height: 50%;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="mb-0 mt-3 center-head">Class Discount (4)</label>
                                        <input class="form-control searchbar shadow-none" type="number" name="classDiscount" value="<?= $classdiscount ?>" placeholder="Enter per class discount when purchased all 4 lessons together" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="mb-0 mt-3 center-head">Discount Count</label>
                                        <input class="form-control searchbar shadow-none" type="number" name="classDiscountCount" value="<?= $classDiscountCount ?>" placeholder="Enter min count of lesson to be purchased together" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="mb-0 mt-3 center-head">Class Announcement</label>
                                        <textarea class="searchbar shadow-none" name="announcement" rows="3"><?= $classannouncement ?></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="mb-0 mt-3 center-head">Thumbnail</label>
                                        <input type="file" class="form-control form-control-lg shadow-none" name="fileToUpload" id="fileToUpload" accept="image/gif,image/jpeg,image/jpg,image/png,application/pdf" style="color: white !important;background-color: var(--bg-secondary-blue) !important;border: none !important;font-size: 1rem !important;font-weight: 400 !important;border-radius: 0.5rem !important;">
                                    </div>
                                    <div class="col-auto mx-auto mt-5">
                                        <button type="submit" name="update_class" value="<?= $key ?>" class="col-auto btn-main">Update</button>
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
?>


<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Bulk Lesson Schedule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form method="POST" action="./dbh/createlesson" enctype="multipart/form-data">
            <div>
                <p class="border rounded-3 p-3 text-secondary">Attach a CSV file with the coloumns as per the below respective order -</br>
                    ClassID, Lesson Name, Start Time, Duration (Min), Price, Thumbnail (Path)</p>
                <label for="formFile" class="form-label">Attach CSV file</label>
                <input class="form-control" type="file" accept=".csv" id="lessonScheduleFile" name="lessonScheduleFile" required>
                <button class="btn btn-primary mt-3" type="submit" id="bulkschedule" name="bulkschedule">Start scheduling</button>
            </div>
        </form>
    </div>
</div>

<!-- New Classs Modal -->
<div class="modal fade" id="classesModal" tabindex="-1" aria-labelledby="classesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content modal-section">
            <div class="modal-body p-0">
                <section class="home-section" style="min-height: auto!important;left: 0;width:100%;">
                    <div class="section-header">
                        <div class="text" style="padding-left: 25px;">New Class</div>
                    </div>
                    <div class="section-body">
                        <form method="POST" action="dbh/createclass" enctype="multipart/form-data">
                            <div class="row mx-auto">
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Class Name</label>
                                    <input class="form-control searchbar shadow-none" type="text" name="className" placeholder="Enter Class name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Class Curriculam</label>
                                    <input class="form-control searchbar shadow-none" type="text" name="classCurriculam" placeholder="Enter Class Curriculam" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Class Status</label>
                                    <select class="form-select searchbar shadow-none" name="classStatus" required>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Class Colour</label>
                                    <input class="form-control form-control-color searchbar shadow-none" type="color" name="classColour" value="#ff0000" placeholder="Enter Class Theme Came" required style="min-width: 100%;min-height: 50%;">
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Class Discount (4)</label>
                                    <input class="form-control searchbar shadow-none" type="number" name="classDiscount" placeholder="Enter per class discount when purchased all 4 lessons together" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0 mt-3 center-head">Discount Count</label>
                                    <input class="form-control searchbar shadow-none" type="number" name="classDiscountCount" placeholder="Enter min count of lesson to be purchased together" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0 mt-3 center-head">Class Announcement</label>
                                    <textarea class="searchbar shadow-none" name="announcement" rows="3"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0 mt-3 center-head">Thumbnail</label>
                                    <input type="file" class="form-control form-control-lg shadow-none" name="fileToUpload" id="fileToUpload" accept="image/gif,image/jpeg,image/jpg,image/png,application/pdf" style="color: white !important;background-color: var(--bg-secondary-blue) !important;border: none !important;font-size: 1rem !important;font-weight: 400 !important;border-radius: 0.5rem !important;">
                                </div>
                                <div class="col-auto mx-auto mt-5">
                                    <button type="submit" name="createClass" class="col-auto btn-main">Create Class</button>
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
                        <div class="text" style="padding-left: 25px;">Delete Class</div>
                    </div>
                    <div class="section-body">
                        <div style="padding-left: 25px;">
                            Are you sure you want to delete this class? Note that all records relavant to the selected class will be deleted once confirmed.
                        </div>
                        <div class="d-flex justify-content-end mt-5 gap-2">
                            <button type="button" class="btn-main" data-bs-dismiss="modal">Cancel</button>
                            <form method="POST" action="dbh/deleteclass">
                                <button type="submit" name="DeletePayment" id="deleteElementButton" class="btn btn-danger shadow-none" style="border-radius: 0.5rem; padding: 0.75rem 1.25rem;">Delete</button>
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