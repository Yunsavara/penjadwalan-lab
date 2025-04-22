import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select';

import select2 from 'select2';
select2();

<<<<<<< Updated upstream
=======
// Pengguna
import { initPenggunaDatatable } from './pengguna/datatables-pengguna';
import { errorStoreModalPengguna, formPasswordViewStore, initSelect2Store } from './pengguna/form-pengguna-store';
import { initSoftDeletePenggunaModal } from './pengguna/form-pengguna-soft-delete';
>>>>>>> Stashed changes

// Peran
import { initPeranDatatable } from './peran/datatables-peran';
import { errorUpdateModalPeran, initDatatablesValueToModalUpdatePeran } from './peran/form-peran-update';
import { initSoftDeletePeranModal } from './peran/form-peran-soft-delete';

// Lokasi
import { initLokasiDatatable } from './lokasi/datatables-lokasi';
import { errorStoreModalLokasi } from './lokasi/form-lokasi-store';
import { errorUpdateModalLokasi, initDatatablesValueToModalUpdateLokasi } from './lokasi/form-lokasi-update';
import { initSoftDeleteLokasiModal } from './lokasi/form-lokasi-soft-delete';
import { errorStoreModalPeran } from './peran/form-peran-store';


document.addEventListener("DOMContentLoaded", () => {

<<<<<<< Updated upstream
=======
    // Pengguna
    initPenggunaDatatable();
    initSelect2Store();
    initDatatablesValueToModalUpdatePengguna();
    formPasswordViewStore();
    formPasswordViewUpdate();
    initSoftDeletePenggunaModal();

>>>>>>> Stashed changes
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
});
