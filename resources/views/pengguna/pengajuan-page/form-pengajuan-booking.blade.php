@extends('layouts.app')

@section('title', 'Buat Pengajuan')

@section('content')
@vite(['resources/js/pengguna/pengguna'])

    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>

        <form action="{{ route($page_meta['route_name']) }}" method="POST">
            @csrf
            @method($page_meta['method'])

            <div class="form-pengajuan-booking">
                <div class="col-12 d-flex flex-wrap align-items-center justify-content-between">
                    <div class="col-12 col-md-5 mb-3 mb-md-0">
                        <label for="tanggalMulaiBooking" class="form-label">Tanggal Mulai</label>
                        <input type="text" id="tanggalMulaiBooking" class="form-control" name="tanggal_mulai" placeholder="Pilih Tanggal Mulai">
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="tanggalSelesaiBooking" class="form-label">Tanggal Selesai</label>
                        <input type="text" id="tanggalSelesaiBooking" class="form-control" name="tanggal_selesai" placeholder="Pilih Tanggal Selesai">
                    </div>
                </div>

                <div class="col-12 pt-1 mb-3 text-start align-middle">
                    <label for="weekdays" class="form-check-label small me-1 me-md-2">
                        <input type="checkbox" id="weekdays" class="form-check-input"> Hari Kerja
                    </label>
                    <label for="sabtu" class="form-check-label small me-1 me-md-2">
                        <input type="checkbox" id="sabtu" class="form-check-input"> Sabtu
                    </label>
                    <label for="minggu" class="form-check-label small me-1 me-md-1">
                        <input type="checkbox" id="minggu" class="form-check-input"> Minggu
                    </label>
                    <i  data-feather="help-circle"
                        width="15"
                        tabindex="-1"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Hanya Generate Tanggal Berdasarkan Hari yang di Centang.">
                    </i>
                </div>

                <select id="labPengajuanBooking" name="laboratorium[]" class="form-select" multiple>
                    @php
                        $grouped = $Laboratoriums->groupBy('lokasi.nama_lokasi');
                    @endphp

                    @foreach ($grouped as $lokasi => $labs)
                        <optgroup label="Lokasi : {{ $lokasi }}">
                            @foreach ($labs as $lab)
                                <option value="{{ $lab->id }}">
                                    {{ $lab->nama_laboratorium }} - {{ $lab->jenislab->nama_jenis_lab }} (Kapasitas: {{ $lab->kapasitas_laboratorium }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>


                <div class="mb-3">
                    <label for="keperluanPengajuanBooking">Keperluan</label>
                    <textarea id="keperluanPengajuanBooking" class="form-control" name="keperluan_pengajuan_booking" placeholder="Keperluan Mengajar Mata Kuliah..." style="min-height: 100px; max-height:100px; resize:none;"></textarea>
                </div>

                <div class="mb-3 d-flex flex-wrap align-items-center justify-content-between">
                    <div>
                        <label class="form-label">Mode Pilih Jam</label>
                        <i  data-feather="help-circle"
                            width="15"
                            tabindex="-1"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Mode Otomatis akan Generate di Jam yang Sama untuk Semua Tanggal yang diplih.">
                        </i>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="modeJam" id="manual" value="manual" checked>
                            <label class="form-check-label small" for="manual">
                                Manual
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="modeJam" id="otomatis" value="otomatis">
                            <label class="form-check-label small" for="otomatis">
                                Otomatis
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3 d-none" id="autoJamContainer">
                    <label for="jamOtomatis" class="form-label">Pilih Jam Otomatis</label>
                    <select id="jamOtomatis" class="form-select" multiple>
                        <!-- Diisi via JavaScript -->
                    </select>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-primary col-12" onclick="generateBookingForm()">Generate Form</button>
                </div>

                <!-- Tempat Form Hasil Generate -->
                <div id="generatedFormContainer"></div>

                <div class="mb-3 mt-3">
                    <button type="submit" class="btn btn-success col-12">
                        <i data-feather="send" class="me-1"></i> Kirim Pengajuan
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection
