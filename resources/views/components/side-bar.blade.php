<div class="header">

    <div class="header-left active">
        <a href="index.html" class="logo">
            <img src="assets/img/logo.png" alt="">
        </a>
        <a href="index.html" class="logo-small">
            <img src="assets/img/logo-small.png" alt="">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
        </a>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <!-- user menu -->
    <ul class="nav user-menu">


        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-img"><img src="assets/img/profiles/avator1.jpg" alt="">
                    <span class="status online"></span></span>
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
                    <a class="dropdown-item" href="profile.html"> <i class="me-2" data-feather="user"></i> My
                        Profile</a>
                    <a class="dropdown-item" href="generalsettings.html"><i class="me-2"
                            data-feather="settings"></i>Settings</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="signin.html"><img src="assets/img/icons/log-out.svg"
                            class="me-2" alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>


    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="profile.html">My Profile</a>
            <a class="dropdown-item" href="generalsettings.html">Settings</a>
            <a class="dropdown-item" href="signin.html">Logout</a>
        </div>
    </div>

</div>

<div class="sidebar " id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="my-3">

                <li class="menu-title fw-bold">NAVIGATION</li>
                <li class="{{ Request::is('/') ? 'active' : '' }}">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/icons/dashboard.svg') }}" alt="img">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ Request::is('report') ? 'active' : '' }}">
                    <a href="{{ url('report') }}">
                        <img src="{{ asset('assets/img/icons/time.svg') }}" alt="img">
                        <span>Report</span>
                    </a>
                </li>

                <!-- FRONT DESK Section -->
                <li class="menu-title fw-bold">FRONT DESK</li>
                <li class="{{ Request::is('kasir') ? 'active' : '' }}">
                    <a href="{{ url('kasir') }}">
                        <img src="{{ asset('assets/img/icons/sales1.svg') }}" alt="img">
                        <span>Kasir</span>
                    </a>
                </li>
                <li class="{{ Request::is('customer') ? 'active' : '' }}">
                    <a href="{{ url('customer') }}">
                        <img src="{{ asset('assets/img/icons/users1.svg') }}" alt="img">
                        <span>Customer</span>
                    </a>
                </li>
                <li class="{{ Request::is('orderan') ? 'active' : '' }}">
                    <a href="{{ url('orderan') }}">
                        <img src="{{ asset('assets/img/icons/transfer1.svg') }}" alt="img">
                        <span>Orderan</span>
                    </a>
                </li>
                <li class="{{ Request::is('asuransi') ? 'active' : '' }}">
                    <a href="{{ url('asuransi') }}">
                        <img src="{{ asset('assets/img/icons/purchase1.svg') }}" alt="img">
                        <span>Asuransi</span>
                    </a>
                </li>

                <!-- INVENTORY Section -->
                <li class="menu-title fw-bold">INVENTORY</li>
                <li class="{{ Request::is('frame') ? 'active' : '' }}">
                    <a href="{{ url('frame') }}">
                        <i class="bi bi-eyeglasses"></i><span> Frame</span>
                    </a>
                </li>
                <li class="{{ Request::is('lensaFinish') ? 'active' : '' }}">
                    <a href="{{ url('lensaFinish') }}">
                        <i class="bi bi-circle"></i><span> Lensa Finish</span>
                    </a>
                </li>
                <li class="{{ Request::is('lensaKhusus') ? 'active' : '' }}">
                    <a href="{{ url('lensaKhusus') }}">
                        <i class="bi bi-record-circle"></i><span> Lensa Khusus</span>
                    </a>
                </li>
                <li class="{{ Request::is('softlens') ? 'active' : '' }}">
                    <a href="{{ url('softlens') }}">
                        <i class="bi bi-eye-fill"></i><span> Softlens</span>
                    </a>
                </li>
                <li class="{{ Request::is('accesories') ? 'active' : '' }}">
                    <a href="{{ url('accesories') }}">
                        <i class="bi bi-bag-fill"></i><span> Accessories</span>
                    </a>
                </li>

                <!-- COMPANY Section -->
                <li class="menu-title fw-bold">COMPANY</li>
                <li class="{{ Request::is('user-staff') ? 'active' : '' }}">
                    <a href="{{ url('user-staff') }}">
                        <img src="{{ asset('assets/img/icons/users1.svg') }}" alt="img">
                        <span>User & Staff</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
