import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select';

import select2 from 'select2';
select2();

// Pengguna
import { initPenggunaDatatable } from './pengguna/datatables-pengguna';
import { errorStoreModalPengguna, formPasswordViewStore, initSelect2Store } from './pengguna/form-pengguna-store';


// Peran
import { initPeranDatatable } from './peran/datatables-peran';
import { errorUpdateModalPeran, initDatatablesValueToModalUpdatePeran } from './peran/form-peran-update';
import { initSoftDeletePeranModal } from './peran/form-peran-soft-delete';
import { errorUpdateModalPengguna, formPasswordViewUpdate, initDatatablesValueToModalUpdatePengguna } from './pengguna/form-pengguna-update';

// Lokasi
import { initLokasiDatatable } from './lokasi/datatables-lokasi';
import { errorStoreModalLokasi } from './lokasi/form-lokasi-store';
import { errorUpdateModalLokasi, initDatatablesValueToModalUpdateLokasi } from './lokasi/form-lokasi-update';
import { initSoftDeleteLokasiModal } from './lokasi/form-lokasi-soft-delete';
import { errorStoreModalPeran } from './peran/form-peran-store';


document.addEventListener("DOMContentLoaded", () => {

    // Pengguna
    initPenggunaDatatable();
    initSelect2Store();
    initDatatablesValueToModalUpdatePengguna();
    formPasswordViewStore();
    formPasswordViewUpdate();

    // Peran
    initPeranDatatable();
    initDatatablesValueToModalUpdatePeran();
    initSoftDeletePeranModal();

    // Lokasi
    initLokasiDatatable();
    initDatatablesValueToModalUpdateLokasi();
    initSoftDeleteLokasiModal();

    // Kalau Gagal Input Form
    errorStoreModalLokasi();
    errorUpdateModalLokasi();
    errorStoreModalPeran();
    errorUpdateModalPeran();
    errorStoreModalPengguna();
    errorUpdateModalPengguna();
});
