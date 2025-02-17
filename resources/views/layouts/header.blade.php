<nav class="navbar bg-white shadow-sm sticky-top">
    <div class="container-fluid d-flex justify-content">
        <div class="menu-role d-flex gap-2">
            <i data-feather="menu" id="toggleSidebar"></i>
            <span class="fw-bold" style="letter-spacing: 2px;">ADMIN</span>
        </div>
        <div class="tools-container d-flex justify-content gap-3">

            <!-- Notification -->
            <div class="notification-icon-container" style="cursor: pointer;">
                <i data-feather="bell" id="notificationIcon"></i><span id="notifCounter"></span>
            </div>
            <!-- /Notification -->

            <!-- Search -->
            <div class="search-icon-container" style="cursor: pointer;">
                <i data-feather="search" id="searchIcon"></i>
            </div>
            <!-- /Search -->

            <!-- Profile Menu -->
            <div class="profile-container">
                <div class="d-flex align-items-center" id="profileDropdown" style="cursor: pointer;">
                    <img src="https://i.pinimg.com/474x/d0/31/25/d031252582615a4b54880616bc82a916.jpg" alt="profile-picture"
                        class="img-fluid object-fit-cover rounded-5 border border-black" width="25" height="25">
                    <span class="ms-2 d-inline-block text-truncate" style="max-width: 5rem;">Yunsavara</span>
                    <i data-feather="chevron-down" class="ms-2" style="width: 1rem;"></i>
                </div>

                <!-- Dropdown Menu Profile -->
                <ul class="dropdown-menu-profile mt-3" id="dropdownMenuProfile">
                    <li class="profile-item">
                        <a class="profile-link" href="#">
                            <i data-feather="user" class="profile-icon"></i>
                            Profile
                        </a>
                    </li>
                    <li class="profile-item">
                        <a class="profile-link" href="#">
                            <i data-feather="settings" class="profile-icon"></i>
                            Pengaturan
                        </a>
                    </li>
                    <li class="profile-item">
                        <a class="profile-link" href="#">
                            <i data-feather="log-out" class="profile-icon"></i>
                            Keluar
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /Profile Menu -->

        </div>
    </div>
</nav>
