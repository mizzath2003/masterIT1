<?php
function displayOrderLesson($listItemName, $discountedPrice, $lessonPrice)
{
    echo $listItemName;
    if ($discountedPrice != $lessonPrice) {
?>
        <span class="d-md-inline d-block">- <del>Rs <?= $lessonPrice ?></del></span>
        <span class="d-md-inline d-block">Rs <?= $discountedPrice ?></span><br>
    <?php
    } else { ?>
        <span class="textGray fs-6">LKR <?= $lessonPrice ?></span><br>
<?php }
}
