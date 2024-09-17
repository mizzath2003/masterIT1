<?php
session_start();
require "dbh/dbdata.php";
requireLogin("no", "./");
define("PAGE_TITLE", "Register");
require "includes/header.php";
include_once "components/navbar.php";

?>

<!-- Register Form -->
<section class="main-section container-xl mx-auto mb-5">
    <div class="row container-xl mx-auto align-items-center register-form">
        <h1 class="col mx-auto mt-5 mb-4 text-center">Sign Up</h1>
        <form action="<?= CLIENT_URL ?>student/register" method="POST" class="row g-3 needs-validation" id="regForm" novalidate>
            <div class="col-md-4">
                <label for="validationCustom01" class="form-label">First name</label>
                <input type="text" class="form-control" id=" " name="fname" required>
                <div class="invalid-feedback">
                    Please enter your first name.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom02" class="form-label">Last name</label>
                <input type="text" class="form-control" id="validationCustom02" name="lname" required>
                <div class="invalid-feedback">
                    Please enter your last name.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom12" class="form-label">O/L Batch</label>
                <select class="form-select" name="olbatch" id="validationCustom04" required>
                    <option selected disabled value="">Choose...</option>
                    <option value="2025">Grade 09 </option>
                    <option value="2024">Grade 10 </option>
                    <option value="2023">Grade 11 </option>
                </select>
                <div class="invalid-feedback">
                    Please enter your O/L batch.
                </div>
            </div>

            <div class="col-md-3">
                <label for="validationCustom03" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" name="dob" id="validationCustom03" required>
                <div class="invalid-feedback">
                    Please select your date of birth.
                </div>
            </div>
            <div class="col-md-3">
                <label for="validationCustom04" class="form-label">Gender</label>
                <select class="form-select" name="gender" id="validationCustom04" required>
                    <option selected disabled value="">Choose...</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>
                <div class="invalid-feedback">
                    Please select a valid gender.
                </div>
            </div>
            <div class="col-md-6">
                <label for="validationCustom05" class="form-label">School</label>
                <input type="text" class="form-control" name="school" id="validationCustom05" required>
                <div class="invalid-feedback">
                    Please enter a valid school.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom08" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" name="mobile" id="validationCustom08" onkeyup="this.value=this.value.replace(/[^\d]/,'')" maxlength="10" minlength="10" required>
                <div class="invalid-feedback">
                    Please enter a valid mobile number.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom09" class="form-label">Parents Mobile Number</label>
                <input type="text" class="form-control" name="pmobile" id="validationCustom09" onkeyup="this.value=this.value.replace(/[^\d]/,'')" maxlength="10" minlength="10" required>
                <div class="invalid-feedback">
                    Please enter a valid mobile number.
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustom06" class="form-label">District</label>
                <select class="form-select" name="district" id="validationCustom06" required>
                    <option selected disabled value="">Choose...</option>
                    <option>Ampara</option>
                    <option>Anuradhapura</option>
                    <option>Badulla</option>
                    <option>Batticaloa</option>
                    <option>Colombo</option>
                    <option>Galle</option>
                    <option>Gampaha</option>
                    <option>Hambantota</option>
                    <option>Jaffna</option>
                    <option>Kalutara</option>
                    <option>Kandy</option>
                    <option>Kegalle</option>
                    <option>Kilinochchi</option>
                    <option>Kurunegala</option>
                    <option>Mannar</option>
                    <option>Matale</option>
                    <option>Matara</option>
                    <option>Monaragala</option>
                    <option>Mullaitivu</option>
                    <option>Nuwara Eliya</option>
                    <option>Polonnaruwa</option>
                    <option>Puttalam</option>
                    <option>Ratnapura</option>
                    <option>Trincomalee</option>
                    <option>Vavuniya</option>
                </select>
                <div class="invalid-feedback">
                    Please select a district.
                </div>
            </div>
            <div class="col-md-12">
                <label for="validationCustom07" class="form-label">Address</label>
                <div class="input-group has-validation">
                    <textarea class="form-control" name="address" id="validationCustom07" rows="3" required></textarea>
                    <div class="invalid-feedback">
                        Please enter a valid address.
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label for="validationCustomUsername" class="form-label">Email</label>
                <div class="input-group has-validation">
                    <input type="email" class="form-control" id="validationCustomUsername" name="email" aria-describedby="inputGroupPrepend" required>
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
                <button type="submit" class="btn-custom btn-custom-orange btnSignUp">Sign Up</button>
            </div>
            <p class="text-center have-account textGray" style="font-size: 14px;">Already have an account? <a href="<?= CLIENT_URL ?>login" class="text-decoration-none"> Sign In</a></p>
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