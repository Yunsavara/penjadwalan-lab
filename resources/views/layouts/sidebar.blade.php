<div class="sidebar shadow py-5 px-4" id="sidebar">
    <div class="brand-logo gap-2 d-flex justify-content-center py-1">
        <i data-feather="activity" id="brandIcon"></i>
        <span>Penjadwalan Lab</span>
    </div>
    <hr>

    <ul class="menu-container list-unstyled">
        <span class="divider" style="font-size:0.75rem;">MENU</span>
        {{-- Kalau selain admin dan laboran akan diarahkan ke dashboard umum --}}
        <li class="sidebar-item {{ Route::is(in_array(auth()->user()->role->name, ['admin', 'laboran']) ? auth()->user()->role->name . '.dashboard' : 'dashboard') ? 'active' : '' }}">
            <a href="{{ route(in_array(auth()->user()->role->name, ['admin', 'laboran']) ? auth()->user()->role->name . '.dashboard' : 'dashboard') }}" class="sidebar-link">
                <i data-feather="layers" class="sidebar-icon-link"></i>Beranda
            </a>
        </li>

        <span class="divider" style="font-size:0.75rem;">TOOLS</span>

        @php
            // List route buat ke dropdown menu manajemen
            $manajemenRoutes = ['laboran.pengguna', 'admin.barang'];
        @endphp

        @if(auth()->user()->role->name === "admin" || auth()->user()->role->name === "laboran")
            <li class="sidebar-item @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif">
                <a href="" class="sidebar-link d-flex flex-grow collapsed" data-bs-toggle="collapse" data-bs-target="#manajemenDropdown">
                    <i data-feather="database" class="sidebar-icon-link"></i>Manajemen
                    <i data-feather="chevron-right" class="dropdown-icon @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif"></i>
                </a>

                <ul class="collapse list-unstyled dropdown-menu-vanilla @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif" id="manajemenDropdown">
                    <li class="sidebar-item @if (Route::currentRouteName() == 'admin.pengguna') active @endif">
                        <a href="{{ route('laboran.pengguna') }}" class="sidebar-link">Pengguna</a>
                    </li>
                    <li class="sidebar-item @if (Route::currentRouteName() == 'admin.barang') active @endif">
                        <a href="{{ route('admin.barang') }}" class="sidebar-link">Barang</a>
                    </li>
                </ul>
            </li>
        @endif

                            {{-- <li class="sidebar-item @if (Route::currentRouteName() == 'admin.roles') active @endif">
                        <a href="{{ route('admin.roles') }}" class="sidebar-link">Operasional</a>
                    </li> --}}


        {{-- Admin atau Laboran --}}
        @if (auth()->user()->role->name == "admin" || auth()->user()->role->name == "laboran")
            <li class="sidebar-item {{ Route::is('laboran.laboratorium') ? 'active' : '' }}">
                <a href="{{ Route('laboran.laboratorium') }}" class="sidebar-link"><i data-feather="command" class="sidebar-icon-link"></i>Laboratorium</a>
            </li>
        @endif

        @if (auth()->user()->role->name !== "admin" && auth()->user()->role->name !== "laboran")
            <li class="sidebar-item {{ Route::is('pengajuan*') ? 'active' : '' }}">
                <a href="{{ Route('pengajuan') }}" class="sidebar-link"><i data-feather="calendar" class="sidebar-icon-link"></i>Jadwal</a>
            </li>
        @endif
    </ul>

</div>
