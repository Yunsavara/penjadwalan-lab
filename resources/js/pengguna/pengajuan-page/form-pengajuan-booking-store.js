// Import library dan style yang dibutuhkan
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/themes/airbnb.css";
import select2 from "select2";
import { updateLaboratoriumOptions } from "./fetch-form-pengajuan-booking";

// Inisialisasi global Select2
select2();

export function initSelect2(){
    $('#lokasi, #laboratorium, #jam').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Lokasi",
        allowClear: true,
    }); 

    $('#laboratorium').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Laboratorium",
        allowClear: true,
    }); 

    $('#jam').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Jam",
        allowClear: true,
    }); 
}

updateLaboratoriumOptions();

// Fungsi untuk inisialisasi event listener saat lokasi dipilih
export function initLaboratoriumByLokasi() {
    // Tambahkan event listener dengan Select2-specific event
    $('#lokasi').on('select2:select', function (e) {
        const lokasiId = e.params.data.id;
        updateLaboratoriumOptions(lokasiId);
    });
}


