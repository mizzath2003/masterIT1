<?php
session_start();
require "dbh/dbdata.php";
requireLogin("yes");
define("PAGE_TITLE", "Edit Profile");
require "includes/header.php";
include_once "components/navbar.php";

$email = strtolower($con->real_escape_string($_SESSION['email']));
$sql = "SELECT `ImgLink`, `Fname`, `Lname`, `Batch`, `DOB`, `Gender`, `School`, `Mobile`, `PMobile`,
`District`, `Address` FROM `students` WHERE `Email`='$email'";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        foreach ($row as $field => $value) {
            ${$field} = $value;
        }
    }
}
?>

<!-- Register Form -->
<section class="main-section container-xl mx-auto mb-5">
    <div class="row container-xl mx-auto align-items-center register-form">
        <h1 class="col mx-auto mt-5 mb-4 text-center">Edit Profile</h1>
        <form action="<?= CLIENT_URL ?>student/update" method="POST" class="row g-3 needs-validation" id="regForm" novalidate>
            <div class="col-md-4">
                <label for="validationCustom01" class="form-label">First name</label>
                <input type="text" class="form-control" id="validationCustom01" name="fname" value="<?= $Fname ?>" required>
                <div class="invalid-feedback">
                    Please enter your first name.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom02" class="form-label">Last name</label>
                <input type="text" class="form-control" id="validationCustom02" name="lname" value="<?= $Lname ?>" required>
                <div class="invalid-feedback">
                    Please enter your last name.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom12" class="form-label">O/L Batch</label>
                <select class="form-select" name="olbatch" id="validationCustom04" required>
                    <option <?= checkActive($Batch, "2025", "selected") ?> value="2025">Grade 09 </option>
                    <option <?= checkActive($Batch, "2024", "selected") ?> value="2024">Grade 10 </option>
                    <option <?= checkActive($Batch, "2023", "selected") ?> value="2023">Grade 11 </option>
                </select>
                <div class="invalid-feedback">
                    Please enter your O/L batch.
                </div>
            </div>

            <div class="col-md-3">
                <label for="validationCustom03" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" name="dob" id="validationCustom03" value="<?= $DOB ?>" required>
                <div class="invalid-feedback">
                    Please select your date of birth.
                </div>
            </div>
            <div class="col-md-3">
                <label for="validationCustom04" class="form-label">Gender</label>
                <select class="form-select" name="gender" id="validationCustom04" required>
                    <option <?= checkActive($Gender, "Male", "selected") ?>>Male</option>
                    <option <?= checkActive($Gender, "Female", "selected") ?>>Female</option>
                </select>
                <div class="invalid-feedback">
                    Please select a valid gender.
                </div>
            </div>
            <div class="col-md-6">
                <label for="validationCustom05" class="form-label">School</label>
                <input type="text" class="form-control" name="school" id="validationCustom05" value="<?= $School ?>" required>
                <div class="invalid-feedback">
                    Please enter a valid school.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom08" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" name="mobile" id="validationCustom08" onkeyup="this.value=this.value.replace(/[^\d]/,'')" maxlength="10" minlength="10" value="<?= $Mobile ?>" required>
                <div class="invalid-feedback">
                    Please enter a valid mobile number.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom09" class="form-label">Parents Mobile Number</label>
                <input type="text" class="form-control" name="pmobile" id="validationCustom09" onkeyup="this.value=this.value.replace(/[^\d]/,'')" maxlength="10" minlength="10" value="<?= $PMobile ?>" required>
                <div class="invalid-feedback">
                    Please enter a valid mobile number.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom06" class="form-label">District</label>
                <select class="form-select" name="district" id="validationCustom06" required>
                    <option <?= checkActive($District, "Ampara", "selected") ?>>Ampara</option>
                    <option <?= checkActive($District, "Anuradhapura", "selected") ?>>Anuradhapura</option>
                    <option <?= checkActive($District, "Badulla", "selected") ?>>Badulla</option>
                    <option <?= checkActive($District, "Batticaloa", "selected") ?>>Batticaloa</option>
                    <option <?= checkActive($District, "Colombo", "selected") ?>>Colombo</option>
                    <option <?= checkActive($District, "Galle", "selected") ?>>Galle</option>
                    <option <?= checkActive($District, "Gampaha", "selected") ?>>Gampaha</option>
                    <option <?= checkActive($District, "Hambantota", "selected") ?>>Hambantota</option>
                    <option <?= checkActive($District, "Jaffna", "selected") ?>>Jaffna</option>
                    <option <?= checkActive($District, "Kalutara", "selected") ?>>Kalutara</option>
                    <option <?= checkActive($District, "Kandy", "selected") ?>>Kandy</option>
                    <option <?= checkActive($District, "Kegalle", "selected") ?>>Kegalle</option>
                    <option <?= checkActive($District, "Kilinochchi", "selected") ?>>Kilinochchi</option>
                    <option <?= checkActive($District, "Kurunegala", "selected") ?>>Kurunegala</option>
                    <option <?= checkActive($District, "Mannar", "selected") ?>>Mannar</option>
                    <option <?= checkActive($District, "Matale", "selected") ?>>Matale</option>
                    <option <?= checkActive($District, "Matara", "selected") ?>>Matara</option>
                    <option <?= checkActive($District, "Monaragala", "selected") ?>>Monaragala</option>
                    <option <?= checkActive($District, "Mullaitivu", "selected") ?>>Mullaitivu</option>
                    <option <?= checkActive($District, "Nuwara Eliya", "selected") ?>>Nuwara Eliya</option>
                    <option <?= checkActive($District, "Polonnaruwa", "selected") ?>>Polonnaruwa</option>
                    <option <?= checkActive($District, "Puttalam", "selected") ?>>Puttalam</option>
                    <option <?= checkActive($District, "Ratnapura", "selected") ?>>Ratnapura</option>
                    <option <?= checkActive($District, "Trincomalee", "selected") ?>>Trincomalee</option>
                    <option <?= checkActive($District, "Vavuniya", "selected") ?>>Vavuniya</option>
                </select>
                <div class="invalid-feedback">
                    Please select a district.
                </div>
            </div>
            <div class="col-md-12">
                <label for="validationCustom07" class="form-label">Address</label>
                <div class="input-group has-validation">
                    <textarea class="form-control" name="address" id="validationCustom07" rows="3" required><?= $Address ?> </textarea>
                    <div class="invalid-feedback">
                        Please enter a valid address.
                    </div>
                </div>
            </div>
            <!-- change to image upload -->
            <div class="col-md-4">
                <label for="validationCustomUsername" class="form-label">Email</label>
                <div class="input-group has-validation">
                    <input type="email" class="form-control" id="validationCustomUsername" name="email" aria-describedby="inputGroupPrepend" value="<?= $email ?>" disabled required>
                    <div class="invalid-feedback">
                        Please enter a valid email.
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label for="pass1" class="form-label">Password</label>
                <input type="password" class="form-control" id="pass1" name="password" required onkeyup="hideError()">
                <div class="invalid-feedback">
                    Please enter a valid Password.
                </div>
            </div>
            <div class="col-md-4">
                <label for="pass2" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="pass2" name="confirm_password" required onkeyup="hideError()">
                <div class="invalid-feedback">
                    Please enter a valid Password.
                </div>
            </div>

            <div class="d-grid gap-2 mb-3 mt-5 col-4 mx-auto">
                <button type="submit" class="btn-custom btn-custom-orange btnSignUp">Update</button>
            </div>
            <div class="mt-2 alert alert-danger" role="alert" id="error" style="visibility:hidden">
                Passwords do not match
            </div>
        </form>
    </div>
</section>
<!-- End of Register Form -->


<script>
    $(document).ready(function() {
        $("#regForm input").on("keydown", function(event) {
            if (event.key === "Enter" || event.keyCode === 13) {
                event.preventDefault();
                $("#regForm input").eq($("#regForm input").index(this) + 1).focus();
            }
        });
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    });
</script>


<?php
require "includes/footer.php";
?>