import select2 from 'select2';
select2();

// import 'datatables.net-fixedcolumns';
// import 'datatables.net-fixedcolumns-bs5';

import { initLaboratoriumDatatable } from "./laboratorium/datatables-laboratorium.js";
import { initSelect2Store } from "./laboratorium/form-laboratorium-store.js";
import { initDatatablesValueToModalUpdate } from "./laboratorium/form-laboratorium-update.js";
import { initSoftDeleteLaboratoriumModal } from './laboratorium/form-laboratorium-soft-delete.js';

document.addEventListener("DOMContentLoaded", () => {
    initLaboratoriumDatatable();
    initSelect2Store();
    initDatatablesValueToModalUpdate();
    initSoftDeleteLaboratoriumModal();
});
