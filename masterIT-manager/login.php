<?php
session_start();
if (isset($_SESSION['name'])) {
    header('Location:/');
}
$pgname = 'Login';
include('dbh/dbdata.php');
include('includes/header.php');
?>
<section class="login-section">
    <div class="section-body mx-3">
        <div class="col-md-4 mx-auto">
            <div class="col-8 mx-auto">
                <img src="<?= $websiteURLFull ?>images/logo.png" class="img-fluid logo-img" alt="logo">
            </div>
            <div class="text text-xx-large text-center my-5">
                Admin Login
            </div>
            <form method="POST" action="dbh/loginuser">
                <div>
                    <label class="mb-0 mt-3 center-head">Email</label>
                    <input class="form-control searchbar shadow-none" type="text" name="email" placeholder="Enter email address" required>
                </div>
                <div>
                    <label class="mb-0 mt-3 center-head">Password</label>
                    <input class="form-control searchbar shadow-none" type="password" name="pwd" placeholder="Enter password" required>
                </div>
                <div class="text-center mt-5">
                    <button type="submit" name="login_btn" class="btn-main px-5">Login</button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php
include('includes/footer.php');
?>