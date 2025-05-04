import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/themes/airbnb.css";

let flatpickrMultiInstance, flatpickrRangeInstance;
let hariOperasionalGlobal = [];
let jamOperasionalMap = {};

// Mapping hari untuk menampilkan nama hari
const hariMapping = {
  0: 'Minggu', 1: 'Senin', 2: 'Selasa', 3: 'Rabu',
  4: 'Kamis', 5: 'Jumat', 6: 'Sabtu'
};

// Inisialisasi form pengajuan booking
export function initFormPengajuanBookingStore() {
  initializeSelects(); // Inisialisasi select2 untuk dropdown
  initializeFlatpickrs(); // Inisialisasi flatpickr untuk datepicker
  bindEventListeners(); // Menambahkan event listeners pada elemen-elemen
}

// Inisialisasi elemen select2 untuk dropdown lokasi dan laboratorium
function initializeSelects() {
  $('#lokasiPengajuanBooking').select2({ theme: "bootstrap-5", placeholder: "Pilih Lokasi" });

  $('#laboratoriumPengajuanBooking')
    .select2({ theme: "bootstrap-5", placeholder: "Pilih Laboratorium" })
    .prop('disabled', true); // Menonaktifkan select2 laboratorium saat tidak ada lokasi
}

// Penngaturan Flatpickr Multi Untuk Disable yang Tidak ada Di Hari Operasional
function getFlatpickrMultiConfig() {
  return {
    minDate: "today",
    altInput: true,
    altFormat: "d F Y",
    dateFormat: "Y-m-d",
    locale: Indonesian,
    mode: "multiple",
    disable: [
      function (date) {
        const allowedDays = hariOperasionalGlobal.map(h => Number(h.hari_operasional));
        return !allowedDays.includes(date.getDay()); // True berarti akan dinonaktifkan
      }
    ],
    onReady: (_, __, instance) => instance._input.setAttribute("disabled", true)
  };
}

// Inisialisasi flatpickr dengan konfigurasi tertentu untuk tanggal multi dan range
function initializeFlatpickrs() {
  // Inisialisasi awal (belum ada hari operasional), disable semuanya dulu
  flatpickrMultiInstance = flatpickr("#tanggalMulti", {
    ...getFlatpickrMultiConfig(),
    disable: [() => true] // Disable semua dulu sampai data hari operasional diambil
  });

  flatpickrRangeInstance = flatpickr("#tanggalRange", {
    minDate: "today",
    altInput: true,
    altFormat: "d F Y",
    dateFormat: "Y-m-d",
    locale: Indonesian,
    mode: "range",
    onReady: (_, __, instance) => instance._input.setAttribute("disabled", true)
  });
}

// Menambahkan event listeners untuk elemen-elemen tertentu
function bindEventListeners() {
  $('input[name="mode_tanggal"]').on('change', handleModeToggle); // Event untuk mode tanggal
  $('#lokasiPengajuanBooking').on('change', handleLokasiChange); // Event untuk perubahan lokasi
  $('#tanggalMulti').on('change', handleMultiDateChange); // Event untuk perubahan tanggal multi
  $('#tanggalRange').on('change', handleRangeOrCheckboxChange); // Event untuk perubahan range tanggal
  $('#checkboxHariOperasional').on('change', '.checkbox-hari', handleRangeOrCheckboxChange); // Event untuk checkbox hari operasional
}

// Menangani perubahan mode tanggal (multi atau range)
function handleModeToggle() {
  const mode = $('input[name="mode_tanggal"]:checked').val();

  // Menyembunyikan atau menampilkan elemen-elemen sesuai dengan mode yang dipilih
  $('#multiDateContainer').toggleClass('d-none', mode !== 'multi');
  $('#rangeDateContainer, #hariOperasionalContainer, #jamOperasionalContainer')
    .toggleClass('d-none', mode === 'multi');
  $('#jamPerTanggalContainer').toggleClass('d-none', mode !== 'multi').empty();

  clearDateAndJamFields(); // Membersihkan tanggal dan jam
}

// Menangani perubahan lokasi
async function handleLokasiChange() {
  const lokasiId = $('#lokasiPengajuanBooking').val();
  if (!lokasiId) return resetAll();

  setInputsDisabled(true);
  clearDateAndJamFields();

  try {
    hariOperasionalGlobal = await fetchJSON(`/pengajuan/api/data-hari-operasional/${lokasiId}`);
    
    const jamPromises = hariOperasionalGlobal.map(h => fetchJSON(`/pengajuan/api/data-jam-operasional/${h.id}`));
    const jamResults = await Promise.all(jamPromises);
    hariOperasionalGlobal.forEach((hari, i) => jamOperasionalMap[hari.id] = jamResults[i]);

    renderHariOperasionalCheckboxes();

    flatpickrMultiInstance.destroy();
    flatpickrMultiInstance = flatpickr("#tanggalMulti", getFlatpickrMultiConfig());

    const laboratorium = await fetchJSON(`/pengajuan/api/data-laboratorium/${lokasiId}`);
    const options = laboratorium.map(lab => ({ id: lab.id, text: lab.nama_laboratorium }));

    $('#laboratoriumPengajuanBooking')
      .empty()
      .select2({ theme: "bootstrap-5", placeholder: "Pilih Laboratorium", data: options })
      .prop('disabled', false);
  } catch (error) {
    console.error("Gagal memuat data lokasi:", error);
    resetAll();
  } finally {
    setInputsDisabled(false);
  }
}

// Menangani perubahan pada tanggal multi
function handleMultiDateChange() {
  const tanggalList = flatpickrMultiInstance.selectedDates;
  const $container = $('#jamPerTanggalContainer').empty(); // Bersihkan kontainer jam

  tanggalList.forEach(tgl => {
    const day = tgl.getDay();
    const matched = hariOperasionalGlobal.find(h => Number(h.hari_operasional) === day); // Cari hari yang cocok
    if (!matched) return; // Jika tidak ditemukan, lanjutkan ke tanggal berikutnya

    const jamList = jamOperasionalMap[matched.id] || [];
    $container.append(generateJamSelect(tgl, jamList)); // Tambahkan select jam untuk tanggal ini
  });

  $('.select2-jam').select2({ theme: 'bootstrap-5', placeholder: 'Pilih Sesi Jam' }); // Inisialisasi select2 untuk sesi jam
}

// Menangani perubahan pada range tanggal atau checkbox hari
function handleRangeOrCheckboxChange() {
  const range = flatpickrRangeInstance.selectedDates;
  if (range.length !== 2) return; // Pastikan dua tanggal dipilih untuk range

  const start = new Date(range[0]);
  const end = new Date(range[1]);
  const selectedHari = $('.checkbox-hari:checked').map(function () {
    return { hari: Number($(this).val()), id: $(this).data('id') };
  }).get(); // Ambil hari yang dicentang dari checkbox

  const $container = $('#jamOperasionalContainer').empty(); // Bersihkan kontainer jam

  // Iterasi dari tanggal awal hingga tanggal akhir untuk menambahkan sesi jam
  for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
    const current = new Date(d);
    const match = selectedHari.find(h => h.hari === current.getDay()); // Cari hari yang cocok
    if (!match) continue; // Jika tidak cocok, lanjutkan

    const jamList = jamOperasionalMap[match.id] || [];
    $container.append(generateJamSelect(current, jamList)); // Tambahkan select jam untuk tanggal ini
  }

  $('.select2-jam').select2({ theme: 'bootstrap-5', placeholder: 'Pilih Sesi Jam' }); // Inisialisasi select2 untuk sesi jam
}

// Menampilkan checkbox untuk memilih hari operasional
function renderHariOperasionalCheckboxes() {
  const $container = $('#checkboxHariOperasional').empty().addClass('row');
  hariOperasionalGlobal.forEach(hari => {
    const html = `
      <div class="col-6 col-md-4 col-lg-3">
        <div class="form-check">
          <input class="form-check-input checkbox-hari"
                 type="checkbox"
                 value="${hari.hari_operasional}"
                 data-id="${hari.id}"
                 id="hari-${hari.id}">
          <label class="form-check-label" for="hari-${hari.id}">
            ${hariMapping[hari.hari_operasional]}
          </label>
        </div>
      </div>`;
    $container.append(html); // Menambahkan checkbox untuk hari operasional
  });
}

// Membuat elemen select untuk memilih jam operasional
function generateJamSelect(tanggal, jamList) {
  const formatted = tanggal.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
  const dateKey = tanggal.toISOString().split('T')[0]; // Format tanggal untuk key
  const options = jamList.map(j => {
    const label = `${j.jam_mulai} - ${j.jam_selesai}`;
    return `<option value="${label}">${label}</option>`; // Menghasilkan option untuk setiap sesi jam
  }).join('');

  return `
    <div class="mb-3">
      <label class="form-label">Sesi Jam untuk ${formatted}</label>
      <select name="jam[${dateKey}][]" class="form-select select2-jam" multiple>
        ${options}
      </select>
    </div>`;
}

// Mengambil data JSON dari server
function fetchJSON(url) {
  return fetch(url).then(res => {
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  });
}

// Menonaktifkan atau mengaktifkan input (lokasi, laboratorium, dan jam)
function setInputsDisabled(disabled) {
  $('#lokasiPengajuanBooking, #laboratoriumPengajuanBooking').prop('disabled', disabled);
  $('.checkbox-hari, .select2-jam').prop('disabled', disabled);

  flatpickrMultiInstance._input.toggleAttribute('disabled', disabled);
  flatpickrRangeInstance._input.toggleAttribute('disabled', disabled);
}

// Membersihkan field tanggal dan jam
function clearDateAndJamFields() {
  flatpickrMultiInstance.clear();
  flatpickrRangeInstance.clear();
  $('#jamPerTanggalContainer, #jamOperasionalContainer').empty();
  $('.checkbox-hari').prop('checked', false);
}

// Mereset semua data dan tampilan
function resetAll() {
  hariOperasionalGlobal = [];
  jamOperasionalMap = {};

  $('#checkboxHariOperasional, #jamOperasionalContainer, #jamPerTanggalContainer').empty();
  $('#laboratoriumPengajuanBooking').empty().prop('disabled', true).trigger('change');

  clearDateAndJamFields();
  setInputsDisabled(false);
}
