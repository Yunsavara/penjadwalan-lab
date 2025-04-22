import { Modal } from 'bootstrap';

const modalElement = document.getElementById('modalDeletePengguna');
const deleteModal = new Modal(modalElement);

export function initSoftDeletePenggunaModal() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-delete-pengguna')) {
            const row = JSON.parse(e.target.getAttribute('data-row'));

            // Set form action
            const form = document.getElementById('formDeletePengguna');
            form.setAttribute('action', `/admin/hapus-pengguna/${row.id_pengguna}`);

            // Set pesan konfirmasi
            const message = `Apakah Anda yakin ingin menghapus Peran <strong>${row.nama_pengguna}</strong> ?`;
            document.getElementById('deletePenggunaMessage').innerHTML = message;

            // Tampilkan modal
            deleteModal.show();
        }
    });
}
