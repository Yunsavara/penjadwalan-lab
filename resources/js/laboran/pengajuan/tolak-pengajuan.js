import { Modal } from "bootstrap";

export function showTolakModal(kodePengajuan) {
    let modalElement = document.getElementById("modalLaboranTolakPengajuan");

    // Tampilkan modal
    let modalInstance = new Modal(modalElement);
    modalInstance.show();

    // Ambil elemen untuk mengisi kode pengajuan
    let modalKodePengajuan = document.getElementById("modalLaboranTolakPengajuanKode");
    modalKodePengajuan.textContent = kodePengajuan;

    // Isi input hidden dengan kode pengajuan
    let inputKodePengajuan = document.getElementById("inputLaboranTolakKodePengajuan");
    inputKodePengajuan.value = kodePengajuan;
}
