<?php
function resourceList($resourceName, $resourceLink)
{
?>
    <div class="resources-list col-lg-7 row align-items-center position-relative">
        <p class="col text-start"><?= $resourceName ?></p>
        <a class="col-auto text-end stretched-link" href="<?= $resourceLink ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i></a>
    </div>
<?php
}
?>