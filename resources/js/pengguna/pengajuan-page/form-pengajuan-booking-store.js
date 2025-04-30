import { generateHariOperasionalCheckbox, updateLaboratoriumOptions } from "./fetch-form-pengajuan-booking-store";
import { initGenerateButtonForm, hapusLab, hapusAccordionTanggal } from "./generate-form-pengajuan-booking-store";


import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/themes/airbnb.css";
import select2 from "select2";

select2();

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
            allowClear: true,
        }); 
    });
}

export function initFlatpickrTanggal() {
    flatpickr("#tanggal_mulai,#tanggal_selesai", {
        locale: Indonesian,
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        minDate: "today",
    });
}

export function initLaboratoriumByLokasi() {
    $('#lokasi').on('select2:select', async (e) => {
        // Reset semua input dan hasil form saat lokasi diganti
        $('#laboratorium').val(null).trigger('change'); 
        $('#tanggal_mulai')[0]._flatpickr.clear();
        $('#tanggal_selesai')[0]._flatpickr.clear();
        $('#hariOperasionalContainer').empty(); 
        $('#hariBookingLabel').remove();
        $('#jamOperasionalContainer').empty(); 
        $('#accordionGenerated').empty(); 
        $('#hasilGenerate').addClass('d-none'); 

        const lokasiId = e.params.data.id;
        await updateLaboratoriumOptions(lokasiId);
        await generateHariOperasionalCheckbox(lokasiId);
    });
}


initGenerateButtonForm()
window.hapusLab = hapusLab;
window.hapusAccordionTanggal = hapusAccordionTanggal;