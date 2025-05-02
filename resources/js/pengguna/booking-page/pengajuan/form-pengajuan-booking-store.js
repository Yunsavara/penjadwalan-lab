import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/themes/airbnb.css";

let flatpickrMultiInstance, flatpickrRangeInstance;
let hariOperasionalGlobal = [];
let jamOperasionalMap = {};

// Mapping hari dari 0 (Minggu) sampai 6 (Sabtu)
const hariMapping = {
  0: 'Minggu',
  1: 'Senin',
  2: 'Selasa',
  3: 'Rabu',
  4: 'Kamis',
  5: 'Jumat',
  6: 'Sabtu'
};

export function initFormPengajuanBookingStore() {
  initSelect2Lokasi();
  initFlatpickrTanggal();
  bindModeTanggalToggle();
  bindLokasiChange();
  bindTanggalMultiChange();
  bindRangeChangeAndCheckboxEvents();
}

function initSelect2Lokasi() {
  $('#lokasiPengajuanBooking').select2({
    theme: "bootstrap-5",
    placeholder: "Pilih Lokasi",
  });

  $('#laboratoriumPengajuanBooking').select2({
    theme: "bootstrap-5",
    placeholder: "Pilih Laboratorium"
  }).prop('disabled', true);
}

function initFlatpickrTanggal() {
  flatpickrMultiInstance = flatpickr("#tanggalMulti", {
    minDate: "today",
    altInput: true,
    altFormat: "d F Y",
    mode: "multiple",
    dateFormat: "Y-m-d",
    locale: Indonesian,
    onReady: function(selectedDates, dateStr, instance) {
      instance._input.setAttribute("disabled", true);
    }
  });

  flatpickrRangeInstance = flatpickr("#tanggalRange", {
    minDate: "today",
    altInput: true,
    altFormat: "d F Y",
    mode: "range",
    dateFormat: "Y-m-d",
    locale: Indonesian,
    onReady: function(selectedDates, dateStr, instance) {
      instance._input.setAttribute("disabled", true);
    }
  });
}


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

    flatpickrMultiInstance.clear();
    flatpickrRangeInstance.clear();
    $('#jamPerTanggalContainer, #jamOperasionalContainer').empty();
    $('.checkbox-hari').prop('checked', false);
  });
}

async function bindLokasiChange() {
  $('#lokasiPengajuanBooking').on('change', async function () {
    const lokasiId = $(this).val();

    if (!lokasiId) {
      resetAllData();
      return;
    }

    // Disable semua input saat proses pengambilan data
    $('#lokasiPengajuanBooking, #laboratoriumPengajuanBooking').prop('disabled', true);
    $('.checkbox-hari, .select2-jam').prop('disabled', true);
    flatpickrMultiInstance._input.setAttribute("disabled", true);
    flatpickrRangeInstance._input.setAttribute("disabled", true);

    try {
      // Fetch hari operasional
      const hariRes = await fetch(`/pengajuan/api/data-hari-operasional/${lokasiId}`);
      hariOperasionalGlobal = await hariRes.json();

      // Fetch jam operasional berdasarkan hari
      const jamPromises = hariOperasionalGlobal.map(hari =>
        fetch(`/pengajuan/api/data-jam-operasional/${hari.id}`).then(res => res.json())
      );
      const jamResults = await Promise.all(jamPromises);
      hariOperasionalGlobal.forEach((hari, i) => {
        jamOperasionalMap[hari.id] = jamResults[i];
      });

      generateCheckboxHariOperasional();

      // Fetch laboratorium
      const labRes = await fetch(`/pengajuan/api/data-laboratorium/${lokasiId}`);
      const laboratorium = await labRes.json();
      const options = laboratorium.map(lab => ({ id: lab.id, text: lab.nama_laboratorium }));

      $('#laboratoriumPengajuanBooking').empty().select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Laboratorium",
        data: options,
      });

      resetAndEnableElements();

    } catch (error) {
      console.error('Error fetching data:', error);
      resetAllData();
      $('#lokasiPengajuanBooking, #laboratoriumPengajuanBooking').prop('disabled', false);
    } finally {
      $('#tanggalMulti, #tanggalRange, .checkbox-hari, #jamPerTanggalContainer, #jamOperasionalContainer').prop('disabled', false);
    }
  });
}

function bindTanggalMultiChange() {
  $('#tanggalMulti').on('change', function () {
    const tanggalList = flatpickrMultiInstance.selectedDates;
    $('#jamPerTanggalContainer').empty();

    tanggalList.forEach(tgl => {
      const day = tgl.getDay();
      const matchedHari = hariOperasionalGlobal.find(h => Number(h.hari_operasional) === day);
      if (!matchedHari) return;

      const jamList = jamOperasionalMap[matchedHari.id] || [];
      const selectHtml = generateJamSelect(tgl, jamList);
      $('#jamPerTanggalContainer').append(selectHtml);
    });

    $('.select2-jam').select2({ theme: 'bootstrap-5', placeholder: 'Pilih Sesi Jam' });
  });
}

function bindRangeChangeAndCheckboxEvents() {
  $('#tanggalRange').on('change', handleRangeAndCheckbox);
  $('#checkboxHariOperasional').on('change', '.checkbox-hari', handleRangeAndCheckbox);
}

function resetAllData() {
  hariOperasionalGlobal = [];
  jamOperasionalMap = {};
  $('#checkboxHariOperasional, #jamOperasionalContainer, #jamPerTanggalContainer').empty();
  $('#laboratoriumPengajuanBooking').empty().prop('disabled', true).trigger('change');
  flatpickrMultiInstance.clear();
  flatpickrRangeInstance.clear();
  $('.checkbox-hari').prop('checked', false);
}

function resetAndEnableElements() {
  $('#lokasiPengajuanBooking, #laboratoriumPengajuanBooking').prop('disabled', false);
  $('.checkbox-hari, .select2-jam').prop('disabled', true);
  flatpickrMultiInstance._input.removeAttribute("disabled");
  flatpickrRangeInstance._input.removeAttribute("disabled");

  flatpickrMultiInstance.clear();
  flatpickrRangeInstance.clear();
  $('#jamPerTanggalContainer, #jamOperasionalContainer').empty();
  $('.checkbox-hari').prop('checked', false);
}

function generateCheckboxHariOperasional() {
  const $container = $('#checkboxHariOperasional');
  $container.empty().addClass('row');

  hariOperasionalGlobal.forEach(hari => {
    $container.append(`
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
      </div>
    `);
  });
}


function handleRangeAndCheckbox() {
  const range = flatpickrRangeInstance.selectedDates;
  if (range.length !== 2) return;

  const start = new Date(range[0]);
  const end = new Date(range[1]);
  const selectedHari = $('.checkbox-hari:checked').map(function () {
    return { hari: Number($(this).val()), id: $(this).data('id') };
  }).get();

  $('#jamOperasionalContainer').empty();

  for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
    const current = new Date(d);
    const day = current.getDay();
    const matched = selectedHari.find(h => h.hari === day);
    if (!matched) continue;

    const jamList = jamOperasionalMap[matched.id] || [];
    const selectHtml = generateJamSelect(current, jamList);
    $('#jamOperasionalContainer').append(selectHtml);
  }

  $('.select2-jam').select2({ theme: 'bootstrap-5', placeholder: 'Pilih Sesi Jam' });
}

function generateJamSelect(tanggal, jamList) {
  const formattedDate = tanggal.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
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
