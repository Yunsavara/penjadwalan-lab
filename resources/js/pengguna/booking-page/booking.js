import { applyOldFormData, initSelect2, initTanggalRangePicker, registerEventListeners } from "./pengajuan/form-pengajuan-booking-store";

document.addEventListener("DOMContentLoaded", () => {
  initSelect2();
  initTanggalRangePicker();
  registerEventListeners();
  applyOldFormData();
});