import { Modal } from 'bootstrap';

// Fungsi yang menerima data atau form gagal di input

export function errorStoreModalJenisLab(){
    const formData = document.getElementById('formDataJenisLabStore');
    const errors = JSON.parse(formData.dataset.errors);
    const sessionForm = formData.dataset.session;

    if (errors && sessionForm === 'createJenisLab') {
        const modal = new Modal(document.getElementById('formJenisLabStore'));
        modal.show();
    }
}
