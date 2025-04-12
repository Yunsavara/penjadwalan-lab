import { Modal } from 'bootstrap';

function initSelect2Update() {
    $('#formLaboratoriumUpdate').on('shown.bs.modal', function () {
        $('#edit-jenisLaboratorium').select2({
            dropdownParent: $('#formLaboratoriumUpdate'),
            theme: "bootstrap-5",
            placeholder: "Pilih Jenis Lab",
            allowClear: true,
        });
        $('#edit-lokasiLaboratorium').select2({
            dropdownParent: $('#formLaboratoriumUpdate'),
            theme: "bootstrap-5",
            placeholder: "Pilih Lokasi Lab",
            allowClear: true,
        });
    });
}

export function initDatatablesValueToModalUpdateLab() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit-laboratorium')) {
            const data = JSON.parse(e.target.getAttribute('data-row'));

            // // Set Judul Modal
            // const message = `<i data-feather="edit" class="me-2"></i>Ubah Laboratorium ${data.name} dari Lokasi ${data.lokasi_name}`;
            // document.getElementById('modalEditLaboratoriumLabel').innerHTML = message;

            // Set Data ke input
            document.getElementById('edit-slugLaboratorium').value = data.slug;
            document.getElementById('edit-namaLaboratorium').value = data.name;
            document.getElementById('edit-jenisLaboratorium').value = data.jenislab_id;
            document.getElementById('edit-lokasiLaboratorium').value = data.lokasi_id;
            document.getElementById('edit-kapasitasLaboratorium').value = data.kapasitas;
            document.getElementById('edit-statusLaboratorium').value = data.status;

            const form = document.getElementById('formEditLaboratorium');
            form.setAttribute('action', `/laboran/ubah-laboratorium/${data.slug}`);

            const editModal = new Modal(document.getElementById('formLaboratoriumUpdate'));
            editModal.show();

            initSelect2Update();
        }
    });
}


export function errorUpdateModalLaboratorium(){
    const formData = document.getElementById('formDataLaboratoriumUpdate');
    const errors = JSON.parse(formData.dataset.errors);
    const sessionForm = formData.dataset.session;

    // console.log(errors, sessionForm);

    const old = JSON.parse(formData.dataset.old || '{}');

    if (errors && sessionForm === 'editLaboratorium') {
        const modal = new Modal(document.getElementById('formLaboratoriumUpdate'));

        // Isi field dari old input
        document.getElementById('edit-slugLaboratorium').value = old.slug || '';
        document.getElementById('edit-namaLaboratorium').value = old.name || '';
        document.getElementById('edit-jenisLaboratorium').value = old.jenislab_id || '';
        document.getElementById('edit-lokasiLaboratorium').value = old.lokasi_id;
        document.getElementById('edit-kapasitasLaboratorium').value = old.kapasitas;
        document.getElementById('edit-statusLaboratorium').value = old.status;

        // // Set judul modal
        // const message = `<i data-feather="edit" class="me-2"></i>Ubah Laboratorium ${old.name} dari Lokasi ${old.lokasi_name}`;
        // document.getElementById('modalEditLaboratoriumLabel').innerHTML = message;

        // Set action form
        const form = document.getElementById('formEditLaboratorium');
        form.setAttribute('action', `/laboran/ubah-laboratorium/${old.slug}`);

        // Show modal
        modal.show();
    }
}
