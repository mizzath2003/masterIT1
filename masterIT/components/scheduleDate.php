<?php
function scheduleDate($currentDate, $monthYear)
{
?>


    <div class="schedule-section-date mt-3" style="max-width: fit-content;">
        <p class="mt-2 mb-4"><span class="me-2 textbgMain fs-6 rounded-circle"><?= str_pad($currentDate, 2, "0", STR_PAD_LEFT) ?></span><?= $monthYear ?></p>
    </div>
<?php
}
