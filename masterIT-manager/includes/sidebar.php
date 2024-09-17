<div class="sidebar">
    <div class="logo-details">
        <div class="logo_name">
        </div>
        <i class='bx bx-menu' onclick="closeSidebar()" id="btn"></i>
    </div>
    <ul id="nav" class="nav-list">
        <li>
            <a href="<?= $websiteURLFull ?>">
                <i class='bx bx-grid-alt'></i>
                <span class="links_name">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>
        <li>
            <a href="<?= $websiteURLFull ?>students">
                <i class='bx bx-user'></i>
                <span class="links_name">Students</span>
            </a>
            <span class="tooltip">Students</span>
        </li>
        <li>
            <a href="<?= $websiteURLFull ?>classes">
                <i class='bx bx-chalkboard'></i>
                <span class="links_name">Classes</span>
            </a>
            <span class="tooltip">Classes</span>
        </li>
        <li>
            <a href="<?= $websiteURLFull ?>tutes">
                <i class='bx bx-file'></i>
                <span class="links_name">Tutes</span>
            </a>
            <span class="tooltip">Tutes</span>
        </li>
        <li>
            <a href="<?= $websiteURLFull ?>invoices">
                <i class='bx bx-wallet'></i>
                <span class="links_name">Invoices</span>
            </a>
            <span class="tooltip">Invoices</span>
        </li>
        <!-- <li>
            <a href="<?= $websiteURLFull ?>admins">
                <i class='bx bx-cog'></i>
                <span class="links_name">Settings</span>
            </a>
            <span class="tooltip">Settings</span>
        </li> -->
        <li class="profile">
            <div class="profile-details">
                <div class="name_job">
                    <div class="name"><?= $_SESSION['name'] ?></div>
                </div>
                <a data-no-instant href="<?= $websiteURLFull ?>dbh/logout">
                    <i class='bx bx-log-out' id="log_out"></i>
                    <span class="tooltip">Logout</span>
                </a>
            </div>
        </li>
    </ul>
</div>