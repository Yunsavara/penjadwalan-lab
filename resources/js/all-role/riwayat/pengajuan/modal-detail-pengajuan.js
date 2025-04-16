import { Modal } from 'bootstrap';

export function showDetailModal(kodePengajuan) {
    $.ajax({
        url: `/jadwal/pengajuan-detail/${kodePengajuan}`,
        type: "GET",
        success: function (response) {
            if (response.success) {
                let data = response.data;

                // Update elemen modal langsung
                document.getElementById("modalKodePengajuan").textContent = data.kode_pengajuan;
                document.getElementById("modalStatusPengajuan").textContent = data.status;

                // Isi daftar lab & jadwal
                let labList = document.getElementById("modalLabPengajuan");
                labList.innerHTML = ""; // Kosongkan dulu sebelum diisi ulang
                data.details.forEach(detail => {
                    let li = document.createElement("li");
                    li.textContent = `${detail.lab} (${detail.tanggal}, ${detail.jam_mulai} - ${detail.jam_selesai})`;
                    labList.appendChild(li);
                });

                // Isi log status
                let logList = document.getElementById("modalLogsPengajuan");
                logList.innerHTML = ""; // Kosongkan sebelum isi ulang

                data.logs.forEach(log => {
                    let li = document.createElement("li");
                    li.innerHTML = `<strong>${log.status}</strong> oleh <em>${log.user}</em> pada ${log.waktu} <br> Catatan: ${log.catatan}`;
                    logList.appendChild(li);
                });

                // Tampilkan modal
                const modalElement = document.getElementById('modalDetailPengajuan');
                const modal = new Modal(modalElement);
                modal.show();
            } else {
                alert("Gagal mengambil data detail.");
            }
        },
        error: function () {
            alert("Terjadi kesalahan saat mengambil data.");
        }
    });
}
