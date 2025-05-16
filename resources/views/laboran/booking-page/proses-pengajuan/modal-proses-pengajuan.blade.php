
{{-- Tolak Pengajuan --}}
<div class="modal fade" id="detailProsesPengajuanModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="detailProsesPengajuanModalLabel"></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="detailProsesPengajuanOleh"></p>
        <p id="detailProsesPengajuanPrioritas"></p>
        <p id="detailProsesPengajuanLaboratorium"></p>
        <p id="detailProsesPengajuanKeperluan"></p>
        <p id="detailProsesPengajuanStatus"></p>

        <p id="detailProsesPengajuanTanggalJam"></p>

      </div>
    </div>
  </div>
</div>

{{-- Terima Pengajuan --}}
<div class="modal fade" id="terimaProsesPengajuanModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formTerimaProsesPengajuan" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h1 class="modal-title fs-5">Konfirmasi Terima</h1>
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

          <p id="terimaProsesPengajuanBody" class="mb-2"></p>

          <div class="mb-3">
            <label class="form-label">Tolak Otomatis
              <i data-feather="help-circle" width="15" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Otomatis Tolak Pengajuan prioritas bawah yang bentrok dengan Pengajuan ini."></i>
            </label>
              <div class="d-flex flex-wrap gap-2">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="mode_terima" value="tidak" id="tidakOtomatisTolakKonflik" checked>
                    <label class="form-check-label" for="tidakOtomatisTolakKonflik">Tidak</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="mode_terima" value="otomatis" id="otomatisTolakKonflik">
                    <label class="form-check-label" for="otomatisTolakKonflik">Otomatis</label>
                </div>
              </div>
          </div>

          <div class="mb-3" id="alasanPenolakanContainer" style="display:none;">
              <label for="alasanPenolakan" class="form-label">Alasan Penolakan</label>
              <textarea name="alasan_penolakan_otomatis" id="alasanPenolakan" class="form-control" placeholder="Tulis alasan penolakan otomatis di sini..." style="resize:none; max-height:100px; min-height:100px;"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Ya, Terima</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Tolak Pengajuan --}}
<div class="modal fade" id="tolakProsesPengajuanModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formTolakProsesPengajuan" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h1 class="modal-title fs-5">Konfirmasi Tolak</h1>
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

          <p id="tolakProsesPengajuanBody" class="mb-2"></p>

          <div class="mb-3">
            <label for="balasanPengajuanBooking">Alasan Penolakan</label>
            <textarea name="balasan_pengajuan_booking" id="balasanPengajuanBooking" class="form-control" style="resize:none; min-height:100px; max-height:100px;"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Ya, Tolak</button>
        </div>
      </form>
    </div>
  </div>
</div>