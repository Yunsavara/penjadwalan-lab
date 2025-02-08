<nav class="header">
    <div class="menu-container">
        <i data-feather="menu" id="menuBar"></i>
    </div>

    <div class="tools-container">
        <div class="notification-icon">
            <i data-feather="bell" id="notificationIcon"></i>
            <div class="notif-list">
                <ul class="notif-container">
                    <li class="notif-item active">
                        <a href="#" class="notif-link">
                            <img src="{{ asset('images/unpam-logo.png') }}" alt="foto profil">
                            <div class="name-and-message">
                                <p class="sender-name">yunikosatria23@gmail.com</p>
                                <p class="message-notif">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad, voluptates?</p>
                            </div>
                        </a>
                    </li>
                    <li class="notif-item">
                        <a href="#" class="notif-link">
                            <img src="{{ asset('images/unpam-logo.png') }}" alt="foto profil">
                            <div class="name-and-message">
                                <p class="sender-name">yunikosatria23@gmail.com</p>
                                <p class="message-notif">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad, voluptates?</p>
                            </div>
                        </a>
                    </li>
                    <li class="notif-item">
                        <a href="#" class="notif-link">
                            <img src="{{ asset('images/unpam-logo.png') }}" alt="foto profil">
                            <div class="name-and-message">
                                <p class="sender-name">yunikosatria23@gmail.com</p>
                                <p class="message-notif">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad, voluptates?</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="search-icon">
            <i data-feather="search" id="searchIcon"></i>
        </div>

        <div class="foto-profil-login">
            <img src="{{ asset('images/unpam-logo.png') }}" alt="foto profil login" id="fotoProfilLogin">
            <span id="loginName">{{ Auth::user()->name }}</span>
            <i data-feather="chevron-down" id="chevronProfilLogin"></i>
            <div class="profil-menu">
                <ul class="profil-menu-container">
                    <li class="profil-menu-item"><a href="#" class="profil-menu-link">
                        <i data-feather="user" class="profil-menu-icon"></i> Profil</a>
                    </li>
                    <li class="profil-menu-item"><a href="#" class="profil-menu-link">
                        <i data-feather="settings" class="profil-menu-icon"></i> Pengaturan</a>
                    </li>
                    <li class="profil-menu-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" class="profil-menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i data-feather="log-out" class="profil-menu-icon"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
