import { Modal } from "bootstrap";

export function batalkanJadwal(bookingDetailId) {
    let modalElement = document.getElementById("modalBatalkanJadwal");

    // Set ID booking detail di modal
    modalElement.querySelector("#inputBookingDetailId").value = bookingDetailId;

    // Tampilkan modal
    let modalInstance = new Modal(modalElement);
    modalInstance.show();
}
