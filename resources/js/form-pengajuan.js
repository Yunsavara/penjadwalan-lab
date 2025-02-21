import { Indonesian } from "flatpickr/dist/l10n/id.js";


document.addEventListener("DOMContentLoaded", () => {
    initFormPengajuan();
    initFlatpickr();
});

function initFormPengajuan() {
    const buttonPengajuan = document.getElementById("btnPengajuan");
    const formPengajuanContainer = document.getElementById("formPengajuanContainer");
    const formPengajuan = formPengajuanContainer.querySelector("form");

    buttonPengajuan.addEventListener("click", () => {
        formPengajuanContainer.classList.toggle("show");

        if (formPengajuanContainer.classList.contains("show")) {
            buttonPengajuan.textContent = "Batalkan";
            buttonPengajuan.classList.remove("btn-primary");
            buttonPengajuan.classList.add("btn-danger");
        } else {
            buttonPengajuan.classList.add("btn-primary");
            buttonPengajuan.classList.remove("btn-danger");
            buttonPengajuan.textContent = "Buat Pengajuan";
            formPengajuan.reset(); // Reset form saat dibatalkan
        }
    });
}

let selectedDates = [];
let currentPage = 0;
const itemsPerPage = 1; // Menampilkan 1 tanggal per halaman

function initFlatpickr() {
    flatpickr("#tanggalPengajuan", {
        mode: "multiple",
        dateFormat: "d F Y",
        locale: Indonesian,
        onChange: function (dates, dateStr, instance) {
            selectedDates = dates.map(date => instance.formatDate(date, "Y-m-d")); // Format tanggal untuk menghindari perbedaan waktu
            currentPage = 0; // Reset halaman setiap kali tanggal berubah
            updateJamContainer();
            updatePagination();
        }
    });
}

function updateJamContainer() {
    const jamContainer = document.getElementById("jamContainer");
    jamContainer.innerHTML = "";

    const start = currentPage * itemsPerPage;
    const end = start + itemsPerPage;
    const displayedDates = selectedDates.slice(start, end);

    displayedDates.forEach((date) => {
        const formattedDate = new Date(date).toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric"
        });


        jamContainer.innerHTML += `
            <div class="col-12 border p-3 rounded">
                <h6 class="fw-bold">Tanggal : ${formattedDate}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <label for="jam_mulai_${date}">Jam Mulai</label>
                        <input type="text" name="jam_mulai[${date}]" id="jam_mulai_${date}" class="form-control timepicker">
                    </div>
                    <div class="col-md-6">
                        <label for="jam_selesai_${date}">Jam Selesai</label>
                        <input type="text" name="jam_selesai[${date}]" id="jam_selesai_${date}" class="form-control timepicker">
                    </div>
                </div>
            </div>
        `;
    });

    // Inisialisasi Flatpickr setelah input dibuat
    document.querySelectorAll(".timepicker").forEach((input) => {
        flatpickr(input, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i", // Format 24 jam
            time_24hr: true
        });
    });

    feather.replace(); // Render ulang ikon Feather
}


function updatePagination() {
    const paginationControls = document.getElementById("paginationControls");
    const totalPages = Math.ceil(selectedDates.length / itemsPerPage);

    if (selectedDates.length > 1) { // Jika ada tanggal yang dipilih
        paginationControls.innerHTML = `
            <button id="prevPage" class="btn btn-outline-primary me-2" ${currentPage === 0 ? "disabled" : ""}>
                <i data-feather="chevron-left"></i>
            </button>
            <span id="pageInfo">Halaman ${currentPage + 1} dari ${totalPages}</span>
            <button id="nextPage" class="btn btn-outline-primary ms-2" ${currentPage >= totalPages - 1 ? "disabled" : ""}>
                <i data-feather="chevron-right"></i>
            </button>
        `;
        paginationControls.style.display = "flex";
        paginationControls.style.alignItems = "center";
    } else {
        paginationControls.innerHTML = ""; // Kosongkan isi pagination
        paginationControls.style.display = "none"; // Sembunyikan elemen pagination
    }

    feather.replace(); // Render ulang ikon Feather
}

// Event delegation untuk pagination agar tetap berfungsi setelah pembaruan
document.addEventListener("click", (event) => {
    if (event.target.closest("#prevPage")) {
        if (currentPage > 0) {
            currentPage--;
            updateJamContainer();
            updatePagination();
        }
    }

    if (event.target.closest("#nextPage")) {
        const totalPages = Math.ceil(selectedDates.length / itemsPerPage);
        if (currentPage < totalPages - 1) {
            currentPage++;
            updateJamContainer();
            updatePagination();
        }
    }
});
