import { Modal } from "bootstrap";

export function initSelect2Store(){
    $('#formPenggunaStore').on('shown.bs.modal', function () {
        $('#lokasiPengguna').select2({
            dropdownParent: $('#formPenggunaStore'),
            theme: "bootstrap-5",
            placeholder: "Pilih Lokasi Pengguna",
            allowClear: true,
        });
        $('#peranPengguna').select2({
            dropdownParent: $('#formPenggunaStore'),
            theme: "bootstrap-5",
            placeholder: "Pilih Peran Pengguna",
            allowClear: true,
        })
    });
}

export function errorStoreModalPengguna(){
    const formData = document.getElementById('formDataPenggunaStore');
    const errors = JSON.parse(formData.dataset.errors);
    const sessionForm = formData.dataset.session;

    if (errors && sessionForm === 'createPengguna') {
        const modal = new Modal(document.getElementById('formPenggunaStore'));
        modal.show();
    }
}

formPasswordView();

function formPasswordView() {
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('passwordPengguna');
        const icon = document.getElementById('iconPassword');

        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-feather', 'eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('data-feather', 'eye');
        }

        feather.replace();
    });
}
