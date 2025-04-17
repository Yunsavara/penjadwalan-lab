import { Modal } from 'bootstrap';

const modalElement = document.getElementById('modalDeleteLokasi');
const deleteModal = new Modal(modalElement);

export function initSoftDeleteLokasiModal() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-delete-lokasi')) {
            const row = JSON.parse(e.target.getAttribute('data-row'));

            // Set form action
            const form = document.getElementById('formDeleteLokasi');
            form.setAttribute('action', `/admin/hapus-lokasi/${row.id_lokasi}`);

            // Set pesan konfirmasi
            const message = `Apakah Anda yakin ingin menghapus Lokasi <strong>${row.nama_lokasi}</strong> ?`;
            document.getElementById('deleteLokasiMessage').innerHTML = message;

            // Tampilkan modal
            deleteModal.show();
        }
    });
}
