<?php
session_start();
require "dbh/dbdata.php";
requireLogin("no", "./");
define("PAGE_TITLE", "Login");
require "includes/header.php";
require "components/navbar.php";
?>

<style>
    .login-form h1 {
        font-size: 45px;
        font-weight: 700;
    }

    .form-input {
        font-weight: 500;
        background-color: #31343f;
        max-width: 100%;
        border-radius: 12px;
        padding: 12px 0;
    }

    .form-input:focus-within {
        border: 1.7px solid #2d73ff8c;
    }

    .form-input input {
        color: white;
        background-color: #31343f;
        border: none;
    }

    .form-input input:focus {
        background: transparent;
        outline: none;
        color: white;
        font-size: 16px;
        font-weight: 500;
    }

    .form-extra button {
        padding: 15px 122px;
    }

    .form-extra .forgot-password {
        font-size: 13px;
        margin-left: -180px;
    }

    .form-extra .forgot-password a {
        color: var(--textGray);
    }

    .form-extra .forgot-password a:hover {
        color: #2d73ff;
    }

    .form-extra .dont-have-account a {
        color: var(--btnOrange);
    }

    .form-extra .dont-have-account a:hover {
        color: #2d73ff;
    }

    ::-webkit-input-placeholder {
        color: var(--textGray);
        font-family: Inter, sans-serif;
        font-size: 16px;
        font-weight: 500;
    }

    @media (min-width: 755px) {
        .form-input {
            max-width: 400px;
        }

        .form-extra button {
            padding: 15px 146px;
        }

        .form-extra .forgot-password {
            margin-left: -220px;
        }

    }
</style>

<!-- Login Form -->
<section class="main-section container-xl mx-auto mb-5">
    <div class="row d-flex align-items-center justify-content-center login-form">
        <form action="<?= CLIENT_URL ?>student/login" method="POST" id="loginForm">
            <h1 class="col mx-auto mt-5 pt-md-5 mb-4 text-center">Sign In</h1>
            <div class=" col mx-auto text-center">
                <div class="form-input mx-auto textGray text-start px-3 ">
                    <i class="ri-user-line fs-5"></i>
                    <input type="text" class="col" id="username" name="email" placeholder="Email" required>
                </div>
                <div class="form-input mx-auto mt-3  textGray text-start px-3 ">
                    <i class="ri-lock-2-line fs-5"></i>
                    <input type="password" class="col " id="username" name="password" placeholder="Password" required>
                </div>
                <div class="mx-auto   form-extra textGray text-center">
                    <p class="Error" style="margin-top: 4px; margin-bottom: -4px; color: hsl(0deg 49.8% 51.96%) !important;visibility:hidden">Invalid username or password</p>
                    <p class="mt-2 forgot-password"><a href="" class="text-decoration-none">Forgot Password?</a> </p>
                    <button type="submit" class="btn-custom btn-custom-orange mb-1">Sign In</button>
                    <p class="mt-2 dont-have-account" style="font-size: 14px;">Don't have an account? <a href="<?= CLIENT_URL ?>register" class="text-decoration-none">Sign Up</a> </p>
                </div>
            </div>
        </form>
    </div>
</section>
<!-- END OF Login Form -->

<script>
    const form = document.getElementById("loginForm");
    const inputs = form.querySelectorAll("input");

    // Add event listeners to each input field
    inputs.forEach((input, index) => {
        input.addEventListener("keydown", (event) => {
            if (event.key === "Enter" || event.keyCode === 13) {
                event.preventDefault(); // Prevent form submission
                const nextIndex = index + 1;
                if (nextIndex < inputs.length) {
                    inputs[nextIndex].focus(); // Focus on the next input
                }
            }
        });
    });
</script>


<?php
require "includes/footer.php";
?>