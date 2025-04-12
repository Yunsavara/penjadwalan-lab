export function initSelect2Store(){
    $('#formLaboratoriumStore').on('shown.bs.modal', function () {
        $('#jenisLaboratorium').select2({
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
