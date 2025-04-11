import { Modal } from 'bootstrap';

export function initDatatablesValueToModalUpdate() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit-lab')) {
            const data = JSON.parse(e.target.getAttribute('data-row'));

            // Set Pesan data yang sedang di-ubah
            const message = `<i data-feather="edit" class="me-2"></i>Ubah Laboratorium ${data.name} dari Lokasi ${data.lokasi_name}`;
            document.getElementById('modalEditLabel').innerHTML = message;

            document.getElementById('edit-slug').value = data.slug;
            document.getElementById('edit-namaLab').value = data.name;
            document.getElementById('edit-jenisLab').value = data.jenislab_id;
            document.getElementById('edit-lokasiLaboratorium').value = data.lokasi_id;
            document.getElementById('edit-kapasitas').value = data.kapasitas;
            document.getElementById('edit-status').value = data.status;

            const form = document.getElementById('formEditLab');
            form.setAttribute('action', `/laboran/ubah-laboratorium/${data.slug}`);

            const editModal = new Modal(document.getElementById('formLaboratoriumEdit'));
            editModal.show();

            initSelect2Update();
        }
    });
}

function initSelect2Update() {
    $('#formLaboratoriumEdit').on('shown.bs.modal', function () {
        $('#edit-jenisLab').select2({
            dropdownParent: $('#formLaboratoriumEdit'),
            theme: "bootstrap-5",
            placeholder: "Pilih Jenis Lab",
            allowClear: true,
        });
        $('#edit-lokasiLaboratorium').select2({
            dropdownParent: $('#formLaboratoriumEdit'),
            theme: "bootstrap-5",
            placeholder: "Pilih Lokasi Lab",
            allowClear: true,
        });
    });
}
