// Import Flatpickr dan tema serta lokal Indonesia
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/themes/airbnb.css";

// Import Select2
import select2 from "select2";
select2();

// Inisialisasi Select2 untuk dropdown
export function initSelect2() {
    const selectConfigs = [
        { selector: '#lokasi', placeholder: 'Pilih Lokasi' },
        { selector: '#laboratorium', placeholder: 'Pilih Laboratorium' },
        { selector: '#jamOperasional', placeholder: 'Pilih Jam' }
    ];

    selectConfigs.forEach(({ selector, placeholder }) => {
        $(selector).select2({
            theme: "bootstrap-5",
            placeholder,
            allowClear: false,
        }); 
    });

    // Event listener untuk perubahan lokasi
    $('#lokasi').on('change', function () {
        const lokasiId = $(this).val();

        // Reset semua field terkait lokasi sebelumnya
        $('#laboratorium').val(null).trigger('change');
        $('#hariOperasionalContainer').empty();
        $('#jamOperasionalContainer').empty();
        $('#accordionGenerated').empty();
        $('#hasilGenerate').addClass('d-none');
        $('#tanggal_mulai')[0]._flatpickr.clear();
        $('#tanggal_selesai')[0]._flatpickr.clear();


        if (lokasiId) {
            $.ajax({
                url: `/pengajuan/api/data-laboratorium/${lokasiId}`,
                type: 'GET',
                success: function (data) {
                    const $laboratorium = $('#laboratorium');
                    $laboratorium.empty(); // Hapus opsi sebelumnya

                    data.forEach(function (lab) {
                        const option = new Option(lab.nama_laboratorium, lab.id, false, false);
                        $laboratorium.append(option);
                    });

                    $laboratorium.trigger('change'); // Refresh Select2
                },
                error: function () {
                    alert('Gagal memuat laboratorium.');
                }
            });

            // Ambil Hari Operasional
            $.ajax({
                url: `/pengajuan/api/data-hari-operasional/${lokasiId}`,
                type: 'GET',
                success: function (hariList) {
                    const $container = $('#hariOperasionalContainer');
                    $container.empty();

                    $container.append(`
                        <div class="col-12">
                            <label class="form-label">Hari Operasional</label>
                        </div>
                    `);

                    hariList.forEach((hari, index) => {
                        const checkboxId = `hari_${hari.id}`;
                        const html = `
                            <div class="col-6 col-sm-4 col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input hari-checkbox" type="checkbox"
                                        value="${hari.hari_operasional}" id="${checkboxId}" name="hari_operasional[]">
                                    <label class="form-check-label" for="${checkboxId}">
                                        ${hari.hari_operasional}
                                    </label>
                                </div>
                            </div>
                        `;
                        $container.append(html);
                    });
                },
                error: function () {
                    alert('Gagal memuat hari operasional.');
                }
            });
        }
    });

    // Event listener untuk checkbox hari operasional
    $(document).on('change', '.hari-checkbox', function () {
        const $jamContainer = $('#jamOperasionalContainer');
        const isChecked = $(this).is(':checked');
        const hari = $(this).val();
        const checkboxId = $(this).attr('id'); // contoh: hari_3
        const hariOperasionalId = checkboxId.split('_')[1]; // Ambil ID-nya

        const selectId = `jam_select_${hariOperasionalId}`;

        if (isChecked) {
            // Fetch jam operasional via AJAX
            $.ajax({
                url: `/pengajuan/api/data-jam-operasional/${hariOperasionalId}`,
                type: 'GET',
                success: function (jamList) {
                    if (jamList.length > 0) {
                        let options = jamList.map((jam, i) => {
                            return `<option value="${jam.jam_mulai} - ${jam.jam_selesai}">
                                        ${jam.jam_mulai} - ${jam.jam_selesai}
                                    </option>`;
                        }).join('');

                        const html = `
                            <div class="mb-3 jam-item" id="wrap_${selectId}">
                                <label class="form-label">Jam Operasional Hari ${hari}</label>
                                <select id="${selectId}" name="jam_operasional[${hari}][]" class="form-select" multiple required>
                                    ${options}
                                </select>
                            </div>
                        `;
                        $jamContainer.append(html);

                        // Inisialisasi Select2 untuk select yang baru ditambahkan
                        $(`#${selectId}`).select2({
                            theme: "bootstrap-5",
                            placeholder: `Pilih Jam untuk Hari ${hari}`,
                            allowClear: true,
                        });
                    }
                },
                error: function () {
                    alert(`Gagal memuat jam operasional untuk hari ${hari}`);
                }
            });
        } else {
            // Hapus select jika hari tidak dicentang
            $(`#wrap_jam_select_${hariOperasionalId}`).remove();
        }
    });

}


// Inisialisasi Flatpickr untuk input tanggal mulai dan selesai
export function initFlatpickrTanggal() {
    flatpickr("#tanggal_mulai,#tanggal_selesai", {
        locale: Indonesian,
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        minDate: "today",
    });
}

export function handleGenerateForm() {
    $('#generateBtn').on('click', function () {
        const tanggalMulai = $('#tanggal_mulai').val();
        const tanggalSelesai = $('#tanggal_selesai').val();
        const laboratoriumIds = $('#laboratorium').val();
        const alasan = $('#alasan').val();

        if (!tanggalMulai || !tanggalSelesai || !laboratoriumIds.length || !alasan) {
            alert('Mohon lengkapi semua field sebelum generate.');
            return;
        }

        const allDates = getDateRange(tanggalMulai, tanggalSelesai);
        const hariChecked = $('.hari-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        // Ambil semua pilihan jam dari select awal (yang dipilih user)
        const jamDipilihPerHari = {};
        $('.jam-item select').each(function () {
            const hari = $(this).attr('name').match(/\[([^\]]+)\]/)[1];
            const jamList = $(this).val() || [];
            jamDipilihPerHari[hari] = jamList;
        });

        // Ambil semua jam operasional dari DOM, berdasarkan data awal
        const semuaJamPerHari = {};
        $('.jam-item select').each(function () {
            const hari = $(this).attr('name').match(/\[([^\]]+)\]/)[1];
            const allOptions = $(this).find('option').map(function () {
                return $(this).val();
            }).get();
            semuaJamPerHari[hari] = allOptions;
        });

        const tanggalRange = allDates.filter(tgl => {
            const hari = new Date(tgl).toLocaleDateString('id-ID', { weekday: 'long' });
            return hariChecked.includes(hari);
        });

        let accordionHtml = '';

        tanggalRange.forEach((tanggal, index) => {
            const hari = new Date(tanggal).toLocaleDateString('id-ID', { weekday: 'long' });

            // Format tanggal menjadi "Rabu, 23 Juni 2025"
            const tanggalFormatted = new Date(tanggal).toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });            

            const semuaJam = semuaJamPerHari[hari] || [];
            const jamDipilih = jamDipilihPerHari[hari] || [];

            let innerLabHtml = '';

            laboratoriumIds.forEach((labId) => {
                const labName = $(`#laboratorium option[value="${labId}"]`).text();

                const checkboxHtml = semuaJam.map((jam, i) => {
                    const id = `chk_${tanggal}_${labId}_${i}`;
                    const isChecked = jamDipilih.includes(jam) ? 'checked' : '';
                    const [mulai, selesai] = jam.split(' - ');
                    const nilaiJson = JSON.stringify({ jam_mulai: mulai, jam_selesai: selesai });

                    return `
                        <div class="col-6 col-md-4">
                            <div class="form-check">
                                <input class="form-check-input multi-checkbox"
                                    type="checkbox"
                                    data-hidden-id="hidden_${id}"
                                    id="${id}" ${isChecked}>

                                <input type="hidden"
                                    name="booking[${tanggal}][${labId}][]"
                                    id="hidden_${id}"
                                    value='${nilaiJson}' ${isChecked ? '' : 'disabled'}>

                                <label class="form-check-label" for="${id}">${jam}</label>
                            </div>
                        </div>
                    `;
                }).join('');

                innerLabHtml += `
                    <div class="mb-4 lab-block" data-lab-id="${labId}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">${labName}</h6>
                            <button type="button" class="btn btn-sm btn-danger btn-remove-lab" data-lab-id="${labId}">
                                Hapus Lab
                            </button>
                        </div>
                        <div class="row">
                            ${checkboxHtml}
                        </div>
                    </div>
                `;
            });

            accordionHtml += `
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_${index}">
                        <button class="accordion-button ${index !== 0 ? 'collapsed' : ''}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse_${index}" aria-expanded="${index === 0}" aria-controls="collapse_${index}">
                            ${tanggalFormatted}
                        </button>
                    </h2>
                    <div id="collapse_${index}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" aria-labelledby="heading_${index}" data-bs-parent="#accordionGenerated">
                        <div class="accordion-body">
                            ${innerLabHtml}
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-tanggal" data-tanggal="${tanggal}">
                                    Hapus Tanggal Ini
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#accordionGenerated').html(accordionHtml);
        $('#hasilGenerate').removeClass('d-none');
    });
}

// Util untuk hitung array tanggal dari rentang
function getDateRange(start, end) {
    const dateArray = [];
    let currentDate = new Date(start);
    const stopDate = new Date(end);

    while (currentDate <= stopDate) {
        const dateStr = currentDate.toISOString().split('T')[0];
        dateArray.push(dateStr);
        currentDate.setDate(currentDate.getDate() + 1);
    }

    return dateArray;
}

$(document).on('change', '.multi-checkbox', function () {
    const hiddenId = $(this).data('hidden-id');
    $(`#${hiddenId}`).prop('disabled', !$(this).is(':checked'));
});

$(document).on('click', '.btn-remove-lab', function () {
    const $labBlock = $(this).closest('.lab-block');
    const $accordionBody = $labBlock.closest('.accordion-body');
    const $accordionItem = $labBlock.closest('.accordion-item');

    // Hapus lab-block
    $labBlock.remove();

    // Cek apakah masih ada lab lainnya
    if ($accordionBody.find('.lab-block').length === 0) {
        $accordionItem.remove();
    }
});

$(document).on('click', '.btn-remove-tanggal', function () {
    const tanggal = $(this).data('tanggal');

    // Temukan dan hapus seluruh accordion-item yang sesuai tanggal
    const $accordionItem = $(this).closest('.accordion-item');
    $accordionItem.remove();
});
