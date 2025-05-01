import { applyOldFormData, initSelect2, initTanggalRangePicker, registerEventListeners, updateHariCheckbox, updateLabOptions } from "./pengajuan-page/form-pengajuan-booking-store";

document.addEventListener("DOMContentLoaded", () => {
  initSelect2();
  initTanggalRangePicker();
  registerEventListeners();
  applyOldFormData();
});