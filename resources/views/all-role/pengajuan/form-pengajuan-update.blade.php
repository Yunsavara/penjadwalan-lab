<!-- Modal Form Edit Pengajuan -->
<div class="modal fade" id="formPengajuanEdit" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <div class="col-12 d-flex justify-content-between align-items-center">
              <h1 class="modal-title fs-5" id="editModalLabel">Edit Pengajuan</h1>
              <button type="button" data-feather="x" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        </div>
        <div class="modal-body">

          <!-- Form Input -->
          <form id="editForm" method="POST">
              @csrf
              @method('PUT')

              <!-- Hidden Input untuk kode_pengajuan -->
              <input type="hidden" name="kode_pengajuan" id="editKodePengajuan">

              <!-- Input Tanggal -->
              <div class="col-12 mb-3">
                  <label for="editTanggalPengajuan">Tanggal Pengajuan</label>
                  <input type="text" id="editTanggalPengajuan" class="form-control" placeholder="Pilih tanggal">
                  <input type="hidden" name="tanggal_pengajuan" id="editTanggalHidden">
              </div>

              <!-- Input Ruangan -->
              <div class="col-12 mb-3">
                  <label for="editRuangLab">Pilih Ruangan</label>
                  <select name="lab_id[]" id="editRuangLab" class="form-select select2" multiple></select>
              </div>

              <!-- Input Jam Mulai & Selesai -->
              <div class="col-12 mb-3 d-flex flex-wrap align-items-center justify-content-between">
                  <div class="col-12 col-md-5 mb-3">
                      <label for="editJamMulai">Jam Mulai</label>
                      <input type="time" id="editJamMulai" class="form-control" name="jam_mulai">
                  </div>
                  <div class="col-12 col-md-5 mb-3">
                      <label for="editJamSelesai">Jam Selesai</label>
                      <input type="time" id="editJamSelesai" class="form-control" name="jam_selesai">
                  </div>
              </div>

              <!-- Input Keperluan -->
              <div class="col-12 mb-3">
                  <label for="editKeperluanPengajuan">Keperluan</label>
                  <textarea name="keperluan" id="editKeperluanPengajuan" class="form-control" autocomplete="off"
                          style="min-height:80px; max-height:80px;" placeholder="Keperluan untuk mengajar..."></textarea>
              </div>

          </div>

          <div class="modal-footer">
              <button type="reset" class="btn btn-danger">Reset</button>
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>

        </form>
          <!-- /Form Input -->
      </div>
    </div>
  </div>
