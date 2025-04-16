import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select';

import { initPeranDatatable } from "./peran/datatables-peran.js";

document.addEventListener("DOMContentLoaded", () => {
    initPeranDatatable();
});
