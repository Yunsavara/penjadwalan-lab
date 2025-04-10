import $ from 'jquery';
window.$ = $;
window.jQuery = $;

import select2 from 'select2';
select2();

export function initSelect2(){
    $('#formLaboratoriumStore').on('shown.bs.modal', function () {
        $('#jenisLab').select2({
            dropdownParent: $('#formLaboratoriumStore'),
            theme: "bootstrap-5",
            placeholder: "Pilih Jenis Lab",
            allowClear: true,
        });
        $('#lokasiLaboratorium').select2({
            dropdownParent: $('#formLaboratoriumStore'),
            theme: "bootstrap-5",
            placeholder: "Pilih Lokasi Lab",
            allowClear: true,
        })
    });
}
