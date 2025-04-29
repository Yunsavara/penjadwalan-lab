import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/themes/airbnb.css";
import { generateHariOperasionalCheckbox, updateLaboratoriumOptions } from "./fetch-form-pengajuan-booking";
import select2 from "select2";

select2();

export function initSelect2(){
    $('#lokasi').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Lokasi",
        allowClear: true,
    }); 

    $('#laboratorium').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Laboratorium",
        allowClear: true,
    }); 

    $('#jamOperasional').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Jam",
        allowClear: true,
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

updateLaboratoriumOptions();
generateHariOperasionalCheckbox();

// Fungsi untuk inisialisasi event listener saat lokasi dipilih
export function initLaboratoriumByLokasi() {
    // Tambahkan event listener dengan Select2-specific event
    $('#lokasi').on('select2:select', function (e) {
        const lokasiId = e.params.data.id;
        updateLaboratoriumOptions(lokasiId);
        generateHariOperasionalCheckbox(lokasiId);
    });
}


