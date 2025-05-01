import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/themes/airbnb.css";
import select2 from "select2";
select2();

const dayMap = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
let sesiPerHari = {};
let selectedDates = [];
let tanggalRangePicker = null;
let pagedTanggalBoxes = [];
let currentPage = 1;
const itemsPerPage = 5;

export function initSelect2() {
  $('#lokasiSelect, #labSelect').select2({
    theme: "bootstrap-5",
    placeholder: "Pilih Opsi"
  });
}

export function initTanggalRangePicker() {
  tanggalRangePicker = flatpickr("#tanggalRange", {
    mode: "range",
    dateFormat: "Y-m-d",
    minDate: "today",
    altInput: true,
    altFormat: "j F Y",
    locale: Indonesian,
    onChange: function (dates) {
      selectedDates = dates;

      if (dates.length === 2) {
        $('#tanggalMulai').val(flatpickr.formatDate(dates[0], "Y-m-d"));
        $('#tanggalSelesai').val(flatpickr.formatDate(dates[1], "Y-m-d"));
      } else {
        $('#tanggalMulai').val('');
        $('#tanggalSelesai').val('');
      }

      renderTanggalCheckboxes();
    }
  });
}

export function registerEventListeners() {
  $("#lokasiSelect").on("change", handleLokasiChange);
  $(document).on("change", ".hari-checkbox", updateJamOperasional);
  $(document).on("change", ".sesi-select", renderTanggalCheckboxes);
  $(document).on("click", "#prevPage", () => changePage(-1));
  $(document).on("click", "#nextPage", () => changePage(1));
}

function handleLokasiChange() {
  const lokasi = $("#lokasiSelect").val();
  resetForm();

  if (lokasi) {
    updateHariCheckbox(lokasi);
    updateLabOptions();
  }
}

function resetForm() {
  $("#labSelect").val("").trigger("change").empty();
  $("#hariOperasional, #jamOperasionalContainer, #daftarTanggal").empty();
  selectedDates = [];

  if (tanggalRangePicker) {
    tanggalRangePicker.clear();
    if (tanggalRangePicker.altInput) {
      tanggalRangePicker.altInput.value = "";
    }
  }
}

export function updateLabOptions() {
  const lokasi = $("#lokasiSelect").val();
  if (!lokasi) return;

  $.get(`/pengajuan/api/data-laboratorium/${lokasi}`, function (labs) {
    const labSelect = $("#labSelect").empty();
    labs.forEach(lab => {
      labSelect.append(`<option value="${lab.id}">${lab.nama_laboratorium}</option>`);
    });
    labSelect.trigger("change");
  });
}

export function updateHariCheckbox(lokasi) {
  const hariContainer = $("#hariOperasional").empty();
  const jamContainer = $("#jamOperasionalContainer").empty();

  $.get(`/pengajuan/api/data-hari-operasional/${lokasi}`, function (data) {
    if (data.length) {
      hariContainer.append(`<label class="form-label d-block mb-2">Hari Operasional</label>`);
    }

    data.forEach(item => {
      hariContainer.append(`
        <div class="form-check form-check-inline">
          <input type="checkbox" class="form-check-input hari-checkbox" 
                 value="${item.hari_operasional}" data-id="${item.id}" 
                 id="hari-${item.hari_operasional}" name="hari_operasional[]">
          <label class="form-check-label" for="hari-${item.hari_operasional}">
            ${item.hari_operasional}
          </label>
        </div>
      `);
    });

    updateJamOperasional(); // Render awal (kosong)
  });
}

function updateJamOperasional() {
  const container = $("#jamOperasionalContainer");

  $(".day-config").each(function () {
    const hari = this.id.replace("config-", "");
    if (!$(`.hari-checkbox[value="${hari}"]`).is(":checked")) {
      $(this).remove();
      delete sesiPerHari[hari];
    }
  });

  $(".hari-checkbox:checked").each(function () {
    const hari = $(this).val();
    const hariId = $(this).data("id");

    if (!$(`#config-${hari}`).length) {
      $.get(`/pengajuan/api/data-jam-operasional/${hariId}`, function (data) {
        sesiPerHari[hari] = data.map(j => `${j.jam_mulai} - ${j.jam_selesai}`);
        const options = sesiPerHari[hari].map(jam => {
          return `<option value="${jam}">${jam}</option>`;
        }).join("");

        container.append(`
          <div class="day-config mb-3" id="config-${hari}">
            <label for="select-sesi-${hari}" class="form-label">${hari} - Jam Operasional</label>
            <select id="select-sesi-${hari}" class="form-select sesi-select" name="sesi[${hari}][]" multiple>
              ${options}
            </select>
          </div>
        `);

        $(`#select-sesi-${hari}`).select2({ theme: "bootstrap-5" });
        renderTanggalCheckboxes();
      });
    }
  });
}

function renderTanggalCheckboxes() {
    const container = $("#daftarTanggal").empty();
    pagedTanggalBoxes = [];
  
    if (!selectedDates || selectedDates.length < 2) return;
  
    const old = window.oldFormData || {};
    const [start, end] = selectedDates;
    let current = new Date(start);
  
    while (current <= end) {
      const tanggalStr = current.toISOString().split("T")[0];
      const hari = dayMap[current.getDay()];
      let sesiHari = sesiPerHari[hari] || [];
  
      // Tambahkan jam tambahan dari old.sesi_tanggal
      if (old?.sesi_tanggal?.[tanggalStr]) {
        const tambahan = old.sesi_tanggal[tanggalStr].filter(jam => !sesiHari.includes(jam));
        sesiHari = sesiHari.concat(tambahan);
      }
  
      const selectedSesi = $(`#select-sesi-${hari}`).val() || [];
      const formattedDate = current.toLocaleDateString("id-ID", {
        weekday: "long", year: "numeric", month: "long", day: "numeric"
      });
  
      if (sesiHari.length) {
        const checkboxHtml = sesiHari.map(jam => {
          const inputId = `tgl-${tanggalStr}-${jam}`;
          const isChecked = old?.sesi_tanggal?.[tanggalStr]?.includes(jam) || selectedSesi.includes(jam);
          return `
            <div class="form-check form-check-inline">
              <input type="checkbox" class="form-check-input sesi-tanggal"
                     name="sesi_tanggal[${tanggalStr}][]" value="${jam}"
                     id="${inputId}" ${isChecked ? "checked" : ""}>
              <label class="form-check-label" for="${inputId}">${jam}</label>
            </div>
          `;
        }).join("");
  
        pagedTanggalBoxes.push(`
          <div class="tanggal-box mb-3">
            <strong>${formattedDate}</strong><br>
            ${checkboxHtml}
          </div>
        `);
      }
  
      current.setDate(current.getDate() + 1);
    }
  
    currentPage = 1;
    showTanggalPage();
  }
  
function showTanggalPage() {
  const container = $("#daftarTanggal").empty();
  const totalPages = Math.ceil(pagedTanggalBoxes.length / itemsPerPage);
  const start = (currentPage - 1) * itemsPerPage;
  const end = start + itemsPerPage;

  container.append(pagedTanggalBoxes.slice(start, end).join(""));

  if (totalPages > 1) {
    container.append(`
      <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <button class="btn btn-outline-primary btn-sm" id="prevPage" ${currentPage === 1 ? "disabled" : ""}>Sebelumnya</button>
        <span>Halaman ${currentPage} dari ${totalPages}</span>
        <button class="btn btn-outline-primary btn-sm" id="nextPage" ${currentPage === totalPages ? "disabled" : ""}>Berikutnya</button>
      </div>
    `);
  }
}

function changePage(direction) {
  const totalPages = Math.ceil(pagedTanggalBoxes.length / itemsPerPage);
  currentPage = Math.min(Math.max(currentPage + direction, 1), totalPages);
  showTanggalPage();
}


export function applyOldFormData() {
    const old = window.oldFormData;
    if (!old) return;
  
    // Lokasi
    if (old.lokasi) {
      $("#lokasiSelect").val(old.lokasi).trigger("change");
    }
  
    // Keperluan
    if (old.keperluan_pengajuan_booking) {
      $("#keperluanPengajuanBooking").val(old.keperluan_pengajuan_booking);
    }
  
    // Tanggal range
    if (old.tanggalRange && old.tanggalRange.length === 2) {
      const [mulai, selesai] = old.tanggalRange;
      tanggalRangePicker.setDate([mulai, selesai], true);
    }
  
    // Setelah lokasi terpilih, tunggu lab tersedia baru isi ulang lab
    if (old.laboratorium && old.laboratorium.length) {
      const observer = new MutationObserver(() => {
        $("#labSelect").val(old.laboratorium).trigger("change");
        observer.disconnect();
      });
  
      observer.observe(document.querySelector("#labSelect"), { childList: true });
    }
  
    // Hari Operasional
    if (old.hari_operasional) {
        const hariArr = Array.isArray(old.hari_operasional) ? old.hari_operasional : [old.hari_operasional];
        const interval = setInterval(() => {
        const allLoaded = hariArr.every(hari => $(`#hari-${hari}`).length > 0);
        if (allLoaded) {
            hariArr.forEach(hari => {
            $(`#hari-${hari}`).prop("checked", true);
            });
            updateJamOperasional();
            clearInterval(interval);
        }
        }, 200);
    }
  
    // Sesi per hari
    if (old.sesi) {
      const interval = setInterval(() => {
        const allReady = Object.keys(old.sesi).every(hari => $(`#select-sesi-${hari}`).length > 0);
        if (allReady) {
          for (const [hari, sesiList] of Object.entries(old.sesi)) {
            $(`#select-sesi-${hari}`).val(sesiList).trigger("change");
          }
          renderTanggalCheckboxes();
          clearInterval(interval);
        }
      }, 200);
    }
  
    // Sesi per tanggal
    if (old.sesi_tanggal) {
      const interval = setInterval(() => {
        const semuaSiap = Object.entries(old.sesi_tanggal).every(([tgl, list]) =>
          list.every(jam => $(`#tgl-${tgl}-${jam.replaceAll(':', '-')}`).length > 0)
        );
  
        if (semuaSiap) {
          for (const [tgl, sesiList] of Object.entries(old.sesi_tanggal)) {
            sesiList.forEach(jam => {
              const jamId = `#tgl-${tgl}-${jam}`;
              $(jamId).prop("checked", true);
            });
          }
          clearInterval(interval);
        }
      }, 300);
    }
  }
  