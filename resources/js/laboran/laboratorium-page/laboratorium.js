import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select';

import select2 from 'select2';
select2();

// import 'datatables.net-fixedcolumns';
// import 'datatables.net-fixedcolumns-bs5';

// Laboratorium
import { initLaboratoriumDatatable } from "./laboratorium/datatables-laboratorium.js";
import { initSelect2Store, errorStoreModalLaboratorium } from "./laboratorium/form-laboratorium-store.js";
import { initDatatablesValueToModalUpdateLab, errorUpdateModalLaboratorium } from "./laboratorium/form-laboratorium-update.js";
import { initSoftDeleteLaboratoriumModal } from './laboratorium/form-laboratorium-soft-delete.js';

// Jenis Laboratorium
import { initJenisLabDatatable } from './jenis-lab/datatables-jenis-lab.js';
import { errorStoreModalJenisLab } from './jenis-lab/form-jenis-lab-store.js';
import { errorUpdateModalJenisLab, initDatatablesValueToModalUpdateJenisLab } from './jenis-lab/form-jenis-lab.update.js';
import { initSoftDeleteJenisLabModal } from './jenis-lab/form-jenis-lab-soft-delete.js';


document.addEventListener("DOMContentLoaded", () => {
    // Laboratorium
    initLaboratoriumDatatable();
    initSelect2Store();
    initDatatablesValueToModalUpdateLab();
    initSoftDeleteLaboratoriumModal();

    // Jenis Laboratorium
    initJenisLabDatatable();
    initDatatablesValueToModalUpdateJenisLab();
    initSoftDeleteJenisLabModal();

    // Kalau Gagal Input Form
    errorStoreModalLaboratorium();
    errorUpdateModalLaboratorium();
    errorStoreModalJenisLab();
    errorUpdateModalJenisLab();
});
