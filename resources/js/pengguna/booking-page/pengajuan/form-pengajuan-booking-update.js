import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/themes/airbnb.css";

import select2 from "select2";
select2();

document.addEventListener("DOMContentLoaded", () => {
    initFormPengajuanBookingEdit();
})

let flatpickrMultiInstance, flatpickrRangeInstance;
let hariOperasionalGlobal = [];
let jamOperasionalMap = {};

const hariMapping = {
  0: 'Minggu', 1: 'Senin', 2: 'Selasa', 3: 'Rabu',
  4: 'Kamis', 5: 'Jumat', 6: 'Sabtu'
};

export function initFormPengajuanBookingEdit() {
  initializeSelects();
  initializeFlatpickrs();
  bindEventListeners();

  if (window.oldData && Object.keys(window.oldData).length > 0) {
    restoreOldValues();
  }
}

function initializeSelects() {
  $('#lokasiPengajuanBooking').select2({
    theme: "bootstrap-5",
    placeholder: "Pilih Lokasi"
  });

  $('#laboratoriumPengajuanBooking')
    .select2({ theme: "bootstrap-5", placeholder: "Pilih Laboratorium" });
}

function initializeFlatpickrs() {
  flatpickrMultiInstance = flatpickr("#tanggalMulti", {
    minDate: "today",
    altInput: true,
    altFormat: "d F Y",
    dateFormat: "Y-m-d",
    locale: Indonesian,
    mode: "multiple",
    disable: [() => true],
    onReady: (_, __, instance) => instance._input.setAttribute("disabled", true)
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

function bindEventListeners() {
  $('input[name="mode_tanggal"]').on('change', handleModeToggle);
  $('#lokasiPengajuanBooking').on('change', handleLokasiChange);
  $('#tanggalMulti').on('change', handleMultiDateChange);
  $('#tanggalRange, #checkboxHariOperasional').on('change', handleRangeOrCheckboxChange);
}

function handleModeToggle() {
  const mode = $('input[name="mode_tanggal"]:checked').val();

  $('#multiDateContainer').toggleClass('d-none', mode !== 'multi');
  $('#rangeDateContainer, #hariOperasionalContainer, #jamOperasionalContainer')
    .toggleClass('d-none', mode === 'multi');
  $('#jamPerTanggalContainer').toggleClass('d-none', mode !== 'multi').empty();

  clearDateAndJamFields();
}

async function handleLokasiChange(callback = null) {
  const lokasiId = $('#lokasiPengajuanBooking').val();
  if (!lokasiId) return;

  try {
    hariOperasionalGlobal = await fetchJSON(`/pengajuan/api/data-hari-operasional/${lokasiId}`);
    const jamPromises = hariOperasionalGlobal.map(h => fetchJSON(`/pengajuan/api/data-jam-operasional/${h.id}`));
    const jamResults = await Promise.all(jamPromises);

    hariOperasionalGlobal.forEach((hari, i) => {
      jamOperasionalMap[hari.id] = jamResults[i];
    });

    renderHariOperasionalCheckboxes();

    flatpickrMultiInstance.destroy();
    flatpickrMultiInstance = flatpickr("#tanggalMulti", getFlatpickrMultiConfig());

    const laboratorium = await fetchJSON(`/pengajuan/api/data-laboratorium/${lokasiId}`);
    const options = laboratorium.map(lab => ({ id: lab.id, text: lab.nama_laboratorium }));

    $('#laboratoriumPengajuanBooking')
      .empty()
      .select2({ theme: "bootstrap-5", placeholder: "Pilih Laboratorium", data: options })
      .trigger('change');

    if (typeof callback === 'function') callback();
  } catch (err) {
    console.error(err);
  }
}

function getFlatpickrMultiConfig() {
  return {
    minDate: "today",
    altInput: true,
    altFormat: "d F Y",
    dateFormat: "Y-m-d",
    locale: Indonesian,
    mode: "multiple",
    disable: [date => {
      const allowedDays = hariOperasionalGlobal.map(h => Number(h.hari_operasional));
      return !allowedDays.includes(date.getDay());
    }],
    onReady: (_, __, instance) => instance._input.setAttribute("disabled", true)
  };
}

function handleMultiDateChange() {
  const tanggalList = flatpickrMultiInstance.selectedDates;
  const $container = $('#jamPerTanggalContainer').empty();

  tanggalList.forEach(tgl => {
    const day = tgl.getDay();
    const matched = hariOperasionalGlobal.find(h => Number(h.hari_operasional) === day);
    if (!matched) return;

    const jamList = jamOperasionalMap[matched.id] || [];
    $container.append(generateJamSelect(tgl, jamList));
  });

  $('.select2-jam').select2({ theme: 'bootstrap-5', placeholder: 'Pilih Sesi Jam' });
}

function handleRangeOrCheckboxChange() {
  const range = flatpickrRangeInstance.selectedDates;
  if (range.length !== 2) return;

  const start = new Date(range[0]);
  const end = new Date(range[1]);
  const selectedHari = $('.checkbox-hari:checked').map(function () {
    return { hari: Number($(this).val()), id: $(this).data('id') };
  }).get();

  const $container = $('#jamOperasionalContainer').empty();

  for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
    const current = new Date(d);
    const match = selectedHari.find(h => h.hari === current.getDay());
    if (!match) continue;

    const jamList = jamOperasionalMap[match.id] || [];
    $container.append(generateJamSelect(current, jamList));
  }

  $('.select2-jam').select2({ theme: 'bootstrap-5', placeholder: 'Pilih Sesi Jam' });
}

function generateJamSelect(tanggal, jamList) {
  const formatted = tanggal.toLocaleDateString('id-ID', {
    day: 'numeric', month: 'long', year: 'numeric'
  });

  const dateKey = tanggal.toLocaleDateString('sv-SE');

  const options = jamList.map(j => {
    const label = `${j.jam_mulai} - ${j.jam_selesai}`;
    return `<option value="${label}">${label}</option>`;
  }).join('');

  return `
    <div class="mb-3">
      <label class="form-label">Sesi Jam untuk ${formatted}</label>
      <select name="jam[${dateKey}][]" class="form-select select2-jam" multiple>
        ${options}
      </select>
    </div>
  `;
}

function renderHariOperasionalCheckboxes() {
  $('#checkboxHariOperasional').prev('.label-wrapper').remove();

  $('#checkboxHariOperasional').before(`
    <div class="label-wrapper">
      <label class="form-label">Hari Operasional</label>
    </div>
  `);

  const $container = $('#checkboxHariOperasional').empty().addClass('row');

  hariOperasionalGlobal.forEach(hari => {
    $container.append(`
      <div class="col-6 col-md-4 col-lg-3">
        <div class="form-check">
          <input class="form-check-input checkbox-hari"
                 type="checkbox"
                 name="hari_operasional[]"
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

function fetchJSON(url) {
  return fetch(url).then(res => {
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  });
}

function clearDateAndJamFields() {
  flatpickrMultiInstance.clear();
  flatpickrRangeInstance.clear();
  $('#jamPerTanggalContainer, #jamOperasionalContainer').empty();
  $('.checkbox-hari').prop('checked', false);
}

function restoreOldValues() {
  const old = window.oldData || {};

  // Mode
  const mode = old.mode_tanggal === 'range' ? 'range' : 'multi';
  $(`#mode${mode.charAt(0).toUpperCase() + mode.slice(1)}`).prop('checked', true);
  handleModeToggle();

  // Lokasi
  $('#lokasiPengajuanBooking').val(old.lokasi_pengajuan_booking).trigger('change');

  handleLokasiChange(() => {
    $('#laboratoriumPengajuanBooking').val(old.laboratorium_pengajuan_booking).trigger('change');

    if (old.mode_tanggal === 'multi') {
      flatpickrMultiInstance.setDate(old.tanggal_multi.split(','));
      handleMultiDateChange();
    } else {
      const range = old.tanggal_range.split(' - ').map(d => d.trim());
      flatpickrRangeInstance.setDate(range);

      if (Array.isArray(old.hari_operasional)) {
        old.hari_operasional.forEach(hari => {
          $(`.checkbox-hari[value="${hari}"]`).prop('checked', true);
        });
      }

      handleRangeOrCheckboxChange();
    }

    Object.entries(old.jam || {}).forEach(([tgl, sesi]) => {
      $(`select[name="jam[${tgl}][]"]`).val(sesi).trigger('change');
    });

    $('#keperluanPengajuanBooking').val(old.keperluan_pengajuan_booking);
  });
}
