import { initializeAutoTimeSelect, initializeDatePicker, initializeSelect2 } from "./pengajuan-page/form-pengajuan-store";

document.addEventListener("DOMContentLoaded", () => {
    initializeDatePicker();
    initializeSelect2();
    initializeAutoTimeSelect();
});
