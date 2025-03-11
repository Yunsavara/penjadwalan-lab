<div class="modal fade" id="formPengajuanStore" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Buat Pengajuan</h1>
            <button type="button" data-feather="x" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
      <div class="modal-body">

        <!-- Form Input -->
        <form action="{{ route('pengajuan.store') }}" method="POST">
            @csrf
            <!-- Input Tanggal -->
            <div class="col-12 mb-3">
                <label for="tanggalPengajuan">Tanggal Pengajuan</label>
                <input type="text" id="tanggalPengajuan" class="form-control" placeholder="Pilih tanggal"
                value="{{ is_array(old('tanggal_pengajuan')) ? implode(', ', old('tanggal_pengajuan')) : old('tanggal_pengajuan') }}">
                <input type="hidden" name="tanggal_pengajuan" id="tanggalHidden" value="{{ is_array(old('tanggal_pengajuan')) ? implode(',', old('tanggal_pengajuan')) : old('tanggal_pengajuan') }}">
            </div>

            <!-- Input Ruangan -->
            <div class="col-12 mb-3">
                <label for="ruangLab">Pilih Ruangan</label>
                <select name="lab_id[]" id="ruangLab" class="form-select select2" multiple>
                    <option value=""></option>
                    @foreach($Ruangan as $lab)
                        <option value="{{ $lab->id }}" {{ in_array($lab->id, old('lab_id', [])) ? 'selected' : '' }}>
                            {{ $lab->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Input Jam Mulai & Selesai -->
            <div class="col-12 mb-3 d-flex flex-wrap align-items-center justify-content-between">
                <div class="col-12 col-md-5 mb-3">
                    <label for="jamMulai">Jam Mulai</label>
                    <input type="time" id="jamMulai" class="form-control" name="jam_mulai" value="{{ old('jam_mulai') }}">
                </div>
                <div class="col-12 col-md-5 mb-3">
                    <label for="jamSelesai">Jam Selesai</label>
                    <input type="time" id="jamSelesai" class="form-control" name="jam_selesai" value="{{ old('jam_selesai') }}">
                </div>
            </div>

            <!-- Input Keperluan -->
            <div class="col-12 mb-3">
                <label for="keperluanPengajuan">Keperluan</label>
                <textarea name="keperluan" id="keperluanPengajuan" class="form-control" autocomplete="off"
                        style="min-height:80px; max-height:80px;" placeholder="Keperluan untuk mengajar...">{{ old('keperluan') }}</textarea>
            </div>

        </div>

        <div class="modal-footer">
            <button type="reset" class="btn btn-danger">Reset</button>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>

      </form>
        <!-- /Form Input -->
    </div>
  </div>
</div>
