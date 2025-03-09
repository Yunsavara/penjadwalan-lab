<!-- Modal Konfirmasi Batalkan -->
<div class="modal fade" id="modalBatalkanJadwal" tabindex="-1" aria-labelledby="modalBatalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBatalLabel">Konfirmasi Pembatalan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin membatalkan jadwal ?</p>

                <!-- Form Pembatalan -->
                <form id="formBatalkanJadwal" action="{{ route('jadwal.batalkan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_detail_id" id="inputBookingDetailId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-danger" id="btnKonfirmasiBatalJadwal" form="formBatalkanJadwal">Ya</button>
            </div>
        </div>
    </div>
</div>
