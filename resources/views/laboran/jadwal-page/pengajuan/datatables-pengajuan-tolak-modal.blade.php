<!-- Modal Konfirmasi Terima -->
<div class="modal fade" id="modalLaboranTolakPengajuan" tabindex="-1" aria-labelledby="modalLaboranTolakPengajuanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLaboranTolakPengajuanLabel">Konfirmasi Tolak Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menolak pengajuan dengan kode <strong id="modalLaboranTolakPengajuanKode"></strong>?</p>

                <!-- Form untuk Terima Pengajuan -->
                <form id="formLaboranTolakPengajuan" action="{{ route('pengajuan.tolak') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kode_pengajuan" id="inputLaboranTolakKodePengajuan">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-primary" id="btnKonfirmasiTolak" form="formLaboranTolakPengajuan">Ya</button>
            </div>
        </div>
    </div>
</div>
