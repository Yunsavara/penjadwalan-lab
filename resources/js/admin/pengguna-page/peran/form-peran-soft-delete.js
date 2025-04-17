import { Modal } from 'bootstrap';

const modalElement = document.getElementById('modalDeletePeran');
const deleteModal = new Modal(modalElement);

export function initSoftDeletePeranModal() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-delete-peran')) {
            const row = JSON.parse(e.target.getAttribute('data-row'));

            // Set form action
            const form = document.getElementById('formDeletePeran');
            form.setAttribute('action', `/admin/hapus-peran/${row.id_peran}`);

            // Set pesan konfirmasi
            const message = `Apakah Anda yakin ingin menghapus Peran <strong>${row.nama_peran}</strong> ?`;
            document.getElementById('deletePeranMessage').innerHTML = message;

            // Tampilkan modal
            deleteModal.show();
        }
    });
}
