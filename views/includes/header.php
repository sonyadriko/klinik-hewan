<div class="header bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xxl-12">
                <div class="header-content">
                    <div class="header-left">
                        <!-- <div class="brand-logo">
                            <a href="index.html">
                                <img src="../assets/images/logo.png" alt="">
                            </a>
                        </div> -->
                        <!-- <div class="search">
                                    <form action="#">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search Here" />
                                            <span class="input-group-text"><i class="icofont-search"></i></span>
                                        </div>
                                    </form>
                                </div> -->
                    </div>

                    <div class="header-right">
                        <div class="dark-light-toggle" onclick="themeToggle()">
                            <span class="dark"><i class="bi bi-moon"></i></span>
                            <span class="light"><i class="bi bi-brightness-high"></i></span>
                        </div>
                        <!-- <div class="notification dropdown">
                                    <div class="notify-bell" data-toggle="dropdown">
                                        <span><i class="bi bi-bell"></i></span>
                                    </div>
                                    <div class="
                        dropdown-menu dropdown-menu-right
                        notification-list
                      ">
                                        <h4>Announcements</h4>
                                        <div class="lists">
                                            <a href="#" class="">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon success"><i class="bi bi-check"></i></span>
                                                    <div>
                                                        <p>Account created successfully</p>
                                                        <span>2020-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon fail"><i class="bi bi-x"></i></span>
                                                    <div>
                                                        <p>2FA verification failed</p>
                                                        <span>2020-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon success"><i class="bi bi-check"></i></span>
                                                    <div>
                                                        <p>Device confirmation completed</p>
                                                        <span>2020-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3 icon pending"><i
                                                            class="bi bi-exclamation-triangle"></i></span>
                                                    <div>
                                                        <p>Phone verification pending</p>
                                                        <span>2020-11-04 12:00:23</span>
                                                    </div>
                                                </div>
                                            </a>

                                            <a href="../assets/settings-activity.html">More <i
                                                    class="icofont-simple-right"></i></a>
                                        </div>
                                    </div>
                                </div> -->

                        <div class="profile_log dropdown">
                            <div class="user" data-toggle="dropdown">
                                <span class="thumb"><img src="../assets/images/profile/2.png" alt="" /></span>
                                <span class="arrow"><i class="icofont-angle-down"></i></span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="user-email">
                                    <div class="user">
                                        <span class="thumb"><img src="../assets/images/profile/2.png" alt="" /></span>
                                        <div class="user-info">
                                            <h5><?php echo $_SESSION['nama'] ?></h5>
                                            <span><?php echo $_SESSION['email'] ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="user-balance">
                                    <div class="available">
                                        <p>Available</p>
                                        <span>0.00 BTC</span>
                                    </div>
                                    <div class="total">
                                        <p>Total</p>
                                        <span>0.00 USD</span>
                                    </div>
                                </div> -->
                                <a href="profile.html" class="dropdown-item">
                                    <i class="bi bi-person"></i>Profile
                                </a>
                                <!-- <a href="wallet.html" class="dropdown-item">
                                    <i class="bi bi-wallet"></i>Wallet
                                </a>
                                <a href="settings-profile.html" class="dropdown-item">
                                    <i class="bi bi-gear"></i> Setting
                                </a>
                                <a href="settings-activity.html" class="dropdown-item">
                                    <i class="bi bi-clock-history"></i> Activity
                                </a>
                                <a href="lock.html" class="dropdown-item">
                                    <i class="bi bi-lock"></i>Lock
                                </a> -->
                                <a href="logout.php" class="dropdown-item logout">
                                    <i class="bi bi-power"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>