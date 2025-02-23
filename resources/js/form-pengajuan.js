import { Indonesian } from "flatpickr/dist/l10n/id.js";

import $ from 'jquery';
window.$ = window.jQuery = $;

$(document).ready(function() {
    initSelect();
});

function initSelect(){
    $('#pilihRuangan').select2({
        theme: 'bootstrap-5',
        placeholder: "Pilih Ruang Lab",
        allowClear: true,
    });
}


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
            // Reset Select2
            $('#pilihRuangan').val(null).trigger('change');

            // Reset Flatpickr
            const flatpickrInstance = document.querySelector("#tanggalPengajuan")._flatpickr;
            if (flatpickrInstance) {
                flatpickrInstance.clear();
            }

        }
    });
}

let selectedDates = [];
let currentPage = 0;
const itemsPerPage = 1; // Menampilkan 1 tanggal per halaman
let selectedTimes = {}; // Untuk menyimpan data jam mulai dan selesai


function initFlatpickr() {
    flatpickr("#tanggalPengajuan", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d F Y",
        locale: Indonesian,
        onChange: function (dates, dateStr, instance) {
            const newSelectedDates = dates.map(date => instance.formatDate(date, "Y-m-d"));

            // Bersihkan input hidden sebelum diupdate
            document.getElementById("hiddenTanggalInputs").innerHTML = "";

            // Buat input hidden untuk setiap tanggal yang dipilih
            newSelectedDates.forEach(date => {
                const hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "tanggal_pengajuan[]";
                hiddenInput.value = date;
                document.getElementById("hiddenTanggalInputs").appendChild(hiddenInput);
            });

            // Hapus data jam yang tidak ada dalam tanggal yang dipilih
            Object.keys(selectedTimes).forEach((key) => {
                const dateKey = key.split("_").pop();
                if (!newSelectedDates.includes(dateKey)) {
                    delete selectedTimes[key];
                }
            });

            selectedDates = newSelectedDates;
            currentPage = 0;
            updateJamContainer();
            updatePagination();
        }
    });
}

function updateJamContainer() {
    const jamContainer = document.getElementById("jamContainer");

    // Simpan data sebelum dihapus
    document.querySelectorAll(".timepicker").forEach((input) => {
        selectedTimes[input.id] = input.value;
    });

    jamContainer.innerHTML = ""; // Bersihkan tampilan input jam
    document.getElementById("hiddenJamInputs").innerHTML = ""; // Bersihkan hidden input

    selectedDates.forEach((date) => {
        const formattedDate = new Date(date).toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric"
        });

        // Ambil nilai lama, jika ada
        const jamMulai = selectedTimes[`jam_mulai_${date}`] || "";
        const jamSelesai = selectedTimes[`jam_selesai_${date}`] || "";

        if (selectedDates.indexOf(date) >= currentPage * itemsPerPage && selectedDates.indexOf(date) < (currentPage + 1) * itemsPerPage) {
            jamContainer.innerHTML += `
                <div class="col-12 border p-3 rounded">
                    <h6 class="fw-bold">Tanggal : ${formattedDate}</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="jam_mulai_${date}">Jam Mulai</label>
                            <input type="text" id="jam_mulai_${date}" class="form-control timepicker" value="${jamMulai}">
                        </div>
                        <div class="col-md-6">
                            <label for="jam_selesai_${date}">Jam Selesai</label>
                            <input type="text" id="jam_selesai_${date}" class="form-control timepicker" value="${jamSelesai}">
                        </div>
                    </div>
                </div>
            `;
        }

        // Tambahkan hidden input yang selalu dikirim ke backend
        document.getElementById("hiddenJamInputs").innerHTML += `
            <input type="hidden" name="jam_mulai[${date}]" id="hidden_jam_mulai_${date}" value="${jamMulai}">
            <input type="hidden" name="jam_selesai[${date}]" id="hidden_jam_selesai_${date}" value="${jamSelesai}">
        `;
    });

    // Inisialisasi Flatpickr dan update hidden input saat jam berubah
    document.querySelectorAll(".timepicker").forEach((input) => {
        flatpickr(input, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            onChange: function (selectedDates, dateStr, instance) {
                selectedTimes[instance.element.id] = dateStr;

                // Perbarui hidden input yang sesuai
                const hiddenInput = document.getElementById(`hidden_${instance.element.id}`);
                if (hiddenInput) {
                    hiddenInput.value = dateStr;
                }
            }
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
