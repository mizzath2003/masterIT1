<?php
session_start();
$pgname = 'Tutes';
include('includes/allpages.php');
?>
<section class="home-section">
    <div class="section-header">
        <div class="text">Tutes</div>
    </div>
    <div class="section-body">
        <div class="options-section row">
            <div class="col-md-auto">
                <input class="searchbar shadow-none" id="myInput" type="text" placeholder="Search...">
            </div>
            <div class="col-auto ms-auto">
                <span id="exportButtonArea">
                    <button id="exportButton" class="ms-auto btn-main mt-2 mt-md-0" onClick="exportTableToExcel('#tableMain','Tutes')">Export Data</button>
                </span>
                <button class="btn-main ms-2 mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#tutesModal">New Tute</button>
            </div>
        </div>
        <div class="teachers-section">
            <div class="table-responsive">
                <table id="tableMain" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="table-head" style="padding-left: 12px;">ID</th>
                            <th class="table-head">Name</th>
                            <th class="table-head">Link</th>
                            <th class="table-head">Status</th>
                            <th class="table-head no-sort">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="table-data-row">
                        <?php
                        $sql = "SELECT * FROM `tutes` ORDER BY `ID` DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $i = 01;
                            while ($row = $result->fetch_assoc()) {
                                $key = $row['ID'];
                                $tuteName = $row['Name'];
                                $tuteLink = $row['Link'];
                                $tuteStatus = $row['Status'];
                                ($tuteStatus == '1') ? $TuteActiveStatus = "Active" : $TuteActiveStatus = "Inative";
                                ($tuteStatus == '1') ? $tuteActiveColour = "success" : $tuteActiveColour = "warning";
                        ?>
                                <tr>
                                    <td class="table-data"> <?= str_pad($i++, 2, "0", STR_PAD_LEFT); ?></td>
                                    <td class="table-data" id="TuteName<?= $key ?>" onclick="copyData('<?= $tuteName ?>')"><?= $tuteName ?></td>
                                    <td class="table-data"><a id="TuteLink<?= $key ?>" href="<?=$tuteLink?>" target="_blank"><button class="btn btn-outline-primary btn-sm"><?=$tuteLink?></button></a></td>
                                    <td class="table-data" onclick="copyData('<?= $TuteActiveStatus ?>')"><button id="TuteStatus<?= $key ?>" class="btn btn-outline-<?= $tuteActiveColour ?> btn-sm"><?= $TuteActiveStatus ?></button></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic outlined example">
                                            <button type="button" class="btn btn-outline-secondary shadow-none" onclick="editTute('<?= $key ?>')">Edit</button>
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

<!-- Edit Tute Modal -->
<div class="modal fade" id="EditTuteModal" tabindex="-1" aria-labelledby="EditTuteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content modal-section">
            <div class="modal-body p-0">
                <section class="home-section" style="min-height: auto!important;left: 0;width:100%;">
                    <div class="section-header">
                        <div class="text" style="padding-left: 25px;">Edit Tute</div>
                    </div>
                    <div class="section-body">
                        <form method="POST" action="<?= $websiteURLFull?>dbh/tute_edit" enctype="multipart/form-data">
                            <div class="row mx-auto">
                                <div class="col-md-6">
                                    <label class="mb-0 mt-3 center-head">Tute Name</label>
                                    <input class="form-control searchbar shadow-none" id="EditTuteName" type="text" name="EditTuteName" placeholder="Enter tute name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-0 mt-3 center-head">Tute Status</label>
                                    <select class="form-select searchbar shadow-none" name="tuteStatus" required>
                                        <option id="EditTuteActive" value="1">Active</option>
                                        <option id="EditTuteInactive" value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0 mt-3 center-head">Tute</label>
                                    <input id="EditTuteLink" type="url" class="form-control searchbar shadow-none" name="tuteLink">
                                </div>
                                <div class="col-auto mx-auto mt-5">
                                    <button id="EditTuteID" type="submit" name="updateTute" class="col-auto btn-main">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- New Tute Modal -->
<div class="modal fade" id="tutesModal" tabindex="-1" aria-labelledby="tutesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content modal-section">
            <div class="modal-body p-0">
                <section class="home-section" style="min-height: auto!important;left: 0;width:100%;">
                    <div class="section-header">
                        <div class="text" style="padding-left: 25px;">New Tute</div>
                    </div>
                    <div class="section-body">
                        <form method="POST" action="<?= $websiteURLFull?>dbh/tute_create" enctype="multipart/form-data">
                            <div class="row mx-auto">
                                <div class="col-md-6">
                                    <label class="mb-0 mt-3 center-head">Tute Name</label>
                                    <input class="form-control searchbar shadow-none" type="text" name="tuteName" placeholder="Enter tute name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-0 mt-3 center-head">Tute Status</label>
                                    <select class="form-select searchbar shadow-none" name="tuteStatus" required>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0 mt-3 center-head">Tute Link</label>
                                    <input type="url" class="form-control searchbar shadow-none" name="tuteLink">
                                </div>
                                <div class="col-auto mx-auto mt-5">
                                    <button type="submit" name="createTute" class="col-auto btn-main">Create Tute</button>
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
                        <div class="text" style="padding-left: 25px;">Delete Tute</div>
                    </div>
                    <div class="section-body">
                        <div style="padding-left: 25px;">
                            Are you sure you want to delete this tute?
                        </div>
                        <div class="d-flex justify-content-end mt-5 gap-2">
                            <button type="button" class="btn-main" data-bs-dismiss="modal">Cancel</button>
                            <form method="POST" action="<?= $websiteURLFull?>dbh/tute_delete">
                                <button type="submit" name="deleteteTute" id="deleteElementButton" class="btn btn-danger shadow-none" style="border-radius: 0.5rem; padding: 0.75rem 1.25rem;">Delete</button>
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
<script>
    function editTute(elementID) {
        $('#TuteStatus'+elementID).text() == "Active" ? $("#EditTuteActive").attr("selected","selected") : $("#EditTuteInactive").attr("selected","selected");
        $("#EditTuteName").val($('#TuteName'+elementID).text());        
        $("#EditTuteID").val(elementID);
        $("#EditTuteLink").val($('#TuteLink'+elementID).attr('href'));
        $("#EditTuteModal").modal("show");
    }
</script>