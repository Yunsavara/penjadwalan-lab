<div class="modal fade" id="formPeranUpdate" tabindex="-1" aria-labelledby="modalEditPeranLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header bg-warning">
          <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalEditPeranLabel">
            <i data-feather="edit" class="me-2"></i>
            Ubah Peran
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <form id="formEditPeran" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="id_peran_update" id="edit-idPeran">

            <div class="mb-3">
              <label for="edit-namaPeran" class="form-label">Nama Peran</label>
              <div class="input-group">
                <span class="input-group-text">
                    <i data-feather="map" width="20"></i>
                </span>
                <input type="text" name="nama_peran_update" id="edit-namaPeran" class="form-control @error('nama_peran_update') is-invalid @enderror" autocomplete="off">
                @error('nama_peran_update')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
              </div>
            </div>

            <div class="mb-3">
                <label for="edit-prioritasPeran">Prioritas Peran</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i data-feather="award" width="20"></i>
                    </span>
                    <input type="number" name="prioritas_peran_update" class="form-control @error('prioritas_peran_update') is-invalid @enderror" id="edit-prioritasPeran" placeholder="1" autocomplete="off">
                    @error('prioritas_peran_update')
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
<div id="formDataPeranUpdate" class="d-none" data-errors="{{ json_encode($errors->any()) }}" data-session="{{ session('form') }}" data-old='@json(old())'>
</div>
