<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-right mb-0">
            <li class="dropdown notification-list">
                <!-- Mobile menu toggle-->
                <a class="navbar-toggle nav-link">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
            </li>
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="../assets/images/users/user-1.jpg" alt="user-image" class="rounded-circle">

                    <span class="pro-user-name ml-1">
                        <?= $user_fullname; ?> <i class="mdi mdi-chevron-down"></i> 
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings"></i>
                        <span>Settings</span>
                    </a>
                    
                    <div class="dropdown-divider"></div> -->
                    
                    <a href="javascript:void(0);" class="dropdown-item notify-item" id="logout">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
            <!-- <li class="dropdown notification-list">
                <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect">
                    <i class="fe-settings noti-icon"></i>
                </a>
            </li> -->
        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="dashboard.php" class="logo text-center">
                <span class="logo-lg">
                    <span class="logo-lg-text-dark"><img src="../assets/images/rrm/rrmsteel-logo.png" alt="" height="40"></span>
                </span>
                <span class="logo-sm">
                    <span class="logo-sm-text-dark"><img src="../assets/images/rrm/rrmsteel-logo.png" alt="" height="28"></span>
                </span>
            </a>
        </div>

        <div class="clearfix"></div>
    </div>
</div>