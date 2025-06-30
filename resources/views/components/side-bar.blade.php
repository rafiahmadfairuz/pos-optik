@php
    $isGudangUtama = !session('cabang_id');
@endphp

<x-header :showToggle="false"></x-header>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="my-3">

                {{-- NAVIGATION --}}
                @if (in_array(Auth::user()->role, ['admin', 'cabang', 'gudang_cabang']) && !$isGudangUtama)
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
                @endif


                {{-- PEMBELIAN & TRANSFER --}}
                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']) && $isGudangUtama)
                    <li class="menu-title fw-bold">PEMBELIAN & TRANSFER</li>

                    <li class="{{ Request::is('transferBarang') ? 'active' : '' }}">
                        <a href="{{ url('transferBarang') }}">
                            <i class="bi bi-box-arrow-right"></i><span> Transfer Barang</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('beliBarang') ? 'active' : '' }}">
                        <a href="{{ url('beliBarang') }}">
                            <i class="bi bi-cart-plus"></i><span> Beli Barang</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('pembelian') ? 'active' : '' }}">
                        <a href="{{ url('pembelian') }}">
                            <i class="bi bi-cart-check"></i><span>List Pembelian</span>
                        </a>
                    </li>
                @endif


                {{-- List Transfer --}}
                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama', 'gudang_cabang']))
                    <li class="{{ Request::is('listTransferBarang') ? 'active' : '' }}">
                        <a href="{{ url('listTransferBarang') }}">
                            <i class="bi bi-cart-check"></i><span>List Transfer</span>
                        </a>
                    </li>
                @endif


                {{-- Supplier --}}
                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']) && $isGudangUtama)
                    <li class="{{ Request::is('supplier') ? 'active' : '' }}">
                        <a href="{{ url('supplier') }}">
                            <i class="bi bi-person-lines-fill"></i><span>Supplier</span>
                        </a>
                    </li>
                @endif


                {{-- FRONT DESK --}}
                @auth
                    @if (in_array(Auth::user()->role, ['admin', 'cabang']) && !$isGudangUtama)
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


                {{-- INVENTORY --}}
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
                <li class="{{ Request::is('accessories') ? 'active' : '' }}">
                    <a href="{{ url('accessories') }}">
                        <i class="bi bi-bag-fill"></i><span> Accessories</span>
                    </a>
                </li>


                {{-- COMPANY --}}
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
