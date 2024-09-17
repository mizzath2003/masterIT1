<?php
function lessonCard($lessonID, $thumbnailLink, $className, $price, $lessonName, $date, $time, $status, $joinURL = '#', $thumbnailColour = "#30b28c")
{
    if ($status == "1") {
        $lessonText = "Pending Approval";
        $onClick = "";
    } else if ($status == "2") {
        $lessonText = "View Lesson";
        //Encoding the URL for security purpose
        $encodedURL =  CLIENT_URL . 'lesson/overview/' . str_replace(['%', '='], ['-', '--'], urlencode(str_rot13(base64_encode($lessonID))));
        $onClick = "location.href='$encodedURL'";
    } else if ($status == "3") {
        $lessonText = "Join Meeting";
        $onClick = "location.href='$joinURL'";
    } else if ($status == "4") {
        $lessonText = "View Cart";
        $onClick = "document.getElementById('cartBtn').click()";
    } else {
        $lessonText = "Add To Cart";
        $onClick = "addToCart('$lessonID')";
    }
?>
    <div class="lesson-card p-4 inputColor rounded-4">
        <div class="lessonImg">
            <img src="<?= $thumbnailLink ?>" alt="card-image-1" class="img-fluid rounded-4">
        </div>

        <div class="d-flex mt-2 lesson-card-text">
            <h5 class="flex-fill mt-1 ClassName"><?= $className ?></h5>
            <span class="flex-fill text-end" style="color:<?= $thumbnailColour ?>">LKR <?= $price ?></span>
        </div>
        <h4 class=" text-start" style="margin-bottom: 12px;"><?= $lessonName ?></h4>
        <div class="d-flex flex-wrap gap-3 lesson-content">
            <p class="flex-fill textGray mb-0 lh-1"><?= $date ?></br><span class="smallFont">Date</span></p>
            <p class="flex-fill textGray mb-0 lh-1"><?= $time ?></br><span class="smallFont">Time</span></p>
            <a id="<?= $lessonID ?>" class="btn-custom btn-custom-orange w-100"  onclick="<?= $onClick ?>"><?= $lessonText ?></a>
        </div>
    </div>
<?php
}
?>