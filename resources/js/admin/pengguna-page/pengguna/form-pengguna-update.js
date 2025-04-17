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

    // console.log(errors, sessionForm);

    const old = JSON.parse(formData.dataset.old || '{}');

    if (errors && sessionForm === 'editPengguna') {
        const modal = new Modal(document.getElementById('formPenggunaUpdate'));

        // Isi field dari old input
        document.getElementById('edit-idPengguna').value = old.id_pengguna_update;
        document.getElementById('edit-namaPengguna').value = old.nama_pengguna_update;
        document.getElementById('edit-emailPengguna').value = old.email_pengguna_update;
        document.getElementById('edit-lokasiPengguna').value = old.lokasi_id_update;
        document.getElementById('edit-peranPengguna').value = old.peran_id_update;


        // // Set judul modal
        // const message = `<i data-feather="edit" class="me-2"></i>Ubah Laboratorium ${old.name} dari Lokasi ${old.lokasi_name}`;
        // document.getElementById('modalEditLaboratoriumLabel').innerHTML = message;

        // Set action form
        const form = document.getElementById('formEditPengguna');
        form.setAttribute('action', `/admin/ubah-penngguna/${old.id_pengguna_update}`);

        // Show modal
        modal.show();
    }
}


formPasswordView();

function formPasswordView() {
    document.getElementById('edit-togglePassword').addEventListener('click', function () {
        const input = document.getElementById('edit-passwordPengguna');
        const icon = document.getElementById('edit-iconPassword');

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
