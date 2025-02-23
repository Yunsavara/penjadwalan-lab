// Fungsi untuk menampilkan dan memuat form update
function toggleEditForm(kodePengajuan) {
    if (kodePengajuan) {
        loadUpdateForm(kodePengajuan);
    } else {
        console.error("Kode pengajuan tidak ditemukan.");
    }
}

$(document).ready(function() {
    $(document).on('click', '.btn-warning', function() {
        const kodePengajuan = $(this).data('kode-pengajuan'); // Ambil dari data-attribute
        toggleEditForm(kodePengajuan); // Panggil toggleEditForm
    });
});

function initFlatpickr(tanggalPengajuan) {
    // Inisialisasi flatpickr pada elemen input tanggal
    flatpickr("#tanggalPengajuan", {
        mode: "multiple", // Jika ingin mengizinkan pemilihan beberapa tanggal
        dateFormat: "Y-m-d", // Format tanggal sesuai kebutuhan Anda
        defaultDate: tanggalPengajuan, // Mengatur tanggal yang sudah dipilih sebagai default
    });
}


function loadUpdateForm(kodePengajuan) {
    $.ajax({
        url: `/pengajuan-jadwal/${kodePengajuan}`,
        method: 'GET',
        success: function(data) {
            const { lab_id, tanggal_pengajuan, keperluan } = data;

            // Update form dengan data yang diterima
            $('#lab_id').val(lab_id).trigger('change');
            $('#keperluanPengajuan').val(keperluan);
            $('#tanggalPengajuan').val(tanggal_pengajuan.join(', ')); // Menggabungkan tanggal

            // Inisialisasi flatpickr dengan tanggal yang diterima
            initFlatpickr(tanggal_pengajuan);

            // Tampilkan form update
            $('#formPengajuanUpdateContainer').show();
        },
        error: function() {
            alert('Gagal memuat data untuk pengajuan.');
        }
    });
}
