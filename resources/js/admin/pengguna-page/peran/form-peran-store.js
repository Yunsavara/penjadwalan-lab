import { Modal } from 'bootstrap';

// Fungsi yang menerima data atau form gagal di input

export function errorStoreModalPeran(){
    const formData = document.getElementById('formDataPeranStore');
    const errors = JSON.parse(formData.dataset.errors);
    const sessionForm = formData.dataset.session;

    if (errors && sessionForm === 'createPeran') {
        const modal = new Modal(document.getElementById('formPeranStore'));
        modal.show();
    }
}
