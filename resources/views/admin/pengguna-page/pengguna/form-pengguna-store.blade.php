<div class="modal fade" id="formPenggunaStore" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title d-flex align-items-center flex-wrap" id="modalTambahLabel">
                <i data-feather="plus-square" class="me-2"></i>Tambah Pengguna
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
            <form action="{{ route('admin.pengguna.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="namaPengguna" class="form-label">Nama Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="user" width="20"></i>
                        </span>
                        <input type="text" name="nama_pengguna_store" class="form-control @error('nama_pengguna_store') is-invalid @enderror" id="namaPengguna" placeholder="Masukkan Nama Pengguna" autocomplete="off" value="{{ old('nama_pengguna_store', $Pengguna->nama_pengguna) }}">
                        @error('nama_pengguna_store')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="emailPengguna" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="at-sign" width="20"></i>
                        </span>
                        <input type="email" name="email_pengguna_store" class="form-control @error('email_pengguna_store') is-invalid @enderror" id="emailPengguna" placeholder="unpam@gmail.com" autocomplete="off" value="{{ old('email_pengguna_store', $Pengguna->email) }}">
                        @error('email_pengguna_store')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="passwordPengguna" class="form-label">Password Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="key" width="20"></i>
                        </span>
                        <input type="password" name="password_pengguna_store" class="form-control @error('password_pengguna_store') is-invalid @enderror" id="passwordPengguna" placeholder="Masukkan Password Pengguna" autocomplete="off" value="{{ old('password_pengguna_store', $Pengguna->password) }}">
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#passwordPengguna" tabindex="-1">
                            <i class="toggle-icon" data-feather="eye"></i>
                        </button>
                        @error('password_pengguna_store')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="passwordKonfirmasiPengguna" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="key" width="20"></i>
                        </span>
                        <input type="password" name="password_konfirmasi_pengguna_store" class="form-control" id="passwordKonfirmasiPengguna" placeholder="Ulangi Password Pengguna"  value="{{ old('password_konfirmasi_pengguna_store') }}">
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#passwordKonfirmasiPengguna" tabindex="-1">
                            <i class="toggle-icon" data-feather="eye"></i>
                        </button>
                        @error('password_konfirmasi_pengguna_store')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="peranPengguna" class="form-label">Peran Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="briefcase" width="20"></i>
                        </span>
                        <select name="peran_id_store" id="peranPengguna" class="form-select @error('peran_id_store') is-invalid @enderror">
                            <option value="" selected></option>
                            @foreach ($PeranFormSelect as $Peran)
                            <option value="{{ $Peran->id }}"
                                {{ old('peran_id_store', $Peran->role_id) == $Peran->id ? 'selected' : '' }}>
                                {{ $Peran->nama_peran }}
                            </option>
                            @endforeach
                        </select>
                        @error('peran_id_store')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="lokasiPengguna" class="form-label">
                        Lokasi Pengguna
                        <i
                            data-feather="help-circle"
                            width="15"
                            tabindex="-1"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Pilih Lokasi Tugas untuk Pengguna Ber-Peran Laboran, atau 'Fleksible' jika bukan Laboran.">
                        </i>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-feather="map-pin" width="20"></i>
                        </span>
                        <select name="lokasi_id_store" id="lokasiPengguna" class="form-select @error('lokasi_id_store') is-invalid @enderror">
                            <option value="" selected></option>
                            @foreach ($LokasiFormSelect as $lok)
                            <option value="{{ $lok->id }}"
                                {{ old('lokasi_id_store', $Pengguna->lokasi_id) == $lok->id ? 'selected' : '' }}>
                                {{ $lok->nama_lokasi }}
                            </option>
                            @endforeach
                        </select>
                        @error('lokasi_id_store')
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
<div id="formDataPenggunaStore" class="d-none" data-errors="{{ json_encode($errors->any()) }}" data-session="{{ session('form') }}">
</div>
