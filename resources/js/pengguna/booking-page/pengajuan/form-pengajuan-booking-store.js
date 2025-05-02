import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/themes/airbnb.css";

let flatpickrMultiInstance, flatpickrRangeInstance;
let hariOperasionalGlobal = [];
let jamOperasionalMap = {};

// Mapping eksplisit 0-6 (Minggu hingga Sabtu) ke nama hari
const hariMapping = {
  0: 'Minggu',
  1: 'Senin',
  2: 'Selasa',
  3: 'Rabu',
  4: 'Kamis',
  5: 'Jumat',
  6: 'Sabtu'
};

// Inisialisasi utama
export function initFormPengajuanBookingStore() {
  initSelect2Lokasi();
  initFlatpickrTanggal();
  bindModeTanggalToggle();
  bindLokasiChange();
  bindTanggalMultiChange();
  bindRangeChangeAndCheckboxEvents();
}

// Inisialisasi Select2 untuk lokasi
function initSelect2Lokasi() {
  $('#lokasiPengajuanBooking').select2({
    theme: "bootstrap-5",
    placeholder: "Pilih Lokasi"
  });
}

// Inisialisasi Flatpickr untuk input tanggal
function initFlatpickrTanggal() {
  flatpickrMultiInstance = flatpickr("#tanggalMulti", {
    minDate: "today",
    altInput: true,
    altFormat: "d F Y",
    mode: "multiple",
    dateFormat: "Y-m-d",
    locale: Indonesian
  });

  flatpickrRangeInstance = flatpickr("#tanggalRange", {
    minDate: "today",
    altInput: true,
    altFormat: "d F Y",
    mode: "range",
    dateFormat: "Y-m-d",
    locale: Indonesian
  });
}

// Toggle mode multi dan range
function bindModeTanggalToggle() {
  $('input[name="mode_tanggal"]').on('change', function () {
    const mode = $(this).val();

    if (mode === 'multi') {
      $('#multiDateContainer').removeClass('d-none');
      $('#rangeDateContainer, #hariOperasionalContainer, #jamOperasionalContainer').addClass('d-none');
      $('#jamPerTanggalContainer').removeClass('d-none').empty();
    } else {
      $('#multiDateContainer').addClass('d-none');
      $('#rangeDateContainer, #hariOperasionalContainer, #jamOperasionalContainer').removeClass('d-none');
      $('#jamPerTanggalContainer').addClass('d-none').empty();
    }
  });
}

// Fetch hari dan jam operasional berdasarkan lokasi
function bindLokasiChange() {
  $('#lokasiPengajuanBooking').on('change', async function () {
    const lokasiId = $(this).val();
    if (!lokasiId) return;

    const hariRes = await fetch(`/pengajuan/api/data-hari-operasional/${lokasiId}`);
    hariOperasionalGlobal = await hariRes.json();

    const jamPromises = hariOperasionalGlobal.map(hari =>
      fetch(`/pengajuan/api/data-jam-operasional/${hari.id}`).then(res => res.json())
    );

    const jamResults = await Promise.all(jamPromises);

    hariOperasionalGlobal.forEach((hari, i) => {
      jamOperasionalMap[hari.id] = jamResults[i];
    });

    generateCheckboxHariOperasional();
  });
}

// Generate checkbox hari operasional
function generateCheckboxHariOperasional() {
  const $container = $('#checkboxHariOperasional');
  $container.empty();

  hariOperasionalGlobal.forEach(hari => {
    $container.append(`
      <div class="form-check">
        <input class="form-check-input checkbox-hari" type="checkbox" value="${hari.hari_operasional}" data-id="${hari.id}" id="hari-${hari.id}">
        <label class="form-check-label" for="hari-${hari.id}">${hariMapping[hari.hari_operasional]}</label>
      </div>
    `);
  });
}

// Event saat tanggal multi diubah
function bindTanggalMultiChange() {
  $('#tanggalMulti').on('change', function () {
    const tanggalList = flatpickrMultiInstance.selectedDates;
    $('#jamPerTanggalContainer').empty();

    tanggalList.forEach(tgl => {
      const day = tgl.getDay();
      const matchedHari = hariOperasionalGlobal.find(h => Number(h.hari_operasional) === day);
      if (!matchedHari) return;

      const jamList = jamOperasionalMap[matchedHari.id] || [];
      const selectHtml = generateJamSelect(tgl, jamList); // Kirim objek Date langsung

      $('#jamPerTanggalContainer').append(selectHtml);
    });

    $('.select2-jam').select2({ theme: 'bootstrap-5', placeholder: 'Pilih Sesi Jam' });
  });
}

// Binding event untuk range dan checkbox hari
function bindRangeChangeAndCheckboxEvents() {
  $('#tanggalRange').on('change', handleRangeAndCheckbox);
  $('#checkboxHariOperasional').on('change', '.checkbox-hari', handleRangeAndCheckbox);
}

// Handle saat tanggal range dan hari dicentang
function handleRangeAndCheckbox() {
  const range = flatpickrRangeInstance.selectedDates;
  if (range.length !== 2) return;

  const start = new Date(range[0]);
  const end = new Date(range[1]);

  const selectedHari = $('.checkbox-hari:checked').map(function () {
    return {
      hari: Number($(this).val()),
      id: $(this).data('id')
    };
  }).get();

  $('#jamOperasionalContainer').empty();

  for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
    const current = new Date(d);
    const day = current.getDay();
    const matched = selectedHari.find(h => h.hari === day);
    if (!matched) continue;

    const jamList = jamOperasionalMap[matched.id] || [];
    const selectHtml = generateJamSelect(current, jamList); // Kirim objek Date langsung

    $('#jamOperasionalContainer').append(selectHtml);
  }

  $('.select2-jam').select2({ theme: 'bootstrap-5', placeholder: 'Pilih Sesi Jam' });
}

// Generate elemen select untuk sesi jam
function generateJamSelect(tanggal, jamList) {
  // Mengubah tanggal menjadi format "23 Juni 2025"
  const formattedDate = tanggal.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });

  const options = jamList.map(j => {
    const label = `${j.jam_mulai} - ${j.jam_selesai}`;
    return `<option value="${label}">${label}</option>`;
  }).join('');

  return `
    <div class="mb-3">
      <label class="form-label">Sesi Jam untuk ${formattedDate}</label>
      <select name="jam[${tanggal.toISOString().split('T')[0]}][]" class="form-select select2-jam" multiple>
        ${options}
      </select>
    </div>
  `;
}

// (Opsional) Fungsi utilitas untuk mendapatkan nama hari dari indeks
function getHariString(index) {
  return hariMapping[index];
}
