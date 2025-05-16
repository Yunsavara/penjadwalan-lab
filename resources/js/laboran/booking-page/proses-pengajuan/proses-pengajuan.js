import { initProsesPengajuanDatatable } from './datatables-proses-pengajuan';
import { detailProsesPengajuan, terimaProsesPengajuan, tolakProsesPengajuan } from './aksi-proses-pengajuan';

import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select';

import select2 from "select2";
select2();

document.addEventListener("DOMContentLoaded", () => {
    initProsesPengajuanDatatable();
    detailProsesPengajuan();
    terimaProsesPengajuan();
    tolakProsesPengajuan();
});