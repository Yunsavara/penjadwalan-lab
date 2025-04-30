import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/themes/airbnb.css";

// Import Select2
import select2 from "select2";
select2();

export function initSelect2() {
    $('#lokasi, #laboratorium').select2({
        theme: 'bootstrap-5',
        placeholder: "Pilih opsi",
        allowClear: false
    });

    $('#lokasi').on('change', handleLokasiChange);
}

export function initFlatpickrTanggal() {
    flatpickr("#tanggal_mulai, #tanggal_selesai", {
        locale: Indonesian,
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        minDate: "today"
    });
}

function handleLokasiChange() {
    const lokasiId = $(this).val();
    resetFormFields();

    if (!lokasiId) return;

    fetchLaboratorium(lokasiId);
    fetchHariOperasional(lokasiId);
}

function resetFormFields() {
    $('#laboratorium').val(null).trigger('change');
    $('#hariOperasionalContainer, #jamOperasionalContainer, #accordionGenerated').empty();
    $('#hasilGenerate').addClass('d-none');
    $('#tanggal_mulai, #tanggal_selesai').each(function () {
        this._flatpickr?.clear();
    });
}

function fetchLaboratorium(lokasiId) {
    $.get(`/pengajuan/api/data-laboratorium/${lokasiId}`, function (data) {
        const $lab = $('#laboratorium').empty();
        data.forEach(lab => {
            $lab.append(new Option(lab.nama_laboratorium, lab.id));
        });
        $lab.trigger('change');
    }).fail(() => alert('Gagal memuat laboratorium.'));
}

function fetchHariOperasional(lokasiId) {
    $.get(`/pengajuan/api/data-hari-operasional/${lokasiId}`, function (data) {
        const $container = $('#hariOperasionalContainer').empty().append(`
            <div class="col-12">
                <label class="form-label">Hari Operasional</label>
            </div>
        `);

        data.forEach(hari => {
            $container.append(`
                <div class="col-6 col-sm-4 col-md-3">
                    <div class="form-check">
                        <input class="form-check-input hari-checkbox" type="checkbox" value="${hari.hari_operasional}" id="hari_${hari.id}" name="hari_operasional[]">
                        <label class="form-check-label" for="hari_${hari.id}">${hari.hari_operasional}</label>
                    </div>
                </div>
            `);
        });
    }).fail(() => alert('Gagal memuat hari operasional.'));
}

export function initEventHandlers() {
    $(document).on('change', '.hari-checkbox', handleHariCheckboxChange);
    $(document).on('change', '.multi-checkbox', toggleHiddenInput);
    $(document).on('click', '#generateBtn', generateForm);
    $(document).on('click', '.btn-remove-lab', removeLabBlock);
    $(document).on('click', '.btn-remove-tanggal', removeTanggalBlock);
}

function handleHariCheckboxChange() {
    const $checkbox = $(this);
    const hari = $checkbox.val();
    const id = $checkbox.attr('id').split('_')[1];
    const selectId = `jam_select_${id}`;

    if ($checkbox.is(':checked')) {
        $.get(`/pengajuan/api/data-jam-operasional/${id}`, function (data) {
            if (data.length === 0) return;

            const options = data.map(jam => `<option value="${jam.jam_mulai} - ${jam.jam_selesai}">${jam.jam_mulai} - ${jam.jam_selesai}</option>`).join('');
            $('#jamOperasionalContainer').append(`
                <div class="mb-3 jam-item" id="wrap_${selectId}">
                    <label class="form-label">Jam Operasional Hari ${hari}</label>
                    <select id="${selectId}" name="jam_operasional[${hari}][]" class="form-select" multiple required>
                        ${options}
                    </select>
                </div>
            `);
            $(`#${selectId}`).select2({ theme: "bootstrap-5", placeholder: `Pilih Jam untuk Hari ${hari}` });
        }).fail(() => alert(`Gagal memuat jam operasional untuk hari ${hari}`));
    } else {
        $(`#wrap_${selectId}`).remove();
    }
}

function generateForm() {
    const tanggalMulai = $('#tanggal_mulai').val();
    const tanggalSelesai = $('#tanggal_selesai').val();
    const laboratoriumIds = $('#laboratorium').val();

    if (!tanggalMulai || !tanggalSelesai || !laboratoriumIds.length) {
        return alert('Mohon lengkapi semua field sebelum generate.');
    }

    const hariChecked = $('.hari-checkbox:checked').map((_, el) => el.value).get();
    const jamDipilihPerHari = {};
    const semuaJamPerHari = {};

    $('.jam-item select').each(function () {
        const hari = $(this).attr('name').match(/\[([^\]]+)]/)[1];
        jamDipilihPerHari[hari] = $(this).val() || [];
        semuaJamPerHari[hari] = $(this).find('option').map((_, opt) => opt.value).get();
    });

    const dateList = getDateRange(tanggalMulai, tanggalSelesai).filter(date => {
        const hari = new Date(date).toLocaleDateString('id-ID', { weekday: 'long' });
        return hariChecked.includes(hari);
    });

    const accordionHtml = dateList.map((tanggal, idx) => {
        const hari = new Date(tanggal).toLocaleDateString('id-ID', { weekday: 'long' });
        const jamSemua = semuaJamPerHari[hari] || [];
        const jamDipilih = jamDipilihPerHari[hari] || [];

        const labHtml = laboratoriumIds.map(labId => {
            const labName = $(`#laboratorium option[value="${labId}"]`).text();
            const jamCheckboxes = jamSemua.map((jam, i) => {
                const id = `chk_${tanggal}_${labId}_${i}`;
                const [mulai, selesai] = jam.split(' - ');
                const nilai = JSON.stringify({ jam_mulai: mulai, jam_selesai: selesai });
                const checked = jamDipilih.includes(jam) ? 'checked' : '';

                return `
                    <div class="col-6 col-md-4">
                        <div class="form-check">
                            <input class="form-check-input multi-checkbox" type="checkbox" data-hidden-id="hidden_${id}" id="${id}" ${checked}>
                            <input type="hidden" name="booking[${tanggal}][${labId}][]" id="hidden_${id}" value='${nilai}' ${checked ? '' : 'disabled'}>
                            <label class="form-check-label" for="${id}">${jam}</label>
                        </div>
                    </div>
                `;
            }).join('');

            return `
                <div class="mb-4 lab-block" data-lab-id="${labId}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">${labName}</h6>
                        <button type="button" class="btn btn-sm btn-danger btn-remove-lab" data-lab-id="${labId}">Hapus Lab</button>
                    </div>
                    <div class="row">${jamCheckboxes}</div>
                </div>
            `;
        }).join('');

        const tanggalLabel = new Date(tanggal).toLocaleDateString('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });

        return `
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading_${idx}">
                    <button class="accordion-button ${idx !== 0 ? 'collapsed' : ''}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_${idx}" aria-expanded="${idx === 0}" aria-controls="collapse_${idx}">
                        ${tanggalLabel}
                    </button>
                </h2>
                <div id="collapse_${idx}" class="accordion-collapse collapse ${idx === 0 ? 'show' : ''}" data-bs-parent="#accordionGenerated">
                    <div class="accordion-body">
                        ${labHtml}
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-tanggal" data-tanggal="${tanggal}">Hapus Tanggal Ini</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    $('#accordionGenerated').html(accordionHtml);
    $('#hasilGenerate').removeClass('d-none');
}

function getDateRange(start, end) {
    const range = [];
    let current = new Date(start), endDate = new Date(end);
    while (current <= endDate) {
        range.push(current.toISOString().split('T')[0]);
        current.setDate(current.getDate() + 1);
    }
    return range;
}

function toggleHiddenInput() {
    const hiddenId = $(this).data('hidden-id');
    $(`#${hiddenId}`).prop('disabled', !$(this).is(':checked'));
}

function removeLabBlock() {
    const $block = $(this).closest('.lab-block');
    const $body = $block.closest('.accordion-body');
    $block.remove();

    if (!$body.find('.lab-block').length) {
        $body.closest('.accordion-item').remove();
    }
}

function removeTanggalBlock() {
    $(this).closest('.accordion-item').remove();
}
