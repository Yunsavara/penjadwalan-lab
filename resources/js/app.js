// Bootstrap 5
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

// Jquery Global File
import $ from 'jquery';
window.$ = $;
window.jQuery = $;

// Script vanilla
import './script';
import './all-role/riwayat/pengajuan/form-pengajuan-store'
import './all-role/riwayat/pengajuan/form-pengajuan-edit'
import './all-role/riwayat/pengajuan/datatables-pengajuan'
import './all-role/riwayat/jadwal/datatables-jadwal'
import './all-role/generate-jadwal/datatables-generate-jadwal'


// Laboran
import './laboran/generate-jadwal/datatables-generate-jadwal'
import './laboran/pengajuan/datatables-pengajuan'
import './laboran/booking-log/datatable-booking-log'

// Flatpickr
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
