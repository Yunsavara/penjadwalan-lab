import { initLaboratoriumDatatable } from "./laboratorium/datatables-laboratorium.js";
import { initSelect2 } from "./laboratorium/form-laboratorium.js";
import 'datatables.net-fixedcolumns';
import 'datatables.net-fixedcolumns-bs5';

document.addEventListener("DOMContentLoaded", () => {
    initLaboratoriumDatatable();
    initSelect2();
});
