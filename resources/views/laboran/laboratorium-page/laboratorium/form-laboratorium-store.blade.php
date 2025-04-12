<div class="modal fade" id="formLaboratoriumStore" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalTambahLabel">
            <i data-feather="plus-square" class="me-2"></i>Tambah Laboratorium
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
            <form action="{{ route('laboran.laboratorium.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="namaLaboratorium" class="form-label">Nama Ruangan</label>
                    <div class="input-group">
                        <span class="input-group-text">
                        <i data-feather="trello" width="20"></i>
                        </span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="namaLaboratorium" placeholder="CBT-01" autocomplete="off" value="{{ old('name', $Laboratorium->name) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="jenisLaboratorium" class="form-label ">Jenis Laboratorium</label>
                    <div class="input-group d-flex">
                        <span class="input-group-text">
                            <i data-feather="pocket" width="20"></i>
                        </span>
                        <select name="jenislab_id" id="jenisLaboratorium" class="form-select @error('jenislab_id') is-invalid @enderror">
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
                            <i data-feather="map-pin" width="20"></i>
                        </span>
                        <select name="lokasi_id" id="lokasiLaboratorium" class="form-select @error('lokasi_id') is-invalid @enderror">
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
                            <i data-feather="grid" width="20"></i>
                        </span>
                        <input type="text" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" id="kapasitasLaboratorium" placeholder="30" autocomplete="off" value="{{ old('kapasitas', $Laboratorium->kapasitas) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="statusLaboratorium" class="form-label">Status Laboratorium</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="bar-chart-2" width="20"></i>
                        </span>
                        <select name="status" id="statusLaboratorium" class="form-select @error('status') is-invalid @enderror">
                            <option value="tersedia" {{ old('status', $Laboratorium->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="tidak tersedia" {{ old('status', $Laboratorium->status) === 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>

      </div>
    </div>
  </div>

{{-- Datanya nanti ditangkep jika input gagal, dibuat gini supaya bisa if else di javascript --}}
<div id="formDataLaboratoriumStore" class="d-none" data-errors="{{ json_encode($errors->any()) }}" data-session="{{ session('form') }}">
</div>
