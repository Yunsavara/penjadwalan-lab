<div class="modal fade" id="formJenisLaboratoriumUpdate" tabindex="-1" aria-labelledby="modalEditJenisLabLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header bg-warning">
          <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalEditJenisLabLabel">

          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
            <div class="alert-formJenisLaboratoriumUpdate">
                <x-validation></x-validation>
            </div>
          <form id="formEditJenisLab" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="slug" id="edit-slugJenisLab">

            <div class="mb-3">
              <label for="edit-namaJenisLab" class="form-label">Nama Jenis Lab</label>
              <div class="input-group">
                <span class="input-group-text"><i data-feather="trello" width="20"></i></span>
                <input type="text" name="name" id="edit-namaJenisLab" class="form-control" autocomplete="off">
              </div>
            </div>

            <div class="mb-3">
                <label for="edit-deskripsiJenisLab">Deskripsi</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i data-feather="pocket" width="20"></i>
                    </span>
                    <textarea class="form-control" name="description" id="edit-deskripsiJenisLab" placeholder="Merupakan Jenis Laboratorium..." autocomplete="off" style="min-height: 100px; max-height:100px; resize:none;"></textarea>
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
<div id="formDataJenisLaboratoriumUpdate" class="d-none" data-errors="{{ json_encode($errors->any()) }}" data-session="{{ session('form') }}" data-old='@json(old())'>
</div>
