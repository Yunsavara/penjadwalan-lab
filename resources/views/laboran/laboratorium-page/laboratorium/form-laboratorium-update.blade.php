<div class="modal fade" id="formLaboratoriumUpdate" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header bg-warning">
          <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalEditLaboratoriumLabel">
            <i data-feather="edit" class="me-2"></i>
            Ubah Laboratorium
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <form id="formEditLaboratorium" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="id_laboratorium_update" id="edit-idLaboratorium">

            <div class="mb-3">
              <label for="edit-namaLaboratorium" class="form-label">Nama Ruangan</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="trello" width="20"></i></span>
                <input type="text" name="nama_laboratorium_update" id="edit-namaLaboratorium" class="form-control @error('nama_laboratorium_update') is-invalid @enderror" autocomplete="off">
                @error('nama_laboratorium_update')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
              </div>
            </div>

            <div class="mb-3">
              <label for="edit-jenisLaboratorium" class="form-label">Jenis Laboratorium</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="pocket" width="20"></i></span>
                <select name="jenislab_id_update" id="edit-jenisLaboratorium" class="form-select  @error('jenislab_id_update') is-invalid @enderror">
                  <option value="" selected></option>
                  @foreach ($Jenislab as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis_lab }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="edit-lokasiLaboratorium" class="form-label">Lokasi Laboratorium</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="map-pin" width="20"></i></span>
                <select name="lokasi_id_update" id="edit-lokasiLaboratorium" class="form-select @error('lokasi_id_update') is-invalid @enderror">
                  <option value="" selected></option>
                  @foreach ($Lokasi as $lok)
                    <option value="{{ $lok->id }}">{{ $lok->nama_lokasi }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="edit-kapasitasLaboratorium" class="form-label">Kapasitas Laboratorium</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="grid" width="20"></i></span>
                <input type="text" name="kapasitas_laboratorium_update" id="edit-kapasitasLaboratorium" class="form-control @error('kapasitas_laboratorium_update') is-invalid @enderror" autocomplete="off">
              </div>
            </div>

            <div class="mb-3">
              <label for="edit-statusLaboratorium" class="form-label">Status Laboratorium</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="bar-chart-2" width="20"></i></span>
                <select name="status_laboratorium_update" id="edit-statusLaboratorium" class="form-select @error('status_laboratorium_update') is-invalid @enderror">
                  <option value="tersedia">Tersedia</option>
                  <option value="tidak tersedia">Tidak Tersedia</option>
                </select>
              </div>
            </div>

            <div class="mb-3">
                <label for="edit-deskripsiLaboratorium" class="form-label">Deskripsi Laboratorium</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i data-feather="align-right" width="20"></i>
                    </span>
                    <textarea name="deskripsi_laboratorium_update" class="form-control @error('deskripsi_laboratorium_update') is-invalid @enderror" id="edit-deskripsiLaboratorium" placeholder="Tuliskan deskripsi laboratorium..." rows="3" style="min-height: 100px; max-height:100px; resize:none;"></textarea>
                    @error('deskripsi_laboratorium_update')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
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

{{-- Datanya nanti ditangkep jika input gagal, dibuat gini supaya bisa if else di javascript --}}
<div id="formDataLaboratoriumUpdate"
     data-session="{{ session('form') }}"
     data-errors='@json($errors->toArray())'
     data-old='@json(old())'>
</div>
