// Import fungsi dari file lain
import { generateHariOperasionalCheckbox, updateLaboratoriumOptions } from "./fetch-form-pengajuan-booking-store";
import { initGenerateButtonForm, hapusLab, hapusAccordionTanggal } from "./generate-form-pengajuan-booking-store";

// Import Flatpickr dan tema serta lokal Indonesia
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/themes/airbnb.css";

// Import Select2
import select2 from "select2";
select2();

// Inisialisasi Select2 untuk dropdown
export function initSelect2() {
    const selectConfigs = [
        { selector: '#lokasi', placeholder: 'Pilih Lokasi' },
        { selector: '#laboratorium', placeholder: 'Pilih Laboratorium' },
        { selector: '#jamOperasional', placeholder: 'Pilih Jam' }
    ];

    selectConfigs.forEach(({ selector, placeholder }) => {
        $(selector).select2({
            theme: "bootstrap-5",
            placeholder,
            allowClear: false,
        }); 
    });
}

// Inisialisasi Flatpickr untuk input tanggal mulai dan selesai
export function initFlatpickrTanggal() {
    flatpickr("#tanggal_mulai,#tanggal_selesai", {
        locale: Indonesian,
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        minDate: "today",
    });
}

// Event ketika lokasi diubah
export function initLaboratoriumByLokasi() {
    $('#lokasi').on('select2:select', async (e) => {
        // Reset semua input & hasil generate form saat lokasi diganti
        $('#laboratorium').val(null).trigger('change').empty().append('<option value=""></option>');
        $('#tanggal_mulai')[0]._flatpickr.clear();
        $('#tanggal_selesai')[0]._flatpickr.clear();
        $('#hariOperasionalContainer').empty(); 
        $('#hariBookingLabel').remove();
        $('#jamOperasionalContainer').empty(); 
        $('#accordionGenerated').empty(); 
        $('#hasilGenerate').addClass('d-none'); 

        const lokasiId = e.params.data.id;
        await updateLaboratoriumOptions(lokasiId); // Ambil daftar lab berdasarkan lokasi
        await generateHariOperasionalCheckbox(lokasiId); // Generate checkbox hari operasional
    });
}

// Inisialisasi tombol generate dan expose fungsi hapus ke global
initGenerateButtonForm();
window.hapusLab = hapusLab;
window.hapusAccordionTanggal = hapusAccordionTanggal;
