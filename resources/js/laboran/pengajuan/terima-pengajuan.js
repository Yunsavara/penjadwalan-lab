import { Modal } from "bootstrap";

export function showTerimaModal(kodePengajuan) {
    let modalElement = document.getElementById("modalLaboranTerimaPengajuan");

    // Tampilkan modal
    let modalInstance = new Modal(modalElement);
    modalInstance.show();

    // Ambil elemen untuk mengisi kode pengajuan
    let modalKodePengajuan = document.getElementById("modalLaboranPengajuanKode");
    modalKodePengajuan.textContent = kodePengajuan;

    // Isi input hidden dengan kode pengajuan
    let inputKodePengajuan = document.getElementById("inputLaboranKodePengajuan");
    inputKodePengajuan.value = kodePengajuan;
}
