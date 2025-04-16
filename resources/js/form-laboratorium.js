import $ from 'jquery';
window.$ = window.jQuery = $;

$(document).ready(function() {
    initSelect();
});

function initSelect(){
    $('#jenisLab').select2({
        theme: 'bootstrap-5',
        placeholder: "Pilih Jenis Lab",
        allowClear: true,
    });

    $('#lokasiLaboratorium').select2({
        theme: 'bootstrap-5',
        placeholder: "Pilih Lokasi Lab",
        allowClear: true,
    })

    $('#statusLaboratorium').select2({
        theme: 'bootstrap-5',
    })
}
