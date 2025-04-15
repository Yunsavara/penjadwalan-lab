import { Modal } from 'bootstrap';

const modalElement = document.getElementById('modalDeleteLab');
const deleteModal = new Modal(modalElement);

export function initSoftDeleteLaboratoriumModal() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-delete-laboratorium')) {
            const row = JSON.parse(e.target.getAttribute('data-row'));

            // Set form action
            const form = document.getElementById('formDeleteLab');
            form.setAttribute('action', `/laboran/hapus-laboratorium/${row.id_laboratorium}`);

            // Set pesan konfirmasi
            const message = `Apakah Anda yakin ingin menghapus Laboratorium <strong>${row.name_laboratorium}</strong> dari Lokasi <strong>${row.lokasi_name}</strong> ?`;
            document.getElementById('deleteLabMessage').innerHTML = message;

            // Tampilkan modal
            deleteModal.show();
        }
    });
}
