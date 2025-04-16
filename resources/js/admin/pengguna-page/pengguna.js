import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select';

import select2 from 'select2';
select2();


// Lokasi
import { initLokasiDatatable } from './lokasi/datatables-lokasi';
import { errorStoreModalLokasi } from './lokasi/form-lokasi-store';
import { errorUpdateModalLokasi, initDatatablesValueToModalUpdateLokasi } from './lokasi/form-lokasi-update';
import { initSoftDeleteLokasiModal } from './lokasi/form-lokasi-soft-delete';


document.addEventListener("DOMContentLoaded", () => {

    // Lokasi
    initLokasiDatatable();
    initDatatablesValueToModalUpdateLokasi();
    initSoftDeleteLokasiModal();

    // Kalau Gagal Input Form
    errorStoreModalLokasi();
    errorUpdateModalLokasi();
});
