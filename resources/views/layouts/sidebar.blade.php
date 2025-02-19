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

        @if(auth()->user()->role->name == "admin")
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
                </ul>
            </li>

            <li class="sidebar-item {{ Route::is('admin.semester*') ? 'active' : '' }}">
                <a href="{{ Route('admin.semester') }}" class="sidebar-link"><i data-feather="framer" class="sidebar-icon-link"></i>Semester</a>
            </li>

            <li class="sidebar-item {{ Route::is('admin.matakuliah*') ? 'active' : '' }}">
                <a href="{{ Route('admin.matakuliah') }}" class="sidebar-link"><i data-feather="book-open" class="sidebar-icon-link"></i>Mata Kuliah</a>
            </li>

            @php
                $laboratoriumRoutes = ['admin.jenis-lab*','admin.laboratorium*'];
            @endphp

            <li class="sidebar-item @if (Str::is($laboratoriumRoutes, Route::currentRouteName())) active @endif">
                <a href="" class="sidebar-link d-flex flex-grow collapsed" data-bs-toggle="collapse" data-bs-target="#laboratoriumContainer">
                    <i data-feather="folder" class="sidebar-icon-link"></i>Laboratorium
                    <i data-feather="chevron-right" class="dropdown-icon @if (Str::is($laboratoriumRoutes, Route::currentRouteName())) active @endif"></i>
                </a>

                <ul class="collapse list-unstyled dropdown-menu-vanilla @if (Str::is($laboratoriumRoutes, Route::currentRouteName())) active @endif" id="laboratoriumContainer">
                    <li class="sidebar-item @if (Str::is('admin.jenis-lab*', Route::currentRouteName())) active @endif">
                        <a href="{{ route('admin.jenis-lab') }}" class="sidebar-link">Jenis Lab</a>
                    </li>
                    <li class="sidebar-item @if (Str::is('admin.laboratorium*', Route::currentRouteName())) active @endif">
                        <a href="{{ route('admin.laboratorium') }}" class="sidebar-link">Laboratorium</a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item {{ Route::is('admin.barang*') ? 'active' : '' }}">
                <a href="{{ Route('admin.barang') }}" class="sidebar-link"><i data-feather="box" class="sidebar-icon-link"></i>Barang</a>
            </li>
        @endif
    </ul>

</div>
