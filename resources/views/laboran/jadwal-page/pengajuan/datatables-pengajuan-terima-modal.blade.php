<!-- Modal Konfirmasi Terima -->
<div class="modal fade" id="modalLaboranTerimaPengajuan" tabindex="-1" aria-labelledby="modalLaboranTerimaPengajuanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLaboranTerimaPengajuanLabel">Konfirmasi Terima Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menerima pengajuan dengan kode <strong id="modalLaboranPengajuanKode"></strong>?</p>

                <!-- Form untuk Terima Pengajuan -->
                <form id="formLaboranTerimaPengajuan" action="{{ route('pengajuan.terima') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kode_pengajuan" id="inputLaboranKodePengajuan">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-primary" id="btnKonfirmasiTerima" form="formLaboranTerimaPengajuan">Ya</button>
            </div>
        </div>
    </div>
</div>
