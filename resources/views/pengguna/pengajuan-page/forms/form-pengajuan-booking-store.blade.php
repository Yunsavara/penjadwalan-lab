<div class="modal fade" id="formPengajuanBookingStore" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalTambahLabel">
                <i data-feather="plus-square" class="me-2"></i>Tambah Pengajuan Booking
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
            <form action="" method="POST">
                @csrf

                <div class="mb-3">

                </div>

                {{-- <div class="mb-3">
                    <label for="namaPeran" class="form-label">Nama Peran</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="briefcase" width="20"></i>
                        </span>
                        <input type="text" name="nama_peran_store" class="form-control @error('nama_peran_store') is-invalid @enderror" id="namaPeran" placeholder="Laboran" autocomplete="off" value="{{ old('nama_peran_store', $Peran->nama_peran) }}">
                        @error('nama_peran_store')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="prioritasPeran" class="form-label">Prioritas Peran</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="award" width="20"></i>
                        </span>
                        <input type="number" name="prioritas_peran_store" class="form-control @error('prioritas_peran_store') is-invalid @enderror" id="prioritasPeran" placeholder="1" autocomplete="off" value="{{ old('prioritas_peran_store', $Peran->prioritas_peran) }}">
                        @error('prioritas_peran_store')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div> --}}
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
<div id="formDataPeranStore" class="d-none" data-errors="{{ json_encode($errors->any()) }}" data-session="{{ session('form') }}">
</div>
