import { initFormPengajuanBookingStore } from "./pengajuan/form-pengajuan-booking-store"

import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select';

import select2 from "select2";
import { initPengajuanBookingDatatable } from "./pengajuan/datatables-pengajuan-booking";
select2();

document.addEventListener("DOMContentLoaded", () => {
  initFormPengajuanBookingStore();
  initPengajuanBookingDatatable();
});