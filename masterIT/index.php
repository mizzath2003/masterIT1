<?php
session_start();
define("PAGE_TITLE", "Home");
require "dbh/dbdata.php";
require "includes/header.php";
include_once "components/navbar.php";
include_once "components/classCard.php";

?>

<style>
    .section-community {
        font-family: "Archivo", sans-serif;
        border-radius: 50px 50px 0 0;
        padding-bottom: 70px;
        padding-top: 70px;
        margin-top: 50px;

    }

    .community-content {
        background-image: url(assets/images/join-community.webp);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin: 50px 0 50px 0;
        height: 100vh;
        border-radius: 30px;
    }



    .community-content h1 {
        justify-content: center;
        /* margin-bottom: 50px; */
        padding-top: 100px;
        font-size: 64px;
        font-weight: 700;
        margin-bottom: 60px;
    }

    .social-media a {
        color: hsl(223.64deg 13.92% 15.49%);
        background-color: white;
        font-size: 1.3rem;
        margin-right: 24px;
        padding: 10px 10px;
        border-radius: 50%;
    }

    .social-media a:hover {
        color: var(--btnBlue);
    }



    @media (max-width: 768px) {
        .section-community {
            margin-top: 40px;
            padding-top: 55px;
            padding-bottom: 35px;
            height: 100vh;
        }

        .community-content {
            border-radius: 15px;
            height: 70vh;
        }

        .community-content h1 {
            padding-top: 50px;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 50px;
        }

        .social-media a {
            font-size: 1.3rem;
            margin-right: 18px;
            padding: 9px 9px;
            border-radius: 50%;
        }

    }
</style>

<!-- HERO SECTION -->
<section class="hero-section container-xl row mx-auto">
    <div class="col-md p-0 pt-md-5 mt-5">
        <h1 class="mt-lg-5 pt-lg-3"><span style="color: var(--textPurple);">Ignite</span> the journey<br>of learning with <br><span style="color: var(--textBlue);">Master IT</span></h1>
        <p>~ Learn ICT with the geek of IT</p>
        <?php
        if (!isset($_SESSION['email'])) {
        ?>
            <button type="button" onclick="window.location.href = '<?= CLIENT_URL ?>register'" class="btn-custom btn-custom-orange">Sign Up</button>
            <button type="button" onclick="window.location.href = '<?= CLIENT_URL ?>login'" class="btn-custom btn-custom-outline-orange">Sign In</button>
        <?php
        } else {
        ?>
            <a href="#classesSection" class="btn-custom btn-custom-orange p-3" style="top:15px">Explore More</a>
        <?php
        }
        ?>

    </div>

    <div class="col-md p-0 mt-4">
        <img class="position-md-absolute" src="assets/images/heroIMG.webp" alt="heroImage">
    </div>
</section>
<!-- END OF HERO SECTION -->

<!-- CLASS SECTION -->
<section class="container-fluid mx-auto bg-blue-2  section-class" id="classesSection">
    <div class="container-xl row mx-auto">
        <div class="class-heading">
            <h1>Classes</h1>
        </div>
        <?php
        $sql = "SELECT `ID`, `Name`, `Image`, `curriculam` FROM `classes` WHERE `Status`='1'";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $encodedURL =  CLIENT_URL . 'lessons/' . str_replace(['%', '='], ['-', '--'], urlencode(str_rot13(base64_encode($row['ID']))));
                classCard($row['Image'], $row['curriculam'], $row['Name'], $encodedURL);
            }
        }
        ?>
    </div>
</section>
<!-- END OF SECTION CLASS -->

<!-- SECTION QUOTE -->
<section class="container-xl row mx-auto mt-5 section-quote">
    <div class="col-md-7">
        <h1>"It always <br> seems impossible <br> until it's done."</h1>
        <p>- Nelson Mandela</p>
    </div>

    <div class="col-md-5 pt-5 mt-5">
        <img src="assets/images/image-quote.webp" alt="quote image">
    </div>

</section>
<!-- END OF QUOTE SECTION -->

<!-- SECTION COMMUNITY -->
<section class="container-fluid mx-auto bg-blue-2 section-community ">
    <div class="container-xl mx-auto community-content text-center">
        <h1 class="row mx-auto">Join our community</h1>
        <div class="col mx-auto social-media">
            <a href="" class="text-decoration-none"><i class="ri-whatsapp-fill"></i></i></a>
            <a href="" class="text-decoration-none"><i class="bi bi-twitter"></i></a>
            <a href="" class="text-decoration-none"><i class="ri-instagram-fill"></i></a>
            <a href="" class="text-decoration-none"><i class="bi bi-telegram"></i></a>
            <a href="" class="text-decoration-none"><i class="ri-facebook-fill"></i></a>
        </div>
    </div>

</section>




<?php
require "includes/footer.php";
?>