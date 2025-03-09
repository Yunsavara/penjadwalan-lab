<!-- Modal Konfirmasi Batalkan -->
<div class="modal fade" id="modalBatalkanPengajuan" tabindex="-1" aria-labelledby="modalBatalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBatalLabel">Konfirmasi Pembatalan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin membatalkan pengajuan dengan kode <strong id="modalBatalKode"></strong>?</p>

                <!-- Form Pembatalan -->
                <form id="formBatalkanPengajuan" action="{{ route('pengajuan.batalkan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kode_pengajuan" id="inputKodePengajuan">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-danger" id="btnKonfirmasiBatal" form="formBatalkanPengajuan">Ya</button>
            </div>
        </div>
    </div>
</div>
