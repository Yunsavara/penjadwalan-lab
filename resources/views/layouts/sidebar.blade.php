{{-- <section id="sidebar">
    <a href="#" class="brand"><i class='bx bxs-smile icon'></i> Lab Scheduler</a>
    <ul class="side-menu">
        <li>
            <a href="#" class="{{ Route::is(auth()->user()->role->name . '.dashboard') ? 'active' : '' }}">
                <i class='bx bxs-dashboard icon'></i> Dashboard
            </a>
        </li>

        <li class="divider" data-text="main">Main</li>

        @if(auth()->user()->role->name == "admin")
            <li class="{{ Route::is('admin.pengguna') || Route::is('admin.roles') ? 'active' : '' }}">
                <a href="#">
                    <i class='bx bxs-user-account icon'></i> Manajemen <i class='bx bx-chevron-right icon-right'></i>
                </a>
                <ul class="side-dropdown">
                    <li><a href="{{ route('admin.pengguna') }}" class="{{ Route::is('admin.pengguna') ? 'active' : '' }}">Pengguna</a></li>
                    <li><a href="{{ route('admin.roles') }}" class="{{ Route::is('admin.roles') ? 'active' : '' }}">Roles</a></li>
                </ul>
            </li>
        @endif


    </ul>
</section> --}}





<aside class="sidebar">
    <div class="brand-logo">
        <i data-feather="activity" id="brandIcon"></i>
        <span id="brandLabel">Penjadwalan Lab</span>
    </div>

    <ul class="sidebar-menu-container">
        <div class="divider"><span>UMUM</span></div>
        <li class="sidebar-item {{ Route::is(auth()->user()->role->name . '.dashboard') ? 'active' : '' }}">
            <a href="{{ route(auth()->user()->role->name . '.dashboard') }}"
               class="sidebar-link">
                <i data-feather="layers" class="sidebar-icon-link"></i> Beranda
            </a>
        </li>

        @php
            // List route buat ke dropdown menu
            $manajemenRoutes = ['admin.pengguna', 'admin.roles'];
        @endphp

        @if(auth()->user()->role->name == "admin")
            <li class="sidebar-item dropdown-container
                @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif">
                <a href="#" class="sidebar-link sidebar-toggle">
                    <i data-feather="database" class="sidebar-icon-link"></i>Manajemen
                    <i data-feather="chevron-right" class="dropdown-icon
                        @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif"></i>
                </a>
                <ul class="dropdown-menu
                    @if (in_array(Route::currentRouteName(), $manajemenRoutes)) active @endif">
                    <li class="sidebar-item @if (Route::currentRouteName() == 'admin.pengguna') active @endif">
                        <a href="{{ route('admin.pengguna') }}" class="sidebar-link">Pengguna</a>
                    </li>
                    <li class="sidebar-item @if (Route::currentRouteName() == 'admin.roles') active @endif">
                        <a href="{{ route('admin.roles') }}" class="sidebar-link">Roles</a>
                    </li>
                </ul>
            </li>
        @endif



        <div class="divider"><span>Tools</span></div>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link"><i data-feather="box" class="sidebar-icon-link"></i>Barang</a>
        </li>

        <li class="sidebar-item dropdown-container">
            <a href="index.html" class="sidebar-link sidebar-toggle"><i data-feather="watch" class="sidebar-icon-link"></i>Jadwal <i data-feather="chevron-right" class="dropdown-icon"></i></a>
            <ul class="dropdown-menu">
                <li class="sidebar-item">
                    <a href="index.html" class="sidebar-link">Proses</a>
                </li>
                <li class="sidebar-item">
                    <a href="index.html" class="sidebar-link">Selesai</a>
                </li>
            </ul>
        </li>
    </ul>

</aside>
