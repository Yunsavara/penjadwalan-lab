<div class="modal fade" id="modalDeletePengguna" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalDeleteLabel"><i data-feather="trash-2" class="me-2"></i>Hapus Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="formDeletePengguna" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p id="deletePenggunaMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="confirmDeletePengguna" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
