import { Modal } from 'bootstrap';

export function initDatatablesValueToModalUpdateJenisLab() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit-jenis-lab')) {
            const data = JSON.parse(e.target.getAttribute('data-row'));

            // // judul modal
            // const message = `<i data-feather="edit" class="me-2"></i>Ubah Jenis Laboratorium ${data.name}`;
            // document.getElementById('modalEditJenisLabLabel').innerHTML = message;

            // set pesan yang sedang di-uah
            document.getElementById('edit-slugJenisLab').value = data.slug;
            document.getElementById('edit-namaJenisLab').value = data.name;
            document.getElementById('edit-deskripsiJenisLab').value = data.description;

            // form action
            const form = document.getElementById('formEditJenisLab');
            form.setAttribute('action', `/laboran/ubah-jenis-laboratorium/${data.slug}`);

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
        document.getElementById('edit-slugJenisLab').value = old.slug || '';
        document.getElementById('edit-namaJenisLab').value = old.name || '';
        document.getElementById('edit-deskripsiJenisLab').value = old.description || '';

        // // Set judul modal
        // const message = `<i data-feather="edit" class="me-2"></i>Ubah Jenis Laboratorium ${old.name || ''}`;
        // document.getElementById('modalEditJenisLabLabel').innerHTML = message;

        // Set action form
        const form = document.getElementById('formEditJenisLab');
        form.setAttribute('action', `/laboran/ubah-jenis-laboratorium/${old.slug || ''}`);

        // Show modal
        modal.show();
    }
}
