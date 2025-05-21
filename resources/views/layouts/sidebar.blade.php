<div class="sidebar shadow py-5 px-4" id="sidebar">
    <div class="brand-logo gap-2 d-flex justify-content-center py-1">
        <i data-feather="activity" id="brandIcon"></i>
        <span>Penjadwalan Lab</span>
    </div>
    <hr>

    <ul class="menu-container list-unstyled">

        <span class="divider" style="font-size:0.75rem;">MENU</span>
        @php
            $userRole = auth()->user()->role->nama_peran;
            $dashboardRoute = in_array($userRole, ['admin', 'laboran']) ? $userRole . '.dashboard' : 'dashboard';
        @endphp
        <li class="sidebar-item {{ Route::is($dashboardRoute) ? 'active' : '' }}">
            <a href="{{ route($dashboardRoute) }}" class="sidebar-link">
                <i data-feather="layers" class="sidebar-icon-link"></i>Beranda
            </a>
        </li>

        <span class="divider" style="font-size:0.75rem;">TOOLS</span>

        @php
            $manajemenRoutes = ['admin.pengguna', 'laboran.laboratorium', 'admin.barang'];
            $isManajemenActive = in_array(Route::currentRouteName(), $manajemenRoutes);
        @endphp

        @if(in_array($userRole, ['admin', 'laboran']))
            <li class="sidebar-item {{ $isManajemenActive ? 'active' : '' }}">
                <a href="#" class="sidebar-link d-flex flex-grow collapsed" data-bs-toggle="collapse" data-bs-target="#manajemenDropdown">
                    <i data-feather="command" class="sidebar-icon-link"></i>Manajemen
                    <i data-feather="chevron-right" class="dropdown-icon {{ $isManajemenActive ? 'active' : '' }}"></i>
                </a>

                <ul class="collapse list-unstyled dropdown-menu-vanilla {{ $isManajemenActive ? 'active' : '' }}" id="manajemenDropdown">
                    @if($userRole === 'admin')
                        <li class="sidebar-item {{ Route::is('admin.pengguna') ? 'active' : '' }}">
                            <a href="{{ route('admin.pengguna') }}" class="sidebar-link">Pengguna</a>
                        </li>
                    @endif
                    <li class="sidebar-item {{ Route::is('laboran.laboratorium') ? 'active' : '' }}">
                        <a href="{{ route('laboran.laboratorium') }}" class="sidebar-link">Laboratorium</a>
                    </li>
                    <li class="sidebar-item {{ Route::is('admin.barang') ? 'active' : '' }}">
                        <a href="{{ route('admin.barang') }}" class="sidebar-link">Barang</a>
                    </li>
                </ul>
            </li>
        @endif

        @php
            $isBookingActive = Route::is('proses-pengajuan*') || Route::is('booking*')  || Route::is('laboran.proses-pengajuan*'); 
        @endphp

        <li class="sidebar-item {{ $isBookingActive ? 'active' : '' }}">
            <a href="#" class="sidebar-link d-flex flex-grow collapsed" data-bs-toggle="collapse" data-bs-target="#bookingDropdown">
                <i data-feather="calendar" class="sidebar-icon-link"></i>Booking
                <i data-feather="chevron-right" class="dropdown-icon {{ $isBookingActive ? 'active' : '' }}"></i>
            </a>

            <ul class="collapse list-unstyled dropdown-menu-vanilla {{ $isBookingActive ? 'active' : '' }}" id="bookingDropdown">

                @if ($userRole !== "laboran")
                    {{-- <li class="sidebar-item {{ Route::is('pengajuan*') ? 'active' : '' }}">
                        <a href="{{ route('pengajuan') }}" class="sidebar-link">Pengajuan</a>
                    </li> --}}

                    <li class="sidebar-item {{ Route::is('booking*') ? 'active' : '' }}">
                        <a href="{{ route('booking.index') }}" class="sidebar-link">Booking</a>
                    </li>
                @endif


                @if ($userRole == "laboran" || $userRole == "admin")
                    <li class="sidebar-item {{ Route::is('proses-pengajuan*') ? 'active' : '' }}">
                        <a href="{{ route('proses-pengajuan.index') }}" class="sidebar-link">Proses Pengajuan</a>
                    </li>
                @endif
            </ul>
        </li>

    </ul>
</div>
