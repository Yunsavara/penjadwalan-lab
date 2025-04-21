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

export function formPasswordViewStore() {
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const inputs = [
                document.querySelector('#passwordPengguna'),
                document.querySelector('#passwordKonfirmasiPengguna')
            ];

            // Cek status saat ini dari salah satu input
            const isPassword = inputs[0].type === 'password';

            // Toggle type semua input
            inputs.forEach(input => {
                input.type = isPassword ? 'text' : 'password';
            });

            // Update semua icon toggle
            document.querySelectorAll('.toggle-password .toggle-icon').forEach(icon => {
                icon.outerHTML = isPassword
                    ? `<i class="toggle-icon" data-feather="eye-off"></i>`
                    : `<i class="toggle-icon" data-feather="eye"></i>`;
            });

            feather.replace();
        });
    });
}
