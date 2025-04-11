export function initSelect2Store(){
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
