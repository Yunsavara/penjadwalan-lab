import { Modal } from 'bootstrap';

export function initDatatablesValueToModalUpdateLokasi() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit-lokasi')) {
            const data = JSON.parse(e.target.getAttribute('data-row'));

            // // judul modal
            // const message = `<i data-feather="edit" class="me-2"></i>Ubah Jenis Laboratorium ${data.name}`;
            // document.getElementById('modalEditJenisLabLabel').innerHTML = message;

            // set pesan yang sedang di-ubah
            document.getElementById('edit-idLokasi').value = data.id_lokasi;
            document.getElementById('edit-namaLokasi').value = data.nama_lokasi;
            document.getElementById('edit-deskripsiLokasi').value = data.deskripsi_lokasi;

            // form action
            const form = document.getElementById('formEditLokasi');
            form.setAttribute('action', `/admin/ubah-lokasi/${data.id_lokasi}`);

            // panggil modal
            const editModal = new Modal(document.getElementById('formLokasiUpdate'));
            editModal.show();

        }
    });
}


export function errorUpdateModalLokasi(){
    const formData = document.getElementById('formDataLokasiUpdate');
    const errors = JSON.parse(formData.dataset.errors);
    const sessionForm = formData.dataset.session;

    // console.log(errors, sessionForm);

    const old = JSON.parse(formData.dataset.old || '{}');

    if (errors && sessionForm === 'editLokasi') {
        const modal = new Modal(document.getElementById('formLokasiUpdate'));

        // Isi field dari old input
        document.getElementById('edit-idLokasi').value = old.id_lokasi_update;
        document.getElementById('edit-namaLokasi').value = old.nama_lokasi_update;
        document.getElementById('edit-deskripsiLokasi').value = old.deskripsi_lokasi_update;

        // // Set judul modal
        // const message = `<i data-feather="edit" class="me-2"></i>Ubah Jenis Laboratorium ${old.name || ''}`;
        // document.getElementById('modalEditJenisLabLabel').innerHTML = message;

        // Set action form
        const form = document.getElementById('formEditLokasi');
        form.setAttribute('action', `/admin/ubah-lokasi/${old.id_lokasi_update}`);

        // Show modal
        modal.show();
    }
}
