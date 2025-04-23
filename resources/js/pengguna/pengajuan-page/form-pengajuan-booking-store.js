import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/themes/airbnb.css";

import select2 from "select2";
select2();

// Inisialisasi Flatpickr
export function initializeDatePicker() {
    flatpickr("#tanggalMulaiBooking", {
        mode: "single",
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        locale: Indonesian,
        position: "below",
        theme: "airbnb"
    });

    flatpickr("#tanggalSelesaiBooking", {
        mode: "single",
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        locale: Indonesian,
        position: "below",
        theme: "airbnb"
    });
}

// Inisialisasi Select2
export function initializeSelect2() {
    $('#labPengajuanBooking').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Laboratorium",
        allowClear: true,
        multiple: true
    });
}

export function initializeAutoTimeSelect() {
    const $jamOtomatis = $('#jamOtomatis');
    $jamOtomatis.select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Jam",
        allowClear: true,
        width: '100%'
    });

    for (let h = 7; h < 21; h++) {
        const jam = `${h.toString().padStart(2, '0')}:00 - ${h.toString().padStart(2, '0')}:59`;
        $jamOtomatis.append(new Option(jam, h));
    }
}

// Cek status checkbox untuk hari tertentu
function isDayChecked(day, checkboxId) {
    return $(`#${checkboxId}`).prop('checked') && (
        day === 0 && checkboxId === 'minggu' ||
        day === 6 && checkboxId === 'sabtu' ||
        (day >= 1 && day <= 5 && checkboxId === 'weekdays')
    );
}

// Membuat elemen checkbox untuk slot waktu
function createTimeSlotCheckbox(date, lab, hour, autoSelectedHours = []) {
    const hourStr = String(hour).padStart(2, '0');
    const inputId = `${date}-${lab}-${hour}`;
    const isChecked = autoSelectedHours.includes(hour) ? 'checked' : '';
    return `
        <label class="form-check-label">
            <input class="form-check-input me-1" type="checkbox" id="${inputId}" name="booking[${date}][${lab}][]" value="${hour}" ${isChecked}>
            ${hourStr}:00 - ${hourStr}:59
        </label>
    `;
}

// Membuat card untuk tiap tanggal
function generateBookingCard(date, labs, autoSelectedHours = []) {
    let body = `<div class="card-body">`;

    labs.forEach(lab => {
        const labId = lab.replace(/\s+/g, '_');
        body += `
            <div class="mb-3 border p-2 rounded">
                <strong>${lab}</strong>
                <div class="checkbox-group">
        `;

        for (let hour = 7; hour < 21; hour++) {
            body += createTimeSlotCheckbox(date, labId, hour, autoSelectedHours);
        }

        body += `</div></div>`;
    });

    body += `</div>`;
    const card = document.createElement('div');
    card.className = 'card mb-3';
    card.innerHTML = `
        <div class="card-header"><strong>${date}</strong></div>
        ${body}
    `;
    return card;
}

// Fungsi utama untuk generate form booking
window.generateBookingForm = function () {
    const tanggalMulai = $('#tanggalMulaiBooking').val();
    const tanggalSelesai = $('#tanggalSelesaiBooking').val();
    const labs = $('#labPengajuanBooking').val() || [];
    const modeJam = $('#modeJam').val();
    const autoJam = $('#jamOtomatis').val()?.map(Number) || [];

    // Periksa status checkbox
    const statusCheckbox = {
        minggu: $('#minggu').prop('checked'),
        sabtu: $('#sabtu').prop('checked'),
        weekdays: $('#weekdays').prop('checked')
    };

    const container = document.getElementById('generatedFormContainer');
    container.innerHTML = '';

    if (!tanggalMulai || !tanggalSelesai || labs.length === 0) {
        alert("Harap pilih tanggal mulai, tanggal selesai, dan laboratorium.");
        return;
    }

    const startDate = new Date(tanggalMulai);
    const endDate = new Date(tanggalSelesai);
    let currentDate = new Date(startDate);

    while (currentDate <= endDate) {
        const formattedDate = currentDate.toISOString().split('T')[0]; // Format: yyyy-mm-dd
        const dayOfWeek = currentDate.getDay(); // 0: Minggu, 1: Senin, ..., 6: Sabtu

        // Skip tanggal sesuai dengan status checkbox
        if (isDayChecked(dayOfWeek, 'minggu') || isDayChecked(dayOfWeek, 'sabtu') || isDayChecked(dayOfWeek, 'weekdays')) {
            const card = generateBookingCard(formattedDate, labs, modeJam === 'otomatis' ? autoJam : []);
            container.appendChild(card);
        }

        currentDate.setDate(currentDate.getDate() + 1); // Menambah satu hari
    }

    const checkedBooking = [];

    // Ambil semua checkbox yang dicentang
    const checkedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');

    checkedCheckboxes.forEach(checkbox => {
        const name = checkbox.name;  // booking[date][lab][hour]
        const value = checkbox.value; // Jam yang dicentang
        const date = name.split('[')[1].split(']')[0];  // Ambil tanggal
        const lab = name.split('[')[2].split(']')[0];   // Ambil lab

        if (!checkedBooking[date]) checkedBooking[date] = {};
        if (!checkedBooking[date][lab]) checkedBooking[date][lab] = [];

        checkedBooking[date][lab].push(value);  // Menyimpan jam yang dicentang
    });

    console.log(checkedBooking);  // Cek hasil di console untuk memastikan data yang diproses
};

$('#modeJam').on('change', function () {
    const isAuto = $(this).val() === 'otomatis';
    $('#autoJamContainer').toggleClass('d-none', !isAuto);
});
