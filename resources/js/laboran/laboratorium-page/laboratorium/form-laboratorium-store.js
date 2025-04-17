import { Modal } from 'bootstrap';

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

// Fungsi yang menerima data atau form gagal di input
export function errorStoreModalLaboratorium(){
    const formData = document.getElementById('formDataLaboratoriumStore');
    const errors = JSON.parse(formData.dataset.errors);
    const sessionForm = formData.dataset.session;

    if (errors && sessionForm === 'createLaboratorium') {
        const modal = new Modal(document.getElementById('formLaboratoriumStore'));
        modal.show();
    }
}
