<style>
    .list-group-item {
        padding-top: 20px !important;
        border-radius: 25px !important;
        margin-bottom: 5px;
        border-color: grey;
        background-color: var(--formControlBgColour);
    }

    .text-blue-2 {
        color: #96dbffd6;
    }

    .bg-grey-2 {
        background-color: var(--formControlBgColour);
    }

    .offcanvas-backdrop {
        background-color: rgba(23, 24, 30, .5) !important;
    }

    .offcanvas-backdrop.show {
        opacity: 1;
        background-color: rgba(23, 24, 30, .5) !important;
        backdrop-filter: blur(8px);
    }

    .box {
        display: none;
    }

    input[type="file"] {
        width: 100%;
        border-radius: 8px;
    }

    input::file-selector-button {
        background-color: var(--formControlBgColour);
        border: 0;
        color: #dedede;
        padding: 1rem 1.25rem;
        transition: all 0.25s;
        cursor: pointer;
        margin-right: 15px;
    }

    input:hover:not(:disabled):not([readonly])::-webkit-file-upload-button,
    input:hover:not(:disabled):not([readonly])::file-selector-button {
        background-color: var(--formControlBgColour);
        color: white;
    }

    .cart:focus {
        box-shadow: none;
    }

    .scrollBar {
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* IE and Edge */
    }

    /* For WebKit-based browsers (Chrome, Safari) */
    .scrollBar::-webkit-scrollbar {
        display: none;
    }
</style>

<div id="cartSectionOffCanvas">
    <?php
    if (isset($_SESSION['cart'])) {
        function displayListItem($className, $lessonName, $displayPrice, $lessonPrice, $lessonID)
        { ?>
            <li class="list-group-item d-flex justify-content-between align-items-start text-white displayListItem">
                <div class="ms-2 me-auto">
                    <div class="fw-bold"><?= $className ?> -</div>
                    <div class="fw-bold"><?= $lessonName ?></div>
                    <?php if ($displayPrice != $lessonPrice) { ?>
                        <span class="textGray fs-6"><del>LKR <?= $lessonPrice ?></del></span>
                        <span class="text-success fs-6">LKR <?= $displayPrice ?></span>
                    <?php } else { ?>
                        <span class="textGray fs-6">LKR <?= $lessonPrice ?></span>
                    <?php } ?>
                </div>
                <button id="r<?= $lessonID ?>" class="badge btn-custom btn-custom-red p-0 rounded-circle px-2 py-1 mt-3" onclick="removeFromCart('<?= $lessonID ?>')">
                    <i class="bi bi-x-lg"></i>
                </button>
            </li>
        <?php } ?>

        <div class=" offcanvas offcanvas-end text-bg-dark scrollBar" tabindex="-1" id="cartCanvas" aria-labelledby="offcanvasRightLabel" style="box-shadow: 0 0 10px 5px rgba(23, 24, 30, 0.5);background-color:var(--bgMain)!important;">
            <div class="offcanvas-header ">
                <h1 class="offcanvas-title mx-auto mt-3 fs-custom-40" id="offcanvasRightLabel">Checkout</h1>
                <button type="button" class="btn-close btn-close-white me-3 mt-2" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div id="cartSection" class="offcanvas-body">
                <ol class="list-group list-group-numbered">
                    <h5>Lessons in Cart</h5>
                    <?php
                    $subTotal = 0;
                    $LessonClassIDarray = array();
                    $classCount = array();
                    $finalTotal = 0; // Variable to store the final total

                    // Count the number of unique classes in the cart
                    $classOccurrences = array_count_values(array_column($_SESSION['cart'], 'ClassID'));

                    foreach ($classOccurrences as $classID => $occurrence) {
                        $resultClass = $con->query("SELECT `Name`, `Discount`, `discountCount` FROM `classes` WHERE `ID`='$classID'");

                        if ($resultClass->num_rows > 0) {
                            $classData = $resultClass->fetch_assoc();
                            $className = $classData['Name'];
                            $discount = $classData['Discount'];
                            $discountCount = $classData['discountCount'];

                            $lessonCount = $occurrence;
                            $divNumL = (int)($lessonCount / $discountCount);

                            foreach ($_SESSION['cart'] as $keys => $values) {
                                if ($values['ClassID'] == $classID) {
                                    $lessonID = $values['lessonID'];
                                    $sqllessons = "SELECT `Name`,`Price`,`StartTime`,`MeetingID` FROM `lessons` WHERE `Status`='1' AND `ID`='$lessonID'";
                                    $resultlessons = $con->query($sqllessons);

                                    if ($resultlessons->num_rows > 0) {
                                        while ($rowlessons = $resultlessons->fetch_assoc()) {
                                            $lessonName = $rowlessons['Name'];
                                            $lessonPrice = $rowlessons['Price'];
                                            $lessonStartTime = $rowlessons['StartTime'];
                                            $lessonMeetingID = $rowlessons['MeetingID'];

                                            if ($divNumL >= 1) {
                                                $lessonsToDiscount = $discountCount * $divNumL;
                                                $counter = $lessonsToDiscount;

                                                if ($counter > 0 && (new DateTime() < (new DateTime($lessonStartTime))->modify('+5 minutes'))) {
                                                    $discountedPrice = $lessonPrice - $discount;
                                                    $finalTotal += $discountedPrice;
                                                    $counter--;

                                                    // Display list item with discount
                                                    displayListItem($className, $lessonName, $discountedPrice, $lessonPrice, $lessonID);
                                                } elseif (new DateTime() < (new DateTime($lessonStartTime))->modify('+5 minutes')) {
                                                    $finalTotal += $lessonPrice;

                                                    // Display list item without discount
                                                    displayListItem($className, $lessonName, $lessonPrice, $lessonPrice, $lessonID);
                                                } else {
                                                    unset($_SESSION['cart'][$keys]);
                                                }
                                            } else {
                                                if (new DateTime() < (new DateTime($lessonStartTime))->modify('+5 minutes')) {
                                                    $finalTotal += $lessonPrice;

                                                    // Display list item without discount
                                                    displayListItem($className, $lessonName, $lessonPrice, $lessonPrice, $lessonID);
                                                } else {
                                                    unset($_SESSION['cart'][$keys]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    ?>

                </ol>

                <hr class="mx-3 mt-4 text-secondary" style="height:3px">
                <h2 class="fw-bold text-center my-4 text-blue-2">Total: Rs <?= number_format($finalTotal) ?></h2>
                <div class="mt-4">
                    <h5>Payment Method</h5>
                    <form action="payment/update" method="POST" enctype="multipart/form-data">
                        <select onChange="bankDetailsUpdate()" name="bankName" class="cart form-select bg-grey-2" required>
                            <option selected disabled>Choose...</option>
                            <option value="Commercial">Bank transfer/deposit</option>
                            <option value="card" disabled>Credit/Debit Card Payment</option>
                        </select>
                        <div class="mt-3" style="color:#b0b0b0">
                            <div class="Commercial box">
                                <div class="bank-details mb-3 bg-grey-2 alert rounded-4">
                                    Account name - ISMAIL MYMR</br>
                                    Account Number - 8100111657 </br>
                                    Bank - Commercial bank</br>
                                    Branch - Wellawatte
                                </div>
                                Make a bank transfer/deposit to the above account and upload the slip below.

                            </div>
                            <div class="mt-3 paymentUpload" style="display: none;">
                                <input class="bg-grey-2" type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg,image/jpg,image/png,application/pdf" required>
                                <button type="submit" name="cart" class="btn btn-lg btn-primary text-white primary-btn-shadow w-100 mt-5 fw-bold">Place Order</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<script>
    function bankDetailsUpdate() {
        var inputValue = $('[name="bankName"]').val();
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
        $(targetBox).show();
        $(".paymentUpload").show();
    }

    function addToCart(meetingID) {
        var mtID = meetingID;
        $("#" + mtID).prop("disabled", true);
        $("#" + mtID).html(
            "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Loading..."
        );
        $.post(
            "cart/add", {
                addToCart: mtID,
            },
            function(data) {
                if (data == "Success") {
                    $("#lessonSection").load(" #lessonSection > *");
                    $("#cartSectionOffCanvas").load(" #cartSectionOffCanvas > *");
                    $("#cartBtn").load(" #cartBtn > *");
                } else {
                    alert(data)
                }
            }
        );
    }

    function removeFromCart(meetingID) {
        var mtID = meetingID;
        $("#r" + mtID).prop("disabled", true);
        $("#r" + mtID).html(
            "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>"
        );
        $.post(
            "cart/remove", {
                delFromCart: mtID,
            },
            function(data) {
                if (data == "Success") {
                    $("#lessonSection").load(" #lessonSection > *");
                    $("#cartBtn").load(" #cartBtn > *");
                    $('#cartSection .displayListItem').length == 1 ?
                        $("#cartSectionOffCanvas").load(" #cartSectionOffCanvas > *") :
                        $("#cartSection").load(" #cartSection > *");

                    // $("#cartCanvas").addClass("show");
                } else {
                    alert(data)
                }
            }
        );
        // $("#cartCanvas").addClass("show");

    }
</script>