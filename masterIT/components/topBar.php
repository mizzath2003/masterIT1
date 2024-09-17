<?php
function topBar($HeaderName)
{
?>

    <div class="row class-heading mt-5 mb-lg-5 py-4 d-flex align-items-center justify-content-center">
        <div class="d-none d-lg-block col-3 px-4">
            <img class="img-fluid" src="assets/images/rocket.webp" alt="rocket">
        </div>
        <h1 class="col mx-auto"><?= $HeaderName ?></h1>
        <div class="d-none d-lg-block col-3 px-4">
            <img class="img-fluid" src="assets/images/ufo-5.webp" alt="rocket">
        </div>
    </div>

<?php
}
