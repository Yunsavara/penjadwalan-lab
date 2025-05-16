{{-- Tolak Pengajuan --}}
<div class="modal fade" id="batalkanPengajuanBookingModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formBatalkanPengajuanBooking" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h1 class="modal-title fs-5">Konfirmasi Batalkan</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div> 
          @endif

          <p id="batalkanPengajuanBookingBody" class="mb-2"></p>

          <div class="mb-3">
            <label for="balasanPengajuanBooking">Alasan Penolakan</label>
            <textarea name="balasan_pengajuan_booking" id="balasanPengajuanBooking" class="form-control" style="resize:none; min-height:100px; max-height:100px;"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Ya, Batalkan</button>
        </div>
      </form>
    </div>
  </div>
</div>