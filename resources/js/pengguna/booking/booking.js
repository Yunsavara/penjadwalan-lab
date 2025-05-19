import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/themes/airbnb.css";

flatpickr.localize(Indonesian);
window.flatpickr = flatpickr;

import select2 from "select2";
select2();

window.initFuncInput = {
    initLokasiSelect2,
    initLaboratoriumSelect2,
    initTanggalMultiFlatpickr,
    initJamOperasionalSelect2,
    initTanggalRangeFlatpickr
};

function initLokasiSelect2(lokasi, livewire)
{
    const $select = $(lokasi); // lokasi => $el.querySelector

    $select.select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#select2-lokasi-parent'), 
    });

    if (livewire)
    {
        $select.on('change', function () {
            livewire.set('lokasiId', $(this).val());
        });
    }
}

function initLaboratoriumSelect2(laboratorium, livewire)
{
    const $select = $(laboratorium);

    $select.select2({
        theme: "bootstrap-5",
        dropdownParent: $('#select2-laboratorium-parent'),
    });

    if (livewire)
    {
        $select.on('change', function () {
            livewire.set('laboratoriumId', $(this).val());
        });
    }
}

function initTanggalMultiFlatpickr(tanggalMulti, livewire) {
    const instance = flatpickr(tanggalMulti, {
        mode: 'multiple',
        altInput: true,
        altFormat: 'd F Y',
        dateFormat: 'Y-m-d',
        locale: 'id',
        onChange: function (selectedDates, dateStr) {
            const tanggalArray = dateStr.split(', ').filter(t => t !== '');
            livewire.set('tanggalMulti', tanggalArray);
        }
    });

    tanggalMulti.flatpickrInstance = instance;
}

function initJamOperasionalSelect2(jamOperasional, livewire, tanggalStr) {
    const $select = $(jamOperasional);

    if ($select.hasClass('select2-hidden-accessible')) {
        // console.log("Select2 sudah diinisialisasi, lewati:", tanggalStr);
        return;
    }

    $select.select2({
        theme: 'bootstrap-5',
        dropdownParent: $select.parent()
    });

    if (livewire) {
        $select.on('change', function () {
            livewire.set(`jamTerpilih.${tanggalStr}`, $(this).val());
        });
    }
}

function initTanggalRangeFlatpickr(tanggalRange, livewire) {
    flatpickr(tanggalRange, {
        mode: 'range',
        altInput: 'true',
        altFormat: 'd F Y',
        dateFormat: 'Y-m-d',
        locale: 'id',
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                const start = instance.formatDate(selectedDates[0], 'Y-m-d');
                const end = instance.formatDate(selectedDates[1], 'Y-m-d');
                livewire.set('tanggalRange', `${start} - ${end}`);
            } else {
                livewire.set('tanggalRange', null);
            }
        }
    });
}


// Reset, dispatch di controller livewire
Livewire.on('resetLaboratoriumSelect', () => {
    const $select = $('#laboratoriumId');
    $select.val('').trigger('change');
});

Livewire.on('resetTanggalMultiFlatpickr', () => {
    const el = document.querySelector('#tanggalMulti');
    if (el && el.flatpickrInstance) {
        el.flatpickrInstance.clear();
    }
});

