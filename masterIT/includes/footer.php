<!-- FOOTER -->
<footer class="container-fluid text-center textGray mt-5 " style="font-size: 0.9rem;">
    <hr class="footer-separator" style="margin-top: 0;">
    <a class="text-decoration-none textGray" href="<?= CLIENT_URL ?>privacy">Privacy Policy</a> • <a class="text-decoration-none textGray" href="<?= CLIENT_URL ?>refund">Refund Policy</a> • <a class="text-decoration-none textGray" href="<?= CLIENT_URL ?>terms">Terms of Use</a>
    <p style="font-size:0.9rem;">&copy; <?= date(" Y ") ?> Master IT | All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<?php
if (isset($_SESSION['error']) or isset($_SESSION['success'])) {
    $bgColour = isset($_SESSION['error']) ? "danger" : "success";
?>
    <div class="toast-container position-absolute top-0 end-0 p-3 pt-5" style="z-index:1100;margin-top:3rem;">
        <div class="toast align-items-center text-bg-<?= $bgColour ?> border-0 position-relative top-0 end-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?= isset($_SESSION['error']) ? $_SESSION['error'] : $_SESSION['success'] ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl)
        })
        toastList.forEach(toast => toast.show());
    </script>
<?php
    unset($_SESSION['error']);
    unset($_SESSION['success']);
}
?>

<script type="text/javascript" src="assets/js/instantclick.min.js" data-no-instant></script>
<script data-no-instant>
    InstantClick.init();
</script>
</body>

</html>