import { Modal } from 'bootstrap';

export function initDatatablesValueToModalUpdateJenisLab() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit-jenis-lab')) {
            const data = JSON.parse(e.target.getAttribute('data-row'));

            // // judul modal
            // const message = `<i data-feather="edit" class="me-2"></i>Ubah Jenis Laboratorium ${data.name}`;
            // document.getElementById('modalEditJenisLabLabel').innerHTML = message;

            // set pesan yang sedang di-ubah
            document.getElementById('edit-slugJenisLab').value = data.slug_jenis_lab;
            document.getElementById('edit-namaJenisLab').value = data.name_jenis_lab;
            document.getElementById('edit-deskripsiJenisLab').value = data.description_jenis_lab;

            // form action
            const form = document.getElementById('formEditJenisLab');
            form.setAttribute('action', `/laboran/ubah-jenis-laboratorium/${data.slug_jenis_lab}`);

            // panggil modal
            const editModal = new Modal(document.getElementById('formJenisLabUpdate'));
            editModal.show();

        }
    });
}


export function errorUpdateModalJenisLab(){
    const formData = document.getElementById('formDataJenisLabUpdate');
    const errors = JSON.parse(formData.dataset.errors);
    const sessionForm = formData.dataset.session;

    // console.log(errors, sessionForm);

    const old = JSON.parse(formData.dataset.old || '{}');

    if (errors && sessionForm === 'editJenisLab') {
        const modal = new Modal(document.getElementById('formJenisLabUpdate'));

        // Isi field dari old input
        document.getElementById('edit-slugJenisLab').value = old.slug_jenis_lab_update || '';
        document.getElementById('edit-namaJenisLab').value = old.name_jenis_lab_update || '';
        document.getElementById('edit-deskripsiJenisLab').value = old.description_jenis_lab_update || '';

        // // Set judul modal
        // const message = `<i data-feather="edit" class="me-2"></i>Ubah Jenis Laboratorium ${old.name || ''}`;
        // document.getElementById('modalEditJenisLabLabel').innerHTML = message;

        // Set action form
        const form = document.getElementById('formEditJenisLab');
        form.setAttribute('action', `/laboran/ubah-jenis-laboratorium/${old.slug_jenis_lab || ''}`);

        // Show modal
        modal.show();
    }
}
