<?php
function scheduleLesson($duration, $lessonName, $className, $lessonID)
{
    $encodedURL =  CLIENT_URL . 'lesson/overview/' . str_replace(['%', '='], ['-', '--'], urlencode(str_rot13(base64_encode($lessonID))));
?>

    <div class="d-flex mb-2">
        <div class="me-3" style="background-color:hsl(46.67deg 85.07% 60.59%); padding: 3px; border-radius: 10px; "></div>
        <div class="flex-fill justify-content-between  text-start schedule-section-info">
            <p class="smalltextColor"><?= $duration ?></p>
            <h4 class="my-1"><?= $lessonName ?></h4>
            <span style="color: #5fa9bc;"><?= $className ?></span>
        </div>
        <div class="schedule-section-button mt-3">
            <a href="<?= $encodedURL ?>" class="btn-custom btn-custom-outline-yellow">View</a>
        </div>
    </div>
<?php
}
