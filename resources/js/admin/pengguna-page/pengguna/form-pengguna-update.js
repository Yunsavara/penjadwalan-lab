import { Modal } from "bootstrap";

function initSelect2Update() {
    $('#formPenggunaUpdate').on('shown.bs.modal', function () {
        $('#edit-lokasiPengguna').select2({
            dropdownParent: $('#formPenggunaUpdate'),
            theme: "bootstrap-5",
            placeholder: "Pilih Jenis Lab",
            allowClear: true,
        });
        $('#edit-peranPengguna').select2({
            dropdownParent: $('#formPenggunaUpdate'),
            theme: "bootstrap-5",
            placeholder: "Pilih Lokasi Lab",
            allowClear: true,
        });
    });
}

export function initDatatablesValueToModalUpdatePengguna() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit-pengguna')) {
            const data = JSON.parse(e.target.getAttribute('data-row'));

            // // Set Judul Modal
            // const message = `<i data-feather="edit" class="me-2"></i>Ubah Laboratorium ${data.name} dari Lokasi ${data.lokasi_name}`;
            // document.getElementById('modalEditLaboratoriumLabel').innerHTML = message;

            // Set Data ke input
            document.getElementById('edit-idPengguna').value = data.id_pengguna;
            document.getElementById('edit-namaPengguna').value = data.nama_pengguna;
            document.getElementById('edit-emailPengguna').value = data.email;
            document.getElementById('edit-lokasiPengguna').value = data.lokasi_id;
            document.getElementById('edit-peranPengguna').value = data.role_id;

            const form = document.getElementById('formEditPengguna');
            form.setAttribute('action', `/admin/ubah-pengguna/${data.id_pengguna}`);

            const editModal = new Modal(document.getElementById('formPenggunaUpdate'));
            editModal.show();

            initSelect2Update();
        }
    });
}

export function errorUpdateModalPengguna(){
    const formData = document.getElementById('formDataPenggunaUpdate');
    const errors = JSON.parse(formData.dataset.errors);
    const sessionForm = formData.dataset.session;

    const old = JSON.parse(formData.dataset.old || '{}');

    if (errors && sessionForm === 'editPengguna') {
        const modal = new Modal(document.getElementById('formPenggunaUpdate'));

        // Isi field dari old input
        document.getElementById('edit-idPengguna').value = old.id_pengguna_update;
        document.getElementById('edit-namaPengguna').value = old.nama_pengguna_update;
        document.getElementById('edit-emailPengguna').value = old.email_pengguna_update;
        document.getElementById('edit-passwordPengguna').value = old.password_pengguna_update;
        document.getElementById('edit-lokasiPengguna').value = old.lokasi_id_update;
        document.getElementById('edit-peranPengguna').value = old.peran_id_update;


        // // Set judul modal
        // const message = `<i data-feather="edit" class="me-2"></i>Ubah Laboratorium ${old.name} dari Lokasi ${old.lokasi_name}`;
        // document.getElementById('modalEditLaboratoriumLabel').innerHTML = message;

        // Set action form
        const form = document.getElementById('formEditPengguna');
        form.setAttribute('action', `/admin/ubah-pengguna/${old.id_pengguna_update}`);

        // Show modal
        modal.show();
    }
}

export function formPasswordViewUpdate() {
    document.querySelectorAll('.edit-toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const inputs = [
                document.querySelector('#edit-passwordPengguna'),
                document.querySelector('#edit-passwordKonfirmasiPengguna')
            ];

            const isPassword = inputs[0].type === 'password';

            // Toggle semua input
            inputs.forEach(input => {
                input.type = isPassword ? 'text' : 'password';
            });

            // Ganti semua icon toggle yang terkait
            document.querySelectorAll('.edit-toggle-password .toggle-icon').forEach(icon => {
                icon.outerHTML = isPassword
                    ? `<i class="toggle-icon" data-feather="eye-off"></i>`
                    : `<i class="toggle-icon" data-feather="eye"></i>`;
            });

            feather.replace();
        });
    });
}

// Hapus Password kalau buka aksi edit di baris lain 

document.getElementById('formPenggunaUpdate').addEventListener('hidden.bs.modal', function () {
    document.getElementById('edit-passwordPengguna').value = '';
    document.getElementById('edit-passwordKonfirmasiPengguna').value = '';
});
