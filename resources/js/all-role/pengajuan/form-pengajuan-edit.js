import { Modal } from 'bootstrap';
import { Indonesian } from "flatpickr/dist/l10n/id.js";

document.addEventListener("DOMContentLoaded", () => {
    initMultipleDate();
    initMultipleRuangan();
});

function initMultipleDate() {
    let tanggalHidden = document.querySelector("#editTanggalHidden");

    // Ambil nilai dari hidden input & ubah ke array
    let selectedDates = tanggalHidden.value ? tanggalHidden.value.split(",") : [];

    let flatpickrInstance = flatpickr("#editTanggalPengajuan", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        locale: Indonesian,
        altInput: true,
        altFormat: "d F Y",
        allowInput: false,
        defaultDate: selectedDates,
        onChange: function (selectedDates, dateStr) {
            let dateArray = selectedDates.map(date => flatpickr.formatDate(date, "Y-m-d"));
            tanggalHidden.value = dateArray.join(",");
        }
    });

    function resetForm(){
        document.querySelector("#formPengajuanEdit form").addEventListener("reset", function () {
            flatpickrInstance.clear(); // kosongkan tanggal
            tanggalHidden.value = ""; // Kosongkan input hidden
            $('#editRuangLab').val(null).trigger('change'); // kosongkan select ruang lab
        });
    }

    resetForm();
}

function initMultipleRuangan(){
    $('#formPengajuanEdit').on('shown.bs.modal', function () {
        $('#editRuangLab').select2({
            dropdownParent: $('#formPengajuanEdit'),
            theme: "bootstrap-5",
            multiple: true,
            placeholder: "Pilih Ruangan",
            allowInput: true,
            width: "100%"
        });
    });
}

export function showEditModal(kodePengajuan) {
    $.ajax({
        url: `/pengajuan-jadwal/edit/${kodePengajuan}`,
        type: "GET",
        success: function (response) {
            if (response.success) {
                let data = response.data;

                // Set form action dengan kode pengajuan
                $("#editForm").attr("action", `/pengajuan-jadwal/update/${kodePengajuan}`);

                // Isi nilai di modal edit
                $("#editKodePengajuan").val(data.kode_pengajuan);
                $("#editTanggalHidden").val(data.tanggal.join(',')); // Hidden input
                $("#editJamMulai").val(data.jam_mulai);
                $("#editJamSelesai").val(data.jam_selesai);
                $("#editKeperluanPengajuan").val(data.keperluan);

                // Update Flatpickr setelah tanggal diubah
                let flatpickrInstance = document.querySelector("#editTanggalPengajuan")._flatpickr;
                if (flatpickrInstance) {
                    flatpickrInstance.setDate(data.tanggal); // Update tanggal di flatpickr
                }

                // Populate ruang lab
                let ruangLabSelect = $("#editRuangLab");
                ruangLabSelect.empty();

                data.ruang.forEach(lab => {
                    let selected = data.lab_id.includes(lab.id) ? "selected" : "";
                    ruangLabSelect.append(`<option value="${lab.id}" ${selected}>${lab.name}</option>`);
                });

                // Reinitialize Select2 jika digunakan
                ruangLabSelect.select2();

                // Tampilkan modal
                const modalElement = document.getElementById('formPengajuanEdit');
                const modal = new Modal(modalElement);
                modal.show();
            } else {
                alert("Gagal mengambil data edit.");
            }
        },
        error: function () {
            alert("Terjadi kesalahan saat mengambil data.");
        }
    });
}
