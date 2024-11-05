<div class="sidebar">
    <div class="brand-logo">
        <a href="dashboard.php">
            <img src="../assets/images/logo.jpg" alt="Logo" width="30" />
        </a>
    </div>
    <div class="menu">
        <ul>
            <li>
                <a href="dashboard.php" data-toggle="tooltip" data-placement="right" title="Home">
                    <span><i class="bi bi-house"></i></span><br>
                    <span>Home</span>
                </a>
            </li>

            <?php if ($_SESSION['role'] == 'admin') { ?>
                <li>
                    <a href="artikel.php" data-toggle="tooltip" data-placement="right" title="Artikel">
                        <span><i class="bi bi-globe"></i></span><br>
                        <span>Artikel</span>
                    </a>
                </li>
                <li>
                    <a href="akun-pasien.php" data-toggle="tooltip" data-placement="right" title="Akun Pasien">
                        <span><i class="bi bi-person-circle"></i></span><br>
                        <span>Akun Pemilik Hewan</span>
                    </a>
                </li>
            <?php } ?>

            <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'pasien') { ?>
                <li>
                    <a href="reservasi.php" data-toggle="tooltip" data-placement="right" title="Reservasi">
                        <span><i class="bi bi-calendar-check"></i></span><br>
                        <span>Reservasi</span>
                    </a>
                </li>
            <?php } ?>

            <?php if ($_SESSION['role'] == 'dokter') { ?>
                <li>
                    <a href="rekam-medis.php" data-toggle="tooltip" data-placement="right" title="Pemeriksaan">
                        <span><i class="bi bi-file-earmark-medical"></i></span><br>
                        <span>Pemeriksaan</span>
                    </a>
                </li>
                <li>
                    <a href="rekam-medis-grooming.php" data-toggle="tooltip" data-placement="right" title="Grooming">
                        <span><i class="bi bi-file-earmark-medical"></i></span><br>
                        <span>Grooming</span>
                    </a>
                </li>
                <li>
                    <a href="rekam-medis-pethotel.php" data-toggle="tooltip" data-placement="right" title="Pet Hotel">
                        <span><i class="bi bi-file-earmark-medical"></i></span><br>
                        <span>Pet Hotel</span>
                    </a>
                </li>
            <?php } ?>

            <?php if ($_SESSION['role'] == 'pasien') { ?>
                <li>
                    <a href="profile.php" data-toggle="tooltip" data-placement="right" title="Profile">
                        <span><i class="bi bi-person-circle"></i></span><br>
                        <span>Profile</span>
                    </a>
                </li>
            <?php } ?>

            <?php if ($_SESSION['role'] == 'dokter' || $_SESSION['role'] == 'pasien') { ?>
                <li>
                    <a href="forum.php" data-toggle="tooltip" data-placement="right" title="Forum Diskusi">
                        <span><i class="bi bi-chat-left-text"></i></span><br>
                        <span>Forum Diskusi</span>
                    </a>
                </li>
            <?php } ?>

            <li>
                <a href="logout.php" data-toggle="tooltip" data-placement="right" title="Signout">
                    <span><i class="bi bi-box-arrow-right"></i></span><br>
                    <span>Signout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
