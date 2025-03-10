<div class="sidebar shadow py-3 px-4" id="sidebar">
    <div class="brand-logo gap-2 d-flex justify-content-center py-1">
        <i data-feather="activity" id="brandIcon"></i>
        <span>Penjadwalan Lab</span>
    </div>
    <hr>

    <ul class="menu-container list-unstyled">
        <span class="divider" style="font-size:0.75rem;">MENU</span>
        <li class="sidebar-item {{ Route::is(auth()->user()->role->name . '.dashboard') ? 'active' : '' }}">
            <a href="{{ route(auth()->user()->role->name . '.dashboard') }}" class="sidebar-link"><i data-feather="layers" class="sidebar-icon-link"></i>Beranda</a>
        </li>


        <span class="divider" style="font-size:0.75rem;">TOOLS</span>

        @php
            // List route buat ke dropdown menu manajemen
            $manajemenRoutes = ['admin.pengguna', 'admin.roles'];
        @endphp

        @if(auth()->user()->role->name === "admin")
            <li class="sidebar-item @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif">
                <a href="" class="sidebar-link d-flex flex-grow collapsed" data-bs-toggle="collapse" data-bs-target="#manajemenDropdown">
                    <i data-feather="database" class="sidebar-icon-link"></i>Manajemen
                    <i data-feather="chevron-right" class="dropdown-icon @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif"></i>
                </a>

                <ul class="collapse list-unstyled dropdown-menu-vanilla @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif" id="manajemenDropdown">
                    <li class="sidebar-item @if (Route::currentRouteName() == 'admin.pengguna') active @endif">
                        <a href="{{ route('admin.pengguna') }}" class="sidebar-link">Pengguna</a>
                    </li>
                    <li class="sidebar-item @if (Route::currentRouteName() == 'admin.roles') active @endif">
                        <a href="{{ route('admin.roles') }}" class="sidebar-link">Roles</a>
                    </li>
                    <li class="sidebar-item @if (Route::currentRouteName() == 'admin.roles') active @endif">
                        <a href="{{ route('admin.roles') }}" class="sidebar-link">Operasional</a>
                    </li>
                </ul>
            </li>
        @endif


        {{-- Admin atau Laboran --}}
        @if (auth()->user()->role->name === "admin" || auth()->user()->role->name === "laboran")

            @php
                $laboratoriumRoutes = ['laboran.jenis-lab*','laboran.laboratorium*'];
            @endphp

            <li class="sidebar-item @if (Str::is($laboratoriumRoutes, Route::currentRouteName())) active @endif">
                <a href="" class="sidebar-link d-flex flex-grow collapsed" data-bs-toggle="collapse" data-bs-target="#laboratoriumContainer">
                    <i data-feather="folder" class="sidebar-icon-link"></i>Laboratorium
                    <i data-feather="chevron-right" class="dropdown-icon @if (Str::is($laboratoriumRoutes, Route::currentRouteName())) active @endif"></i>
                </a>

                <ul class="collapse list-unstyled dropdown-menu-vanilla @if (Str::is($laboratoriumRoutes, Route::currentRouteName())) active @endif" id="laboratoriumContainer">
                    <li class="sidebar-item @if (Str::is('laboran.jenis-lab*', Route::currentRouteName())) active @endif">
                        <a href="{{ route('laboran.jenis-lab') }}" class="sidebar-link">Jenis Lab</a>
                    </li>
                    <li class="sidebar-item @if (Str::is('laboran.laboratorium*', Route::currentRouteName())) active @endif">
                        <a href="{{ route('laboran.laboratorium') }}" class="sidebar-link">Laboratorium</a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item {{ Route::is('laboran.pengajuan*') ? 'active' : '' }}">
                <a href="{{ Route('laboran.pengajuan') }}" class="sidebar-link"><i data-feather="bar-chart-2" class="sidebar-icon-link"></i>Pengajuan</a>
            </li>

            <li class="sidebar-item {{ Route::is('admin.barang*') ? 'active' : '' }}">
                <a href="{{ Route('admin.barang') }}" class="sidebar-link"><i data-feather="box" class="sidebar-icon-link"></i>Barang</a>
            </li>

        @endif


        @if (auth()->user()->role->name !== "admin" && auth()->user()->role->name !== "laboran")
            <li class="sidebar-item {{ Route::is('pengajuan*') ? 'active' : '' }}">
                <a href="{{ Route('pengajuan') }}" class="sidebar-link"><i data-feather="calendar" class="sidebar-icon-link"></i>Jadwal</a>
            </li>
        @endif
    </ul>

</div>
