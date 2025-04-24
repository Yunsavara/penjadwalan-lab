import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/themes/airbnb.css";
import select2 from "select2";

// Inisialisasi Select2 (aktifkan plugin)
select2();

// Inisialisasi date picker
export function initializeDatePicker() {
    const options = {
        mode: "single",
        altInput: true,
        altFormat: "d F Y",      // Tampilan user
        dateFormat: "Y-m-d",     // Format yang dikirim
        locale: Indonesian,
        position: "below",
        theme: "airbnb"
    };

    flatpickr("#tanggalMulaiBooking", options);
    flatpickr("#tanggalSelesaiBooking", options);
}

// Inisialisasi Select2 untuk laboratorium
export function initializeSelect2() {
    $('#labPengajuanBooking').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Laboratorium",
        multiple: true,
    });
}

// Inisialisasi jam otomatis
export function initializeAutoTimeSelect() {
    const $jamOtomatis = $('#jamOtomatis');
    $jamOtomatis.select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Jam",
        width: '100%'
    });

    for (let h = 7; h < 21; h++) {
        const label = `${h.toString().padStart(2, '0')}:00 - ${h.toString().padStart(2, '0')}:59`;
        $jamOtomatis.append(new Option(label, h));
    }
}

// Mengecek checkbox hari yang aktif
function isDayChecked(day, id) {
    return $(`#${id}`).prop('checked') && (
        (day === 0 && id === 'minggu') ||
        (day === 6 && id === 'sabtu') ||
        (day >= 1 && day <= 5 && id === 'weekdays')
    );
}

// Membuat checkbox untuk satu slot jam
function createTimeSlotCheckbox(date, lab, hour, selectedHours = []) {
    const paddedHour = hour.toString().padStart(2, '0');
    const inputId = `${date}-${lab}-${hour}`;
    const isChecked = selectedHours.includes(hour) ? 'checked' : '';

    return `
        <label class="form-check-label">
            <input class="form-check-input me-1" type="checkbox"
                   id="${inputId}"
                   name="booking[${date}][${lab}][]"
                   value="${hour}" ${isChecked}>
            ${paddedHour}:00 - ${paddedHour}:59
        </label>
    `;
}

// Kartu slot booking per tanggal dan lab
function generateBookingCard(dateKey, dateLabel, labs, selectedHours = []) {
    const bodyContent = labs.map(lab => {
        const labId = lab.id.replace(/\s+/g, '_');
        const checkboxes = Array.from({ length: 14 }, (_, i) =>
            createTimeSlotCheckbox(dateKey, labId, i + 7, selectedHours)
        ).join('');

        return `
            <div class="mb-3 border p-2 rounded">
                <strong>${lab.name}</strong><br>
                <span class="text-muted">${lab.location}</span>
                <div class="checkbox-group">${checkboxes}</div>
            </div>
        `;
    }).join('');

    const card = document.createElement('div');
    card.className = 'card mb-3';
    card.innerHTML = `
        <div class="card-header"><strong>${dateLabel}</strong></div>
        <div class="card-body">${bodyContent}</div>
    `;
    return card;
}

// Generate form booking dinamis
window.generateBookingForm = function () {
    const tanggalMulai = $('#tanggalMulaiBooking').val();
    const tanggalSelesai = $('#tanggalSelesaiBooking').val();
    const selectedLabs = $('#labPengajuanBooking').select2('data');

    const labs = selectedLabs.map(item => ({
        id: item.id,
        name: item.text,
        location: item.element.closest('optgroup').label
    }));

    const modeJam = $('input[name="modeJam"]:checked').val();
    const selectedJam = $('#jamOtomatis').val()?.map(Number) || [];

    if (!tanggalMulai || !tanggalSelesai || labs.length === 0) {
        return alert("Harap pilih tanggal mulai, tanggal selesai, dan laboratorium.");
    }

    const container = document.getElementById('generatedFormContainer');
    container.innerHTML = '';

    const startDate = new Date(tanggalMulai);
    const endDate = new Date(tanggalSelesai);

    for (let current = new Date(startDate); current <= endDate; current.setDate(current.getDate() + 1)) {
        const day = current.getDay();
        const dateKey = current.toISOString().split('T')[0]; // Format untuk name input
        const dateLabel = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }).format(current); // Label untuk tampilan

        if (['minggu', 'sabtu', 'weekdays'].some(id => isDayChecked(day, id))) {
            const card = generateBookingCard(dateKey, dateLabel, labs, modeJam === 'otomatis' ? selectedJam : []);
            container.appendChild(card);
        }
    }

    // Debug hasil checkbox yang dipilih
    const checkedBooking = {};
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
        const { name, value } = checkbox;
        const parts = name.split('[');
        const date = parts[1]?.split(']')[0] || '';
        const lab = parts[2]?.split(']')[0] || '';

        if (!checkedBooking[date]) checkedBooking[date] = {};
        if (!checkedBooking[date][lab]) checkedBooking[date][lab] = [];

        checkedBooking[date][lab].push(value);
    });

    console.log(checkedBooking);
};

// Toggle input jam otomatis
$('input[name="modeJam"]').on('change', function () {
    const isAuto = $(this).val() === 'otomatis';
    $('#autoJamContainer').toggleClass('d-none', !isAuto);
});
