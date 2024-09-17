<?php
session_start();
define("PAGE_TITLE", "Classes");
require "dbh/dbdata.php";
require "includes/header.php";
include_once "components/navbar.php";
include_once "components/classCard.php";
include_once "components/topBar.php";
?>

<style>

</style>

<!-- CLASS SECTION -->
<section class="container-xl mx-auto row">
    <?php
    //TopBar
    topBar("Classes");

    $sql = "SELECT `ID`, `Name`, `Image`, `curriculam` FROM `classes` WHERE `Status`='1'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $encodedURL =  CLIENT_URL . 'lessons/' . str_replace(['%', '='], ['-', '--'], urlencode(str_rot13(base64_encode($row['ID']))));
            classCard($row['Image'], $row['curriculam'], $row['Name'], $encodedURL);
        }
    }
    ?>
</section>
<!-- END OF SECTION CLASS -->

<?php
require "includes/footer.php";
?>