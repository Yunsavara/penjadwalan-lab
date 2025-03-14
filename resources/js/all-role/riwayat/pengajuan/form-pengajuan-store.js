import { Indonesian } from "flatpickr/dist/l10n/id.js";

document.addEventListener("DOMContentLoaded", function () {
    initMultipleDate();
    initMultipleRuangan();
});

function initMultipleDate() {
    let tanggalHidden = document.querySelector("#tanggalHidden");

    // Ambil nilai dari hidden input & ubah ke array
    let selectedDates = tanggalHidden.value ? tanggalHidden.value.split(",") : [];

    let flatpickrInstance = flatpickr("#tanggalPengajuan", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        locale: Indonesian,
        altInput: true,
        altFormat: "d F Y",
        allowInput: false,
        defaultDate: selectedDates, // Load tanggal dari hidden input
        onChange: function (selectedDates, dateStr) {
            let dateArray = selectedDates.map(date => flatpickr.formatDate(date, "Y-m-d"));
            tanggalHidden.value = dateArray.join(",");
        }
    });

    function resetForm(){
        document.querySelector("#formPengajuanStore form").addEventListener("reset", function () {
            flatpickrInstance.clear(); // kosongkan tanggal
            tanggalHidden.value = ""; // Kosongkan input hidden
            $('#ruangLab').val(null).trigger('change'); // kosongkan select ruang lab
        });
    }

    resetForm();
}

function initMultipleRuangan(){
    $('#formPengajuanStore').on('shown.bs.modal', function () {
        $('#ruangLab').select2({
            dropdownParent: $('#formPengajuanStore'),
            theme: "bootstrap-5",
            multiple: true,
            placeholder: "Pilih Ruangan",
            allowInput: true,
            width: "100%"
        });
    });
}
