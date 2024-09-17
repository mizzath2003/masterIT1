<?php
function classCard($imgPath, $curriculam, $className, $redirectLink)
{
?>
    <style>
        .class-card img {
            filter: brightness(var(--filterIMG, 100%));
            transition: filter 0.3s;
        }

        .class-card:hover img,
        .btn-view:hover {
            --filterIMG: 50%;
        }

        .btn-view {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            /* transition: opacity 0.3s, color 0.3s, background-color 0.3s; */
        }

        .class-card:hover .btn-view {
            opacity: 1;
        }
    </style>

    <div class="col-sm-6 col-md-4 g-col-lg-3">
        <a class="text-decoration-none textWhite" href="<?= $redirectLink ?>">
            <div class="class-card position-relative">
                <img src="<?= MANAGER_URL . $imgPath ?>" alt="card-image-1">
                <span><?= $curriculam ?></span>
                <p><?= $className ?></p>
                <div class="overlay"></div>
                <a href="<?= $redirectLink ?>" class="btn-custom btn-custom-outline-white btn-view fw-bold">View Class</a>
            </div>
        </a>
    </div>

<?php
}
?>