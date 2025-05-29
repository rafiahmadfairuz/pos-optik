<div class="header">
    <div class="header-left active">
        <a href="index.html" class="logo">
            <img src="assets/img/logo.png" alt="">
        </a>
        <a href="index.html" class="logo-small">
            <img src="assets/img/logo-small.png" alt="">
        </a>

        @if ($showToggle)
            <a id="toggle_btn" href="javascript:void(0);"></a>
        @endif
    </div>


    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <ul class="nav user-menu ">
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-img"><img src="assets/img/profiles/avator1.jpg" alt="">
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img"><img src="assets/img/profiles/avator1.jpg" alt="">
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>John Doe</h6>
                            <h5>Admin</h5>
                        </div>
                    </div>
                    <hr class="m-0">

                    <a class="dropdown-item logout pb-0" href="signin.html"><img src="assets/img/icons/log-out.svg"
                            class="me-2" alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>
</div>
