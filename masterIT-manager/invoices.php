<?php
session_start();
$pgname = 'Invoices';
include('includes/allpages.php');
?>

<section class="home-section">
    <div class="section-header">
        <div class="text">Invoices</div>
    </div>
    <div class="section-body">
        <div class="options-section row">
            <div class="col-md-auto">
                <input class="searchbar shadow-none" id="myInput" type="text" placeholder="Search...">
            </div>
            <div class="col-auto ms-auto">
                <?php
                if (isset($_GET['status'])) {
                    $invStatus = $conn->real_escape_string($_GET['status']);
                    if ($invStatus == "All") {
                        $paymentRedirectButton = "Pending";
                        $sql = "SELECT * FROM `orders` ORDER BY `ID` DESC";
                    }
                    if ($invStatus == "Pending") {
                        $paymentRedirectButton = "All";
                        $sql = "SELECT * FROM `orders` WHERE `Status`='1' ORDER BY `ID` DESC";
                    }
                } else {
                    $paymentRedirectButton = "All";
                    $sql = "SELECT * FROM `orders` WHERE `Status`='1' ORDER BY `ID` DESC";
                }
                ?>
                <a href="?status=<?= $paymentRedirectButton ?>"><button class="ms-auto btn-main mt-2 mt-md-0"><?= $paymentRedirectButton ?> Invoices</button></a>
            </div>
            <div class="col-auto">
                <span id="exportButtonArea">
                    <button id="exportButton" class="ms-auto btn-main mt-2 mt-md-0" onClick="exportTableToExcel('#tableMain','Invoices')">Export Data</button>
                </span>
            </div>
        </div>
        <div class="teachers-section">
            <div class="table-responsive">
                <table id="tableMain" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="table-head" style="padding-left: 12px;">ID</th>
                            <th class="table-head">Date & Time</th>
                            <th class="table-head">First Name</th>
                            <th class="table-head">Last Name</th>
                            <th class="table-head">Mobile</th>
                            <th class="table-head">Email</th>
                            <th class="table-head">Lessons</th>
                            <th class="table-head">Total</th>
                            <th class="table-head">Payment Method</th>
                            <th class="table-head">Payment Receipt</th>
                            <th class="table-head">Status</th>
                            <th class="table-head no-sort">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="table-data-row">
                        <?php
                        // $sql = "SELECT * FROM `orders` ORDER BY `ID` DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $i = 01;
                            $result_array = array();
                            while ($row = $result->fetch_assoc()) {
                                $allLessonsPurchased = "";
                                $paymentOrderToken = $orderID = $key = $row['ID'];
                                $result_array[] = $row;
                                $paymentOrderUserID = $row['UserID'];
                                $paymentOrderDate = $row['Date'];
                                $paymentTotalAmountToBePaid = $row['Total'];
                                $paymentOrderPaymentMethod = $row['PaymentMethod'];
                                $paymentOrderReceipt = $row['Receipt'];
                                $paymentOrderStatus = $row['Status'];

                                $paymentOrderlessons = "";
                                $sql2 = "SELECT l.ClassID, COUNT(*) AS count FROM order_lesson ol JOIN lessons l ON ol.LessonID = l.ID WHERE ol.orderID = '$orderID' GROUP BY l.ClassID";
                                $result2 = $conn->query($sql2);
                                if ($result2->num_rows > 0) {
                                    while ($row2 = $result2->fetch_assoc()) {
                                        $classID = $row2['ClassID'];
                                        $occurrence = $row2['count'];
                                        $resultClass = $conn->query("SELECT `Name`, `Discount`, `discountCount` FROM `classes` WHERE `ID`='$classID'");

                                        if ($resultClass->num_rows > 0) {
                                            $classData = $resultClass->fetch_assoc();
                                            $className = $classData['Name'];
                                            $discount = $classData['Discount'];
                                            $discountCount = $classData['discountCount'];
                                            $sql3 = "SELECT `LessonID` FROM `order_lesson` ol WHERE `orderID` = '$orderID'";
                                            $result3 = $conn->query($sql3);

                                            if ($result3->num_rows > 0) {
                                                while ($row3 = $result3->fetch_assoc()) {
                                                    $lessonID = $row3['LessonID'];
                                                    $sql4 = "SELECT `Name`, `Price` FROM `lessons` WHERE `ID`='$lessonID'";
                                                    $result4 = $conn->query($sql4);

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
                                                                    $paymentOrderlessons .= "$listItemName - $discountedPrice</br>";
                                                                } else {
                                                                    $paymentOrderlessons .= "$listItemName - $lessonPrice</br>";
                                                                }
                                                            } else {
                                                                $paymentOrderlessons .= "$listItemName - $lessonPrice</br>";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }



                                $sql2 = "SELECT `Fname`,`Lname`,`Mobile`,`Email` FROM `students` WHERE `ID`='$paymentOrderUserID'";
                                $result2 = $conn->query($sql2);
                                if ($result2->num_rows > 0) {
                                    while ($row2 = $result2->fetch_assoc()) {
                                        $paymentStudentFName = $row2['Fname'];
                                        $paymentStudentLName = $row2['Lname'];
                                        $paymentStudentMobile = $row2['Mobile'];
                                        $paymentStudentEmail = $row2['Email'];
                                    }
                                }

                                if ($paymentOrderStatus == "1") {
                                    $paymentApprovalStatus = "Pending";
                                    $paymentApprovalStatusColor = "warning";
                                } elseif ($paymentOrderStatus == "2") {
                                    $paymentApprovalStatus = "Approved";
                                    $paymentApprovalStatusColor = "success";
                                } elseif ($paymentOrderStatus == "3") {
                                    $paymentApprovalStatus = "Rejected";
                                    $paymentApprovalStatusColor = "danger";
                                }
                        ?>
                                <tr>
                                    <td class="table-data"> <?= str_pad($i++, 2, "0", STR_PAD_LEFT); ?></td>
                                    <td class="table-data" onclick="copyData('<?= $paymentOrderDate ?>')"><?= $paymentOrderDate ?></td>
                                    <td class="table-data" onclick="copyData('<?= $paymentStudentFName ?>')"><?= $paymentStudentFName ?></td>
                                    <td class="table-data" onclick="copyData('<?= $paymentStudentLName ?>')"><?= $paymentStudentLName ?></td>
                                    <td class="table-data" onclick="copyData('<?= $paymentStudentMobile ?>')"><?= $paymentStudentMobile ?></td>
                                    <td class="table-data" onclick="copyData('<?= $paymentStudentEmail ?>')"><?= $paymentStudentEmail ?></td>
                                    <td class="table-data" onclick="copyData('<?= $paymentOrderlessons ?>')"><?= $paymentOrderlessons ?></td>
                                    <td class="table-data" onclick="copyData('<?= $paymentTotalAmountToBePaid ?>')"><?= $paymentTotalAmountToBePaid ?></td>
                                    <td class="table-data" onclick="copyData('<?= $paymentOrderPaymentMethod ?>')"><?= $paymentOrderPaymentMethod ?></td>
                                    <td class="table-data"><a href="<?= $clientWebsiteURLFull . $paymentOrderReceipt ?>" target="_blank"><button class="btn btn-outline-primary btn-sm">Click to view receipt</button></td>
                                    <td class="table-data" onclick="copyData('<?= $paymentOrderStatus ?>')"><button class="btn btn-outline-<?= $paymentApprovalStatusColor ?> btn-sm"><?= $paymentApprovalStatus ?></button></td>
                                    <td>
                                        <?php
                                        if ($paymentOrderStatus == "1") {
                                        ?>

                                            <div id="manage<?= $paymentOrderToken ?>" class="btn-group btn-group-sm" role="group" aria-label="Basic outlined example">
                                                <button class="btn btn-outline-secondary shadow-none" onClick="ApprovePayment('<?= $paymentOrderToken ?>')">Approve</button>
                                                <form action="dbh/updatePayment" method="POST">
                                                    <button class="btn btn-outline-secondary shadow-none" name="RejectPaymentID" value="<?= $paymentOrderToken ?>">Reject</button>
                                                </form>
                                            </div>
                                        <?php
                                        }
                                        ?>
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