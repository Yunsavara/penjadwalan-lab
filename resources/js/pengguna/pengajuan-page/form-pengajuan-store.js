import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import select2 from "select2";
select2();

// Inisialisasi Flatpickr
export function initializeDatePicker() {
    flatpickr("#tanggalPengajuanBooking", {
        mode: "multiple",
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        locale: Indonesian,
        position: "below"
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

    // Tambahkan opsi jam dari 07:00 - 20:59
    for (let h = 7; h < 21; h++) {
        const jam = `${h.toString().padStart(2, '0')}:00 - ${h.toString().padStart(2, '0')}:59`;
        $jamOtomatis.append(new Option(jam, h));
    }
}

// Elemen checkbox slot waktu
function createTimeSlotCheckbox(date, lab, hour, autoSelectedHours = []) {
    const hourStr = String(hour).padStart(2, '0');
    const inputId = `${date}-${lab}-${hour}`;
    const isChecked = autoSelectedHours.includes(hour) ? 'checked' : '';
    return `
        <label class="form-check-label">
            <input
                class="form-check-input me-1"
                type="checkbox"
                id="${inputId}"
                name="booking[${date}][${lab}][]"
                value="${hour}"
                ${isChecked}>
            ${hourStr}:00 - ${hourStr}:59
        </label>
    `;
}


// Card untuk tiap tanggal
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


// Fungsi utama generate form booking
window.generateBookingForm = function () {
    const rawDates = $('#tanggalPengajuanBooking').val() || '';
    const dates = rawDates.split(',').map(date => date.trim()).filter(date => date);
    const labs = $('#labPengajuanBooking').val() || [];
    const modeJam = $('#modeJam').val();
    const autoJam = $('#jamOtomatis').val()?.map(Number) || [];

    const container = document.getElementById('generatedFormContainer');
    container.innerHTML = '';

    if (dates.length === 0 || labs.length === 0) {
        alert("Harap pilih tanggal dan laboratorium.");
        return;
    }

    dates.forEach(date => {
        const card = generateBookingCard(date, labs, modeJam === 'otomatis' ? autoJam : []);
        container.appendChild(card);
    });
};

$('#modeJam').on('change', function () {
    const isAuto = $(this).val() === 'otomatis';
    $('#autoJamContainer').toggleClass('d-none', !isAuto);
});
