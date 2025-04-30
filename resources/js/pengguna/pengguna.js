import { handleGenerateForm, initFlatpickrTanggal, initSelect2 } from "./pengajuan-page/form-pengajuan-booking-store"

document.addEventListener("DOMContentLoaded", () => {
    initSelect2();
    initFlatpickrTanggal();
    handleGenerateForm();
})