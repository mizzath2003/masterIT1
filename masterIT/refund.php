<?php
session_start();
define("PAGE_TITLE", "Refund Policy");
require "dbh/dbdata.php";
require "includes/header.php";
include_once "components/navbar.php";
include_once "components/topBar.php";
?>

<!-- TERMS AND CONDITIONS SECTION -->
<section class="main-section container-xl mx-auto mb-5">
    <!-- TopBar -->
    <?= topBar("Refund Policy") ?>
    <div class="container-xl resources text-start">
        <h2 id="refund-policy">Refund Policy</h2>
        <p>Thank you for shopping at masterit.lk. We value your satisfaction and strive to provide you with the best online shopping experience possible. If, for any reason, you are not completely satisfied with your purchase, we are here to help.</p>
        <p><strong>Returns</strong></p>
        <p>We don't accept returns </p>
        <p><strong>Refunds</strong></p>
        <p>Payments made are non refundable. Suppose a student is being expelled or due to any considerable reasons have been stopped from our classes from our end then a refund would be made.</p>
        <p><strong>Exchanges</strong></p>
        <p>If you would like to exchange your payment for a different class/lesson please contact our customer support team within 2 days of placing your order. We will provide you with further instructions on how to proceed with the exchange.</p>
        <p><strong>Contact Us</strong></p>
        <p>If you have any questions or concerns regarding our refund policy, please contact our customer support team - 0772779798. We are here to assist you and ensure your shopping experience with us is enjoyable and hassle-free.</p>
    </div>
</section>
<!-- END OF SECTION TERMS AND CONDITIONS -->

<?php
require "includes/footer.php";
?>