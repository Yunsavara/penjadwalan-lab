// Import library dan style yang dibutuhkan
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/themes/airbnb.css";
import select2 from "select2";

// Inisialisasi global Select2
select2();

// Fungsi untuk inisialisasi Select2 pada elemen tertentu
export function initSelect2() {
    const selects = document.querySelectorAll('#laboratorium, #hari_aktif, #jam');
    selects.forEach(select => {
        $(select).select2({
            placeholder: "Pilih opsi",
            width: '100%',
            theme: "bootstrap-5"
        });
    });
}

// Konfigurasi Flatpickr default yang digunakan untuk semua input tanggal
const flatpickrConfig = {
    altInput: true,
    altFormat: "d F Y",
    locale: Indonesian,
    dateFormat: "Y-m-d",
    theme: "airbnb"
};

// Fungsi untuk inisialisasi Flatpickr pada elemen input tanggal
export function initFlatpickr() {
    // Inisialisasi Flatpickr untuk #tanggal_mulai dan #tanggal_selesai
    flatpickr("#tanggal_mulai", flatpickrConfig);
    flatpickr("#tanggal_selesai", flatpickrConfig);
}


// Fungsi utama untuk mengatur proses generate form pengajuan booking
export function initGenerateFormPengajuan() {
    const generateBtn = document.getElementById('generateBtn');
    const accordionGenerated = document.getElementById('accordionGenerated');
    const hasilGenerate = document.getElementById('hasilGenerate');

    generateBtn.addEventListener('click', () => {
        // Ambil semua input dari form
        const selectedLabs = getSelectedValues('laboratorium');
        const selectedHours = getSelectedValues('jam');
        const selectedDays = getSelectedValues('hari_aktif');
        const startDate = parseDate('tanggal_mulai');
        const endDate = parseDate('tanggal_selesai');
        const reason = document.getElementById('alasan').value;

        // Validasi: Pastikan semua field terisi
        if (!selectedLabs.length || !selectedHours.length || !selectedDays.length || !startDate || !endDate) {
            alert('Mohon lengkapi semua field!');
            return;
        }

        // Kosongkan konten accordion sebelumnya
        accordionGenerated.innerHTML = '';

        // Generate konten accordion berdasarkan input
        generateAccordionContent(selectedLabs, selectedHours, selectedDays, startDate, endDate, reason, accordionGenerated);
        hasilGenerate.classList.remove('d-none');
    });
}

// Fungsi untuk membuat konten accordion berdasarkan input
function generateAccordionContent(labs, hours, days, startDate, endDate, reason, accordionGenerated) {
    let currentDate = new Date(startDate);
    let accordionItemId = 0;

    while (currentDate <= endDate) {
        // Konversi hari Minggu (0) menjadi 7 supaya konsisten
        const dayIndex = (currentDate.getDay() === 0) ? 7 : currentDate.getDay();
        if (days.includes(dayIndex.toString())) {
            accordionItemId++;
            const formattedDate = currentDate.toISOString().split('T')[0];
            const dayName = getDayName(dayIndex);

            // Buat konten accordion
            const accordionContent = createAccordionContent(formattedDate, dayName, accordionItemId, labs, hours, reason);
            accordionGenerated.insertAdjacentHTML('beforeend', accordionContent);
        }
        // Lanjut ke hari berikutnya
        currentDate.setDate(currentDate.getDate() + 1);
    }
}

// Fungsi untuk membuat satu item accordion
function createAccordionContent(date, dayName, itemId, labs, hours, reason) {
    const labSections = labs.map(lab => {
        const labName = getLabName(lab);
        return createLabSection(date, lab, labName, hours, reason);
    }).join('');

    return `
        <div class="accordion-item" id="accordion${date}">
            <h2 class="accordion-header" id="heading${itemId}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${itemId}" aria-expanded="true" aria-controls="collapse${itemId}">
                    ${date} (${dayName})
                </button>
            </h2>
            <div id="collapse${itemId}" class="accordion-collapse collapse" aria-labelledby="heading${itemId}" data-bs-parent="#accordionGenerated">
                <div class="accordion-body">
                    <div class="row">
                        ${labSections}
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Fungsi untuk membuat bagian dalam accordion untuk setiap lab
function createLabSection(date, labId, labName, selectedHours, reason) {
    const jamSelect = document.getElementById('jam');
    const allHours = Array.from(jamSelect.options).map(option => option.value);

    const hourCheckboxes = allHours.map(hour => `
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="${date}|${labId}|${hour}" id="checkbox${date}-${labId}-${hour}" ${selectedHours.includes(hour) ? 'checked' : ''} />
                <label class="form-check-label" for="checkbox${date}-${labId}-${hour}">
                    ${hour}
                </label>
            </div>
        </div>
    `).join('');

    return `
        <div class="col-12 mb-3" id="lab${date}-${labId}">
            <h5>${labName} - ${reason}</h5>
            ${hourCheckboxes}
            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteLabSlot('${date}', ${labId})">Hapus Lab</button>
        </div>
    `;
}

// Fungsi untuk mengambil nilai dari select multiple
function getSelectedValues(id) {
    const select = document.getElementById(id);
    return Array.from(select.selectedOptions).map(option => option.value);
}

// Fungsi untuk mengubah value input menjadi objek Date
function parseDate(id) {
    const value = document.getElementById(id).value;
    return value ? new Date(value) : null;
}

// Fungsi untuk mendapatkan nama hari dari angka (1-7)
function getDayName(index) {
    const days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
    return days[index - 1];
}

// Fungsi untuk mendapatkan nama laboratorium berdasarkan ID
function getLabName(id) {
    const labNames = {
        "1": "Lab Komputer A",
        "2": "Lab Komputer B",
        "3": "Lab Multimedia"
    };
    return labNames[id] || "Unknown Lab";
}

// Fungsi global untuk menghapus slot lab tertentu dari accordion
window.deleteLabSlot = function(date, labId) {
    const labSlot = document.getElementById(`lab${date}-${labId}`);
    if (labSlot) {
        labSlot.remove();

        // Jika semua lab di dalam satu accordion sudah dihapus, hapus accordion-nya
        const parentAccordion = document.getElementById(`accordion${date}`);
        if (parentAccordion && parentAccordion.querySelectorAll('.row .col-12').length === 0) {
            parentAccordion.remove();
        }
    }
};
