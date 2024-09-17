<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?= $websiteURLFull ?>includes/jquery.table2excel.js?v2"></script>
<script type="text/javascript" src="<?= $websiteURLFull ?>includes/jquery.toast.min.js"></script>
<script type="text/javascript" src="<?= $websiteURLFull ?>includes/script.js"></script>
<script type="text/javascript" src="<?= $websiteURLFull ?>includes/instantclick.min.js" data-no-instant></script>
<script data-no-instant>
    InstantClick.init();
</script>
<section id="statusToast">
    <?php
    if (isset($_SESSION['status'])) {
    ?>
        <div class="toast-container position-absolute top-0 end-0 p-3 ms-sm-3 noPrint" style="z-index:1100;">
            <div class="toast bg-white text-dark show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
                <div class="toast-header">
                    <strong class="me-auto">Master IT</strong>
                    <small class="text-muted">just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <?php echo $_SESSION['status']; ?>
                </div>
            </div>
        </div>
        <script id="statusToastscript">
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl, 'show')
            })
            setTimeout(function() {
                $('.toast').fadeOut('fast');
            }, 5000);
        </script>
    <?php
        unset($_SESSION['status']);
    }
    ?>
</section>

</body>

</html>