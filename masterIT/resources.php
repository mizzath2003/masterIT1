<?php
session_start();
define("PAGE_TITLE", "Resources");
require "dbh/dbdata.php";
require "includes/header.php";
include_once "components/navbar.php";
include_once "components/topBar.php";
include_once "components/resourceList.php";

function displayResources()
{
    global $con;
    $sql = "SELECT `Name`, `Link` FROM `resources` WHERE `Status`='1'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            resourceList($row['Name'], $row['Link']);
        }
    }
}
?>

<!-- RESOURCES SECTION -->
<section class="main-section container-xl mx-auto mb-5">
    <!-- TopBar -->
    <?= topBar("Resources") ?>
    <div class="container-xl row mx-auto resources align-items-center justify-content-center">
        <?= displayResources() ?>
    </div>
</section>
<!-- END OF SECTION RESOURCES -->

<?php
require "includes/footer.php";
?>