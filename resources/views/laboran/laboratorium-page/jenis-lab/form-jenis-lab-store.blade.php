<div class="modal fade" id="formJenisLabStore" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalTambahLabel">
                <i data-feather="plus-square" class="me-2"></i>Tambah Jenis Laboratorium
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
            <form action="{{ route('laboran.jenis-lab.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="namaJenisLab" class="form-label">Nama Jenis Lab</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="pocket" width="20"></i>
                        </span>
                        <input type="text" name="nama_jenis_lab_store" class="form-control @error('nama_jenis_lab_store') is-invalid @enderror" id="namaJenisLab" placeholder="Komputer" autocomplete="off" value="{{ old('nama_jenis_lab_store', $JenisLaboratorium->nama_jenis_lab) }}">
                        @error('nama_jenis_lab_store')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsiJenisLab" class="form-label">Deskripsi</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="align-right" width="20"></i>
                        </span>
                        <textarea class="form-control @error('deskripsi_jenis_lab_store') is-invalid @enderror" name="deskripsi_jenis_lab_store" id="deskripsiJenisLab" placeholder="Merupakan Jenis Laboratorium..." autocomplete="off" style="min-height: 100px; max-height:100px; resize:none;">{{ old('deskripsi_jenis_lab_store', $JenisLaboratorium->deskripsi_jenis_lab) }}</textarea>
                        @error('deskripsi_jenis_lab_store')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
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
<div id="formDataJenisLabStore" class="d-none" data-errors="{{ json_encode($errors->any()) }}" data-session="{{ session('form') }}">
</div>
