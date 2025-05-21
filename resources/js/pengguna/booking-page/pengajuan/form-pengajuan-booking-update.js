import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/themes/airbnb.css";

import select2 from "select2";
select2();

document.addEventListener("DOMContentLoaded", () => {
  initFormPengajuanBookingEdit();
});

// Global Variables
let flatpickrMultiInstance, flatpickrRangeInstance;
let hariOperasionalGlobal = [];
let jamOperasionalMap = {};

const hariMapping = {
  0: 'Minggu', 1: 'Senin', 2: 'Selasa', 3: 'Rabu',
  4: 'Kamis', 5: 'Jumat', 6: 'Sabtu'
};

export function initFormPengajuanBookingEdit() {
  initSelect2();
  initFlatpickrs();
  bindEvents();

  if (window.oldData && Object.keys(window.oldData).length > 0) {
    restoreOldFormState();
  }
}

function initSelect2() {
  $('#lokasiPengajuanBooking').select2({
    theme: "bootstrap-5",
    placeholder: "Pilih Lokasi"
  });

  $('#laboratoriumPengajuanBooking').select2({
    theme: "bootstrap-5",
    placeholder: "Pilih Laboratorium"
  }).prop('disabled', true);
}

function initFlatpickrs() {
  flatpickrMultiInstance = flatpickr("#tanggalMulti", {
    ...getFlatpickrMultiConfig(),
    disable: [() => true] 
  });

  flatpickrRangeInstance = flatpickr("#tanggalRange", {
    altInput: true,
    altFormat: "d F Y",
    dateFormat: "Y-m-d",
    locale: Indonesian,
    mode: "range"
  });
}

function getFlatpickrMultiConfig() {
  return {
    altInput: true,
    altFormat: "d F Y",
    dateFormat: "Y-m-d",
    locale: Indonesian,
    mode: "multiple",
    disable: [
      date => !hariOperasionalGlobal.map(h => Number(h.hari_operasional)).includes(date.getDay())
    ]
  };
}

function bindEvents() {
  $('input[name="mode_tanggal"]').on('change', toggleModeTanggal);
  $('#lokasiPengajuanBooking').on('change', () => handleLokasiChange());
  $('#tanggalMulti').on('change', handleMultiDateChange);
  $('#tanggalRange').on('change', handleRangeOrCheckboxChange);
  $('#checkboxHariOperasional').on('change', '.checkbox-hari', handleRangeOrCheckboxChange);
}

function toggleModeTanggal() {
  const mode = $('input[name="mode_tanggal"]:checked').val();

  $('#multiDateContainer').toggleClass('d-none', mode !== 'multi');
  $('#rangeDateContainer, #hariOperasionalContainer, #jamOperasionalContainer')
    .toggleClass('d-none', mode === 'multi');
  $('#jamPerTanggalContainer').toggleClass('d-none', mode !== 'multi').empty();

  clearDateAndJam();
}

async function handleLokasiChange(callback = null) {
  const lokasiId = $('#lokasiPengajuanBooking').val();
  if (!lokasiId) return resetAll();

  setInputsDisabled(true);
  clearDateAndJam();

  try {
    hariOperasionalGlobal = await fetchJSON(`/pengajuan/api/data-hari-operasional/${lokasiId}`);

    const jamResults = await Promise.all(
      hariOperasionalGlobal.map(h => fetchJSON(`/pengajuan/api/data-jam-operasional/${h.id}`))
    );

    hariOperasionalGlobal.forEach((hari, i) => {
      jamOperasionalMap[hari.id] = jamResults[i];
    });

    renderHariCheckboxes();

    flatpickrMultiInstance.set(getFlatpickrMultiConfig());
    flatpickrMultiInstance.clear();    

    const laboratorium = await fetchJSON(`/pengajuan/api/data-laboratorium/${lokasiId}`);
    const options = laboratorium.map(lab => ({ id: lab.id, text: lab.nama_laboratorium }));

    $('#laboratoriumPengajuanBooking')
      .empty()
      .select2({ theme: "bootstrap-5", placeholder: "Pilih Laboratorium", data: options })
      .prop('disabled', false)
      .trigger('change');

    if (typeof callback === 'function') callback();
    
    setInputsDisabled(false);

  } catch (err) {
    console.error("Gagal memuat data:", err);
    resetAll();
  }
}

function handleMultiDateChange() {
  const tanggalList = flatpickrMultiInstance.selectedDates;
  const $container = $('#jamPerTanggalContainer').empty();

  tanggalList.forEach(tgl => {
    const day = tgl.getDay();
    const hariMatch = hariOperasionalGlobal.find(h => Number(h.hari_operasional) === day);
    if (!hariMatch) return;

    const jamList = jamOperasionalMap[hariMatch.id] || [];
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

function renderHariCheckboxes() {
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

function generateJamSelect(tanggal, jamList) {
  const formatted = tanggal.toLocaleDateString('id-ID', {
    day: 'numeric', month: 'long', year: 'numeric'
  });

  const dateKey = tanggal.toLocaleDateString('sv-SE');
  const oldJam = (window.oldData?.jam?.[dateKey]) || [];

  const options = jamList.map(j => {
    const label = `${j.jam_mulai} - ${j.jam_selesai}`;
    const selected = oldJam.includes(label) ? 'selected' : '';
    return `<option value="${label}" ${selected}>${label}</option>`;
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

function fetchJSON(url) {
  return fetch(url).then(res => {
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  });
}

function setInputsDisabled(disabled) {
  $('#lokasiPengajuanBooking, #laboratoriumPengajuanBooking').prop('disabled', disabled);
  $('.checkbox-hari, .select2-jam').prop('disabled', disabled);
  flatpickrMultiInstance._input.toggleAttribute('disabled', disabled);
  flatpickrRangeInstance._input.toggleAttribute('disabled', disabled);
}

function clearDateAndJam() {
  flatpickrMultiInstance.clear();
  flatpickrRangeInstance.clear();
  $('#jamPerTanggalContainer, #jamOperasionalContainer').empty();
  $('.checkbox-hari').prop('checked', false);
}

function resetAll() {
  hariOperasionalGlobal = [];
  jamOperasionalMap = {};

  $('#checkboxHariOperasional, #jamOperasionalContainer, #jamPerTanggalContainer').empty();
  $('#laboratoriumPengajuanBooking').empty().prop('disabled', true).trigger('change');

  clearDateAndJam();
  setInputsDisabled(false);
}

function restoreOldFormState() {
  const old = window.oldData || {};
  applyOldMode(old);
  applyOldLokasi(old);
}

function applyOldMode(old) {
  const mode = old.mode_tanggal === 'range' ? 'range' : 'multi';
  $(`#mode${mode.charAt(0).toUpperCase() + mode.slice(1)}`).prop('checked', true);
  toggleModeTanggal();
}

function applyOldLokasi(old) {
  if (!old.lokasi_pengajuan_booking) return;

  $('#lokasiPengajuanBooking').val(old.lokasi_pengajuan_booking).trigger('change');

  handleLokasiChange(() => {
    applyOldLaboratorium(old);
    applyOldTanggalDanJam(old);
    applyOldKeperluan(old);

    if (Array.isArray(old.hari_operasional)) {
      old.hari_operasional.forEach(hari => {
        $(`.checkbox-hari[value="${hari}"]`).prop('checked', true);
      });
    }
  });
}

function applyOldLaboratorium(old) {
  if (Array.isArray(old.laboratorium_pengajuan_booking)) {
    $('#laboratoriumPengajuanBooking')
      .val(old.laboratorium_pengajuan_booking)
      .trigger('change');
  }
}

function applyOldTanggalDanJam(old) {
  if (old.tanggal_multi) {
    flatpickrMultiInstance.setDate(old.tanggal_multi, false);
    handleMultiDateChange();
  }

  if (old.tanggal_range) {
    const [start, end] = old.tanggal_range.split(" - ").map(s => s.trim());
    if (start && end) {
      flatpickrRangeInstance.setDate([start, end], false);

      if (Array.isArray(old.hari_operasional)) {
        old.hari_operasional.forEach(hari => {
          $(`.checkbox-hari[value="${hari}"]`).prop('checked', true);
        });
      }

      handleRangeOrCheckboxChange();
    }
  }
}

function applyOldKeperluan(old) {
  if (old.keperluan_pengajuan_booking) {
    $('#keperluanPengajuanBooking').val(old.keperluan_pengajuan_booking);
  }
}
