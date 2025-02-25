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
    initTanggalAndJam();
    initOldForm();
});

function initFormPengajuan() {
    const buttonPengajuan = document.getElementById("btnPengajuan");
    const formPengajuanContainer = document.getElementById("formPengajuanContainer");

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
        }
    });
}

function initTanggalAndJam(){
    let currentPage = 0;
    const tanggalContainer = document.getElementById("tanggalInputs");
    const prevButton = document.getElementById("prevTanggal");
    const nextButton = document.getElementById("nextTanggal");
    const addButton = document.getElementById("addTanggal");
    const removeButton = document.getElementById("removeTanggal");

    function updatePagination() {
        const allPages = document.querySelectorAll(".tanggal-input");
        allPages.forEach((page, index) => {
            page.style.display = index === currentPage ? "block" : "none";
        });

        prevButton.disabled = currentPage === 0;
        nextButton.disabled = currentPage === allPages.length - 1;
        removeButton.disabled = allPages.length <= 1;
    }

    addButton.addEventListener("click", function () {
        const newTanggalInput = document.createElement("div");
        newTanggalInput.classList.add("tanggal-input");
        newTanggalInput.innerHTML = `
            <input type="date" name="tanggal_pengajuan[]" class="form-control mb-2" required>
            <input type="time" name="jam_mulai[]" class="form-control mb-2" required>
            <input type="time" name="jam_selesai[]" class="form-control mb-2" required>
        `;
        tanggalContainer.appendChild(newTanggalInput);
        currentPage++;
        updatePagination();
    });

    prevButton.addEventListener("click", function () {
        if (currentPage > 0) {
            currentPage--;
            updatePagination();
        }
    });

    nextButton.addEventListener("click", function () {
        if (currentPage < document.querySelectorAll(".tanggal-input").length - 1) {
            currentPage++;
            updatePagination();
        }
    });

    removeButton.addEventListener("click", function () {
        const allPages = document.querySelectorAll(".tanggal-input");
        if (allPages.length > 1) {
            allPages[currentPage].remove();
            if (currentPage >= allPages.length - 1) {
                currentPage = Math.max(0, allPages.length - 2);
            }
            updatePagination();
        }
    });

    updatePagination();

}

function initOldForm(){
    // Cek apakah ada old input, kalau ada atur jumlah halaman sesuai old input
    const existingInputs = document.querySelectorAll(".tanggal-input").length;
    if (existingInputs > 1) {
        currentPage = 0;
        updatePagination();
    }
}
