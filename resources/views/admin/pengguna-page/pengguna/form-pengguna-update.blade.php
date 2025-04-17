<div class="modal fade" id="formPenggunaUpdate" tabindex="-1" aria-labelledby="modalEditPenggunaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalEditPenggunaLabel">
                <i data-feather="plus-square" class="me-2"></i>Ubah Pengguna
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
            <form id="formEditPengguna" method="POST">
                @csrf
                @method('PUT')

                <input type="text" name="id_pengguna_update" id="edit-idPengguna">

                <div class="mb-3">
                    <label for="edit-namaPengguna" class="form-label">Nama Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="user" width="20"></i>
                        </span>
                        <input type="text" name="nama_pengguna_update" class="form-control @error('nama_pengguna_update') is-invalid @enderror" id="edit-namaPengguna" placeholder="Masukkan Nama Pengguna" autocomplete="off">
                        @error('nama_pengguna_update')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="edit-emailPengguna" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="at-sign" width="20"></i>
                        </span>
                        <input type="email" name="email_pengguna_update" class="form-control @error('email_pengguna_update') is-invalid @enderror" id="edit-emailPengguna" placeholder="unpam@gmail.com" autocomplete="off">
                        @error('email_pengguna_update')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="edit-passwordPengguna" class="form-label">
                        Password Pengguna
                        <i
                            data-feather="alert-circle"
                            class="text-danger"
                            width="15"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Kosongkan password jika tidak ada perubahan password.">
                        </i>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="key" width="20"></i>
                        </span>
                        <input type="password" name="password_pengguna_update" class="form-control @error('password_pengguna_update') is-invalid @enderror" id="edit-passwordPengguna" autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="edit-togglePassword">
                            <i data-feather="eye" id="edit-iconPassword"></i>
                        </button>
                        @error('password_pengguna_update')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="edit-peranPengguna" class="form-label">Peran Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="briefcase" width="20"></i>
                        </span>
                        <select name="peran_id_update" id="edit-peranPengguna" class="form-select @error('peran_id_update') is-invalid @enderror">
                            <option value="" selected></option>
                            @foreach ($PeranFormSelect as $Peran)
                            <option value="{{ $Peran->id }}">{{ $Peran->nama_peran }}</option>
                            @endforeach
                        </select>
                        @error('peran_id_update')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="edit-lokasiPengguna" class="form-label">
                        Lokasi Pengguna
                        <i
                            data-feather="alert-circle"
                            class="text-danger"
                            width="15"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Pilih Lokasi Tugas untuk Pengguna Ber-Peran Laboran, atau 'Fleksible' jika bukan Laboran.">
                        </i>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="map-pin" width="20"></i>
                        </span>
                        <select name="lokasi_id_update" id="edit-lokasiPengguna" class="form-select @error('lokasi_id_update') is-invalid @enderror">
                            <option value="" selected></option>
                            @foreach ($LokasiFormSelect as $lok)
                            <option value="{{ $lok->id }}">{{ $lok->nama_lokasi }}</option>
                            @endforeach
                        </select>
                        @error('lokasi_id_update')
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
<div id="formDataPenggunaUpdate" class="d-none" data-errors="{{ json_encode($errors->any()) }}" data-session="{{ session('form') }}">
</div>
