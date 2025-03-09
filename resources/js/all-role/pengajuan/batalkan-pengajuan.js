import { Modal } from "bootstrap";

export function batalkanPengajuan(kodePengajuan) {
    let modalElement = document.getElementById("modalBatalkanPengajuan");

    if (!modalElement) {
        console.error("Modal tidak ditemukan!");
        return;
    }

    // Set kode pengajuan di modal
    modalElement.querySelector("#modalBatalKode").textContent = kodePengajuan;
    modalElement.querySelector("#inputKodePengajuan").value = kodePengajuan;

    // Tampilkan modal
    let modalInstance = new Modal(modalElement);
    modalInstance.show();
}
