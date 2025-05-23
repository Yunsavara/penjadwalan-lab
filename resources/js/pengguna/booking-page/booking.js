import { initFormPengajuanBookingStore } from "./pengajuan/form-pengajuan-booking-store"
import { initPengajuanBookingDatatable } from "./pengajuan/datatables-pengajuan-booking";
import { batalkanPengajuanBooking } from "./pengajuan/aksi-pengajuan-booking";

import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select';

import select2 from "select2";
select2();

document.addEventListener("DOMContentLoaded", () => {
  initFormPengajuanBookingStore();
  initPengajuanBookingDatatable();
  batalkanPengajuanBooking();
});
