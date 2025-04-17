import { Modal } from 'bootstrap';

// Fungsi yang menerima data atau form gagal di input

export function errorStoreModalLokasi(){
    const formData = document.getElementById('formDataLokasiStore');
    const errors = JSON.parse(formData.dataset.errors);
    const sessionForm = formData.dataset.session;

    if (errors && sessionForm === 'createLokasi') {
        const modal = new Modal(document.getElementById('formLokasiStore'));
        modal.show();
    }
}
