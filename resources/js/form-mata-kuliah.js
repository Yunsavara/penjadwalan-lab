import $ from 'jquery';
window.$ = window.jQuery = $;

$(document).ready(function() {
    initSelect();
});

function initSelect(){
    $('#namaDosen').select2({
        theme: 'bootstrap-5',
        placeholder: "Pilih Dosen",
        allowClear: true,
    });
}
