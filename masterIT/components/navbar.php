<?php
include_once "components/cart.php";
function checkActive($item1, $item2, $return = "active")
{
    return ($item1 == $item2) ? $return : "";
}
?>
<style>
    .navbar a {
        color: var(--textGray) !important;
        padding-left: 10px;
    }

    .navbar-nav .nav-link {
        border-radius: 8px;
        font-size: 14px !important;
    }

    .navbar-nav .nav-link.active,
    .navbar-nav .nav-link.show a {
        color: var(--bgMain) !important;
        background-color: white;
    }

    .navbar-nav .nav-link:not(.active):hover {
        filter: brightness(150%);
        font-weight: 700;
    }


    .profile-pic {
        width: 50px;
        height: 50px;
        object-fit: cover;
        object-position: 50% 50%;
        border-radius: 50%;
        margin-left: 1rem;
        margin-bottom: 0.6rem;
    }
</style>
<section style="background-color: var(--bgMain)!important;" class="fixed-top">
    <nav class="container-xl navbar navbar-expand-lg  navbar-dark mt-4">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <!-- <span class="navbar-toggler-icon"></span> -->
                <img src="assets/images/menuIcon.svg" alt="menu" width="22" height="22">
            </button>
            <a class="navbar-brand d-none d-sm-inline" href="#">
                <img src="assets/images/Logo.png" alt="" style="max-height:50px">
            </a>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" style="background-color: var(--bgMain) !important;">
                <div class="offcanvas-header mt-3">
                    <a class="navbar-brand d-lg-none" href="<?= CLIENT_URL ?>home">
                        <img src="assets/images/Logo.png" alt="" style="max-height:80px;">
                    </a>
                    <button type="button" class="btn-close btn-close-white ms-auto px-3" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 g-3">
                        <li class="nav-item px-3">
                            <a class="nav-link <?= checkActive(PAGE_TITLE, "Home") ?>" href="<?= CLIENT_URL ?>home">Home</a>
                        </li>
                        <li class="nav-item px-3">
                            <a class="nav-link <?= checkActive(PAGE_TITLE, "Classes") ?>" href="<?= CLIENT_URL ?>classes">Classes</a>
                        </li>
                        <li class="nav-item px-3">
                            <a class="nav-link <?= checkActive(PAGE_TITLE, "Resources") ?>" href="<?= CLIENT_URL ?>resources">Resources</a>
                        </li>
                        <?php
                        if (isset($_SESSION['email'])) {
                        ?>

                            <li class="nav-item px-3">
                                <a class="nav-link <?= checkActive(PAGE_TITLE, "My Schedule") ?>" href="<?= CLIENT_URL ?>schedule">My Schedule</a>
                            </li>
                            <li class="nav-item px-3">
                                <a class="nav-link <?= checkActive(PAGE_TITLE, "Profile") ?>" href="<?= CLIENT_URL ?>profile">Profile</a>
                            </li>
                            <li class="nav-item px-3">
                                <a class="nav-link" href="<?= CLIENT_URL ?>logout" data-no-instant>Logout</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="ms-auto">
                <button id="cartBtn" class="border-0 text-decoration-none btn-custom-profile btn-custom2" data-bs-toggle="offcanvas" <?= (!empty($_SESSION['cart'])) ? 'href="#cartCanvas"' : '' ?> role="button" aria-controls="offcanvasRight">
                    <i class="bi bi-cart-dash-fill fs-2 textWhite"></i>
                    <?php if (!empty($_SESSION['cart'])) {
                    ?>
                        <span class="py-1 mt-2 badge position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                            <span><?= count($_SESSION['cart']) ?></span>
                        </span>
                    <?php
                    } ?>
                </button>
                <a class="text-decoration-none" href="<?= (isset($_SESSION['email'])) ? CLIENT_URL . 'profile' : CLIENT_URL . 'login' ?>">
                    <img src="<?= !empty($profileImg) ? CLIENT_URL .  $profileImg : CLIENT_URL . "assets/images/pfp/20c0cdc13eab3e8cac68c6ddb7d99673.png" ?>" alt="profile-icon" class="profile-pic btn-custom-profile btn-custom2">
                </a>
            </div>
        </div>
    </nav>
</section>