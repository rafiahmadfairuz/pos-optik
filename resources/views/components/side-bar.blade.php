<x-header></x-header>
<div class="sidebar" id="sidebar">
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

                @auth
                    @if (in_array(Auth::user()->role, ['admin', 'cabang']))
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
                    @endif
                @endauth


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
                    <a href="{{ url('accessories') }}">
                        <i class="bi bi-bag-fill"></i><span> Accessories</span>
                    </a>
                </li>

                @auth
                    @if (Auth::user()->role === 'admin')
                        <li class="menu-title fw-bold">COMPANY</li>
                        <li class="{{ Request::is('staff') ? 'active' : '' }}">
                            <a href="{{ url('staff') }}">
                                <img src="{{ asset('assets/img/icons/users1.svg') }}" alt="img">
                                <span>User & Staff</span>
                            </a>
                        </li>
                    @endif
                @endauth


            </ul>
        </div>
    </div>
</div>
