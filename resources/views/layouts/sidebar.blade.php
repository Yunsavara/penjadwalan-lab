<div class="sidebar bg-brown100 shadow py-3 px-4" id="sidebar">
    <div class="brand-logo gap-2 d-flex align-items-center py-1 my-2">
        <img src="{{ asset('images/unpam-logo.png') }}" width="35px" alt="" srcset="">
        <span class="mytext-brown"><b>Penjadwalan Lab</b></span>
    </div>


    <div class="m-4"></div>

    <ul class="menu-container list-unstyled">
        {{-- Kalau selain admin dan laboran akan diarahkan ke dashboard umum --}}
        <li
            class="sidebar-item rounded-3 p-3 mb-2 bg-brown200 {{ Route::is(in_array(auth()->user()->role->name, ['admin', 'laboran']) ? auth()->user()->role->name . '.dashboard' : 'dashboard') ? 'active' : '' }}">
            <a href="{{ route(in_array(auth()->user()->role->name, ['admin', 'laboran']) ? auth()->user()->role->name . '.dashboard' : 'dashboard') }}"
                class="sidebar-link">
                <img src="{{ asset('images/icons/home-black.png') }}" height="20px" class="sidebar-icon-link">Beranda
            </a>
        </li>

        @php
            // List route buat ke dropdown menu manajemen
            $manajemenRoutes = ['admin.pengguna', 'admin.roles'];
        @endphp

        @if (auth()->user()->role->name !== 'admin' && auth()->user()->role->name !== 'laboran')
            <li class="sidebar-item rounded-3 p-3 mb-2 bg-brown200 {{ Route::is('pengajuan*') ? 'active' : '' }}">
                <a href="{{ Route('pengajuan') }}" class="sidebar-link"><img
                        src="{{ asset('images/icons/contract.png') }}" width="20px"
                        class="sidebar-icon-link">Jadwal</a>
            </li>
        @endif



        {{-- Admin atau Laboran --}}
        @if (auth()->user()->role->name === 'admin' || auth()->user()->role->name === 'laboran')
            @php
                $jadwalRoutes = ['laboran.jadwal','laboran.pengajuan*'];
            @endphp

            <li
                class="sidebar-item rounded-3 p-3 mb-2 bg-brown200 @if (Str::is($jadwalRoutes, Route::currentRouteName())) active @endif">
                <a href="" class="sidebar-link d-flex flex-grow collapsed" data-bs-toggle="collapse"
                data-bs-target="#jadwalContainer"><img
                        src="{{ asset('images/icons/contract.png') }}" width="20px"
                        class="sidebar-icon-link">Jadwal
                        <i data-feather="chevron-right"
                        class="dropdown-icon
                        @if (Str::is($jadwalRoutes, Route::currentRouteName())) active @endif
                        "></i>
                    </a>

                    <ul class="collapse list-unstyled dropdown-menu-vanilla @if (Str::is($jadwalRoutes, Route::currentRouteName())) active @endif"
                    id="jadwalContainer">
                    <li class="sidebar-item
                    @if (Str::is('laboran.jadwal*', Route::currentRouteName())) active @endif
                    ">
                        <a href="{{ Route('laboran.jadwal') }}" class="sidebar-link">Penggunaan</a>
                    </li>
                    <li class="sidebar-item
                    @if (Str::is('laboran.pengajuan*', Route::currentRouteName())) active @endif
                    ">
                        <a href="{{ route('laboran.pengajuan') }}" class="sidebar-link">Pengajuan</a>
                    </li>
                </ul>
            </li>

            @php
                $laboratoriumRoutes = ['laboran.jenis-lab*', 'laboran.laboratorium*'];
            @endphp
            <li class="sidebar-item rounded-3 p-3 mb-2 bg-brown200 @if (Str::is($laboratoriumRoutes, Route::currentRouteName())) active @endif">
                <a href="" class="sidebar-link d-flex flex-grow collapsed" data-bs-toggle="collapse"
                    data-bs-target="#laboratoriumContainer">
                    <img src="{{ asset('images/icons/room.png') }}" width="20px"
                        class="sidebar-icon-link">Laboratorium
                    <i data-feather="chevron-right"
                        class="dropdown-icon @if (Str::is($laboratoriumRoutes, Route::currentRouteName())) active @endif"></i>
                </a>

                <ul class="collapse list-unstyled dropdown-menu-vanilla @if (Str::is($laboratoriumRoutes, Route::currentRouteName())) active @endif"
                    id="laboratoriumContainer">
                    <li class="sidebar-item @if (Str::is('laboran.jenis-lab*', Route::currentRouteName())) active @endif">
                        <a href="{{ route('laboran.jenis-lab') }}" class="sidebar-link">Jenis</a>
                    </li>
                    <li class="sidebar-item @if (Str::is('laboran.laboratorium*', Route::currentRouteName())) active @endif">
                        <a href="{{ route('laboran.laboratorium') }}" class="sidebar-link">Laboratorium</a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item rounded-3 p-3 mb-2 bg-brown200 {{ Route::is('admin.barang*') ? 'active' : '' }}">
                <a href="{{ Route('admin.barang') }}" class="sidebar-link"><img
                        src="{{ asset('images/icons/pc.png') }}" width="20px" class="sidebar-icon-link">Barang</a>
            </li>
        @endif

        @if (auth()->user()->role->name === 'admin')
            <li class="sidebar-item rounded-3 p-3 mb-2 bg-brown200 @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif">
                <a href="" class="sidebar-link d-flex flex-grow collapsed" data-bs-toggle="collapse"
                    data-bs-target="#manajemenDropdown">
                    <img src="{{ asset('images/icons/settings-black.png') }}" height="20px"
                        class="sidebar-icon-link">Manajemen
                    <i data-feather="chevron-right"
                        class="dropdown-icon @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif"></i>
                </a>

                <ul class="collapse list-unstyled dropdown-menu-vanilla @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif"
                    id="manajemenDropdown">
                    <li class="sidebar-item @if (Route::currentRouteName() == 'admin.pengguna') active @endif">
                        <a href="{{ route('admin.pengguna') }}" class="sidebar-link">Pengguna</a>
                    </li>
                    <li class="sidebar-item @if (Route::currentRouteName() == 'admin.roles') active @endif">
                        <a href="{{ route('admin.roles') }}" class="sidebar-link">Roles</a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>

</div>
