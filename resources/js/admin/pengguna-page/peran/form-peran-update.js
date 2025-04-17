import { Modal } from 'bootstrap';

export function initDatatablesValueToModalUpdatePeran() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit-peran')) {
            const data = JSON.parse(e.target.getAttribute('data-row'));

            // // judul modal
            // const message = `<i data-feather="edit" class="me-2"></i>Ubah Jenis Laboratorium ${data.name}`;
            // document.getElementById('modalEditJenisLabLabel').innerHTML = message;

            // set pesan yang sedang di-ubah
            document.getElementById('edit-idPeran').value = data.id_peran;
            document.getElementById('edit-namaPeran').value = data.nama_peran;
            document.getElementById('edit-prioritasPeran').value = data.prioritas_peran;

            // form action
            const form = document.getElementById('formEditPeran');
            form.setAttribute('action', `/admin/ubah-peran/${data.id_peran}`);

            // panggil modal
            const editModal = new Modal(document.getElementById('formPeranUpdate'));
            editModal.show();

        }
    });
}


export function errorUpdateModalPeran(){
    const formData = document.getElementById('formDataPeranUpdate');
    const errors = JSON.parse(formData.dataset.errors);
    const sessionForm = formData.dataset.session;

    // console.log(errors, sessionForm);

    const old = JSON.parse(formData.dataset.old || '{}');

    if (errors && sessionForm === 'editPeran') {
        const modal = new Modal(document.getElementById('formPeranUpdate'));

        // Isi field dari old input
        document.getElementById('edit-idPeran').value = old.id_peran_update;
        document.getElementById('edit-namaPeran').value = old.nama_peran_update;
        document.getElementById('edit-prioritasPeran').value = old.prioritas_peran_update;

        // // Set judul modal
        // const message = `<i data-feather="edit" class="me-2"></i>Ubah Jenis Laboratorium ${old.name || ''}`;
        // document.getElementById('modalEditJenisLabLabel').innerHTML = message;

        // Set action form
        const form = document.getElementById('formEditPeran');
        form.setAttribute('action', `/admin/ubah-peran/${old.id_peran_update}`);

        // Show modal
        modal.show();
    }
}
