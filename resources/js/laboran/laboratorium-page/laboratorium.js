import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select';

import select2 from 'select2';
select2();

// import 'datatables.net-fixedcolumns';
// import 'datatables.net-fixedcolumns-bs5';

// Laboratorium
import { initLaboratoriumDatatable } from "./laboratorium/datatables-laboratorium.js";
import { initSelect2Store } from "./laboratorium/form-laboratorium-store.js";
import { initDatatablesValueToModalUpdateLab } from "./laboratorium/form-laboratorium-update.js";
import { initSoftDeleteLaboratoriumModal } from './laboratorium/form-laboratorium-soft-delete.js';

// Jenis Laboratorium
import { initJenisLaboratoriumDatatable } from './jenis-lab/datatables-jenis-lab.js';
import { errorStoreModalJenisLab } from './jenis-lab/form-jenis-lab-store.js';
import { errorUpdateModalJenisLab, initDatatablesValueToModalUpdateJenisLab } from './jenis-lab/form-jenis-lab.update.js';


document.addEventListener("DOMContentLoaded", () => {
    // Laboratorium
    initLaboratoriumDatatable();
    initSelect2Store();
    initDatatablesValueToModalUpdateLab();
    initSoftDeleteLaboratoriumModal();

    // Jenis Laboratorium
    initJenisLaboratoriumDatatable();
    initDatatablesValueToModalUpdateJenisLab();

    // Kalau Gagal Input Form
    errorStoreModalJenisLab();
    errorUpdateModalJenisLab();
});
