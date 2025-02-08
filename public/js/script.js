// Toggle Sidebar
function initSidebar(){
    const menuBar = document.getElementById("menuBar");
    const sideBar = document.querySelector(".sidebar");

    menuBar.addEventListener("click", (e) => {
        e.stopPropagation();
        if (window.innerWidth <= 768) {
            sideBar.classList.toggle("active");
            sideBar.classList.remove("hidden");
        } else {
            sideBar.classList.toggle("hidden");
            sideBar.classList.remove("active");
        }
    });

    document.addEventListener("click", (event) => {
        if (window.innerWidth <= 768 && !sideBar.contains(event.target) && !menuBar.contains(event.target)) {
            sideBar.classList.remove("active");
            sideBar.classList.add("hidden");
        }
    });
}

initSidebar();

// Sidebar Dropdown Menu
function initDropdown(){
    const dropdownToggles = document.querySelectorAll(".sidebar-toggle");

    dropdownToggles.forEach((dropdownToggle) => {
        dropdownToggle.addEventListener("click", (e) => {
            e.preventDefault();

            // Tutup semua dropdown yang sedang terbuka
            dropdownToggles.forEach((otherToggle) => {
                if (otherToggle !== dropdownToggle) {
                    const otherMenu = otherToggle.nextElementSibling;
                    const otherIcon = otherToggle.querySelector(".dropdown-icon");

                    if (otherMenu && otherMenu.classList.contains("dropdown-menu")) {
                        otherMenu.classList.remove("active");
                        otherMenu.style.maxHeight = "0";
                        otherIcon.classList.remove("active");
                    }
                }
            });

            // Buka atau tutup dropdown yang diklik
            const dropdownMenu = dropdownToggle.nextElementSibling;
            const dropdownIcon = dropdownToggle.querySelector(".dropdown-icon");

            if (dropdownMenu && dropdownMenu.classList.contains("dropdown-menu")) {
                dropdownMenu.classList.toggle("active");
                dropdownIcon.classList.toggle("active");

                // Animasi Dropdown
                if (dropdownMenu.classList.contains("active")) {
                    dropdownMenu.style.maxHeight = dropdownMenu.scrollHeight + "px";
                } else {
                    dropdownMenu.style.maxHeight = "0";
                }
            }
        });
    });

    // Pastikan dropdown menu terbuka jika ada item aktif di dalamnya
    document.querySelectorAll(".dropdown-menu").forEach((menu) => {
        const activeItem = menu.querySelector(".sidebar-item.active");
        if (activeItem) {
            console.log("Dropdown menu ditemukan dan harus terbuka!"); // Tes log
            menu.classList.add("active");
            menu.style.maxHeight = menu.scrollHeight + "px";

            // Tambahkan class active ke parent dropdown-container
            const parentContainer = menu.closest(".dropdown-container");
            if (parentContainer) {
                parentContainer.classList.add("active");
            }

            // Ubah icon dropdown jadi active juga
            const parentToggle = menu.previousElementSibling;
            if (parentToggle && parentToggle.classList.contains("sidebar-toggle")) {
                const dropdownIcon = parentToggle.querySelector(".dropdown-icon");
                dropdownIcon.classList.add("active");
            }
        }
    });


}

initDropdown();

// Tampilin menu pas klik profil di navbar
function initProfilMenu() {
    const profil = document.querySelector(".foto-profil-login");
    const profilMenu = document.querySelector(".profil-menu");

    profil.addEventListener("click", () => {
        profilMenu.classList.toggle("active");
    })

    // Diluar profil ketutup
    function closeProfilMenu(e){
        if(!profil.contains(e.target) && !profilMenu.contains(e.target)){
            profilMenu.classList.remove("active");
        }
    }

    document.addEventListener("click", closeProfilMenu);
}

initProfilMenu();

function initNotification() {
    const notificationIcon = document.getElementById("notificationIcon");
    const notifList = document.querySelector(".notif-list");

    notificationIcon.addEventListener("click", () => {
        notifList.classList.toggle("active");
    })

    function closeNotifList(e) {
        if(!notificationIcon.contains(e.target) && !notifList.contains(e.target)){
            notifList.classList.remove("active");
        }
    }

    document.addEventListener("click", closeNotifList);
}

initNotification();

// Fungsi Munculin Search Box
function initSearchBox() {
    const searchIcon = document.getElementById("searchIcon");
    const searchBox = document.querySelector(".search-container");

    searchIcon.addEventListener("click", () => {
        searchBox.classList.toggle("active");
    })

    function closeSearchBox(e) {
        if(!searchIcon.contains(e.target) && !searchBox.contains(e.target)){
            searchBox.classList.remove("active");
        }
    }

    document.addEventListener("click", closeSearchBox);
}

initSearchBox();
