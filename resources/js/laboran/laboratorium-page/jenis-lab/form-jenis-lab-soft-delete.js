import { Modal } from 'bootstrap';

const modalElement = document.getElementById('modalDeleteJenisLab');
const deleteModal = new Modal(modalElement);

export function initSoftDeleteJenisLabModal() {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-delete-jenis-lab')) {
            const row = JSON.parse(e.target.getAttribute('data-row'));

            // Set form action
            const form = document.getElementById('formDeleteJenisLab');
            form.setAttribute('action', `/laboran/hapus-jenis-laboratorium/${row.id_jenis_lab}`);

            // Set pesan konfirmasi
            const message = `Apakah Anda yakin ingin menghapus Jenis Lab <strong>${row.name_jenis_lab}</strong> ?`;
            document.getElementById('deleteJenisLabMessage').innerHTML = message;

            // Tampilkan modal
            deleteModal.show();
        }
    });
}
