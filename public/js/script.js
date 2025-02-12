// isi Function Langsung load DOM tanpa nunggu element HTML ke Load
document.addEventListener("DOMContentLoaded", () => {
    initSidebar();
    initProfile();
    initSearch();
    initNotif();
});

function initSidebar() {
    // Sidebar Toggle (Mobile & Desktop)
    const menuBar = document.getElementById("toggleSidebar");
    const sideBar = document.getElementById("sidebar");

    menuBar?.addEventListener("click", e => {
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

    // Buka Dropdown Sidebar Jika Ada Item Aktif
    document.querySelectorAll(".sidebar-item").forEach(item => {
        let activeItem = item.querySelector(".sidebar-item.active");
        let dropdownMenu = item.querySelector(".collapse");
        let dropdownToggle = item.querySelector(".sidebar-link");

        if (activeItem && dropdownMenu && dropdownToggle) {
            new bootstrap.Collapse(dropdownMenu, { toggle: true });
        }
    });

    // Tutup Dropdown Lain Saat Klik yang Baru (sidebar)
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(dropdown => {
        dropdown.addEventListener("click", function () {
            let target = document.querySelector(this.getAttribute("data-bs-target"));

            document.querySelectorAll('.collapse.show').forEach(menu => {
                if (menu !== target) {
                    bootstrap.Collapse.getInstance(menu)?.hide();
                }
            });
        });
    });
}

function initSearch() {
    const searchToggle = document.getElementById("searchIcon");
    const searchBox = document.querySelector(".search-container");

    if (searchToggle && searchBox) {
        searchToggle.addEventListener("click", e => {
            searchBox.classList.toggle("show");
        });

        document.addEventListener("click", e => {
            if (!searchBox.contains(e.target) && !searchToggle.contains(e.target)) {
                searchBox.classList.remove("show");
            }
        });
    }
}

function initProfile(){
    const dropdownToggle = document.getElementById("profileDropdown");
    const dropdownMenuProfile = document.getElementById("dropdownMenuProfile");

    dropdownToggle.addEventListener("click", e => {
        dropdownMenuProfile.classList.toggle("show");
    });

    function closeProfileMenu(e) {
        if (!dropdownToggle.contains(e.target) && !dropdownMenuProfile.contains(e.target)) {
            dropdownMenuProfile.classList.remove("show");
        }
    }

    document.addEventListener("click", closeProfileMenu);
}

function notifCounter(){
    const notifActive = document.querySelectorAll(".notif-item.active").length;
    const notifCounter = document.getElementById("notifCounter");

    if (notifActive > 0) {
        notifCounter.textContent = notifActive;
        notifCounter.style.display = "inline-block";
    } else {
        notifCounter.style.display = "none";
    }
}

notifCounter();

function initNotif(){
    const notif = document.querySelector(".notification-icon-container");
    const notifMessage = document.querySelector(".notif-container");

    notif.addEventListener("click", () => {
        notifMessage.classList.toggle("show");
    })

    function closeNotifMessage(e) {
        if(!notif.contains(e.target) && !notifMessage.contains(e.target)){
            notifMessage.classList.remove("show");
        }
    }

    document.addEventListener("click", closeNotifMessage);
}
