<div class="modal fade" id="formLaboratoriumEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header bg-warning">
          <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalEditLabel">
            
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <form id="formEditLab" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="slug" id="edit-slug">

            <div class="mb-3">
              <label for="edit-namaLab" class="form-label">Nama Ruangan</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="trello" width="20"></i></span>
                <input type="text" name="name" id="edit-namaLab" class="form-control">
              </div>
            </div>

            <div class="mb-3">
              <label for="edit-jenisLab" class="form-label">Jenis Laboratorium</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="pocket" width="20"></i></span>
                <select name="jenislab_id" id="edit-jenisLab" class="form-select">
                  <option value="" selected></option>
                  @foreach ($Jenislab as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="edit-lokasiLaboratorium" class="form-label">Lokasi Laboratorium</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="map-pin" width="20"></i></span>
                <select name="lokasi_id" id="edit-lokasiLaboratorium" class="form-select">
                  <option value="" selected></option>
                  @foreach ($Lokasi as $lok)
                    <option value="{{ $lok->id }}">{{ $lok->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="edit-kapasitas" class="form-label">Kapasitas Laboratorium</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="grid" width="20"></i></span>
                <input type="text" name="kapasitas" id="edit-kapasitas" class="form-control">
              </div>
            </div>

            <div class="mb-3">
              <label for="edit-status" class="form-label">Status Laboratorium</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="bar-chart-2" width="20"></i></span>
                <select name="status" id="edit-status" class="form-select">
                  <option value="tersedia">Tersedia</option>
                  <option value="tidak tersedia">Tidak Tersedia</option>
                </select>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
          </div>
        </form>

      </div>
    </div>
  </div>
