<div class="modal fade" id="formLaboratoriumStore" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalTambahLabel">
            <i data-feather="command" class="me-2"></i>Tambah Laboratorium
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">

            <x-validation></x-validation>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="namaLab" class="form-label">Nama Ruangan</label>
                    <div class="input-group">
                        <span class="input-group-text">
                        <i data-feather="trello"></i>
                        </span>
                        <input type="text" class="form-control" id="namaLab" placeholder="CBT-01">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="jenisLab" class="form-label ">Jenis Laboratorium</label>
                    <div class="input-group d-flex">
                        <span class="input-group-text">
                            <i data-feather="pocket"></i>
                        </span>
                        <select name="jenislab_id" id="jenisLab" class="form-select @error('jenislab_id') is-invalid @enderror">
                            <option value="" selected></option>
                            @foreach ($Jenislab as $jenis)
                                <option value="{{ $jenis->id }}"
                                    {{ old('jenislab_id', $Laboratorium->jenislab_id) == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="lokasiLaboratorium" class="form-label">Lokasi Laboratorium</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="map-pin"></i>
                        </span>
                        <select name="lokasi_id" id="lokasiLaboratorium" class="form-select">
                            <option value="" selected></option>
                            @foreach ($Lokasi as $lok)
                            <option value="{{ $lok->id }}"
                                {{ old('lokasi_id', $Laboratorium->lokasi_id) == $lok->id ? 'selected' : '' }}>
                                {{ $lok->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kapasitasLaboratorium" class="form-label">Kapasitas Laboratorium</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="grid"></i>
                        </span>
                        <input type="text" class="form-control" id="kapasitasLaboratorium" placeholder="30">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="statusLaboratorium" class="form-label">Status Laboratorium</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="bar-chart-2"></i>
                        </span>
                        <select name="" id="statusLaboratorium" class="form-select">
                            <option value="tersedia">Tersedia</option>
                            <option value="tidak tersedia">Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>

      </div>
    </div>
  </div>
