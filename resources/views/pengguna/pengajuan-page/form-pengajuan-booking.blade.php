@extends('layouts.app')

@section('title', 'Buat Pengajuan')

@section('content')
@vite(['resources/js/pengguna/pengguna'])

    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>

        <div class="form-pengajuan-booking">
            <div class="col-12 d-flex flex-wrap align-items-center justify-content-between">
                <div class="col-12 col-md-5 mb-3 mb-md-0">
                    <label for="tanggalMulaiBooking" class="form-label">Tanggal Mulai</label>
                    <input type="text" id="tanggalMulaiBooking" class="form-control" placeholder="Pilih Tanggal Mulai">
                </div>
                <div class="col-12 col-md-5">
                    <label for="tanggalSelesaiBooking" class="form-label">Tanggal Selesai</label>
                    <input type="text" id="tanggalSelesaiBooking" class="form-control" placeholder="Pilih Tanggal Selesai">
                </div>
            </div>

            <div class="col-12 pt-1 mb-3 text-start align-middle">
                <label for="weekdays" class="form-check-label small me-1 me-md-2">
                    <input type="checkbox" id="weekdays" class="form-check-input" checked> Hari Kerja
                </label>
                <label for="sabtu" class="form-check-label small me-1 me-md-2">
                    <input type="checkbox" id="sabtu" class="form-check-input" checked> Sabtu
                </label>
                <label for="minggu" class="form-check-label small me-1 me-md-2">
                    <input type="checkbox" id="minggu" class="form-check-input" checked> Minggu
                </label>
                <i  data-feather="help-circle"
                    width="20"
                    tabindex="-1"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Hanya Generate Tanggal Berdasarkan Hari yang di checklist.">
                </i>
            </div>

            <div class="mb-3">
                <label for="labPengajuanBooking">Pilih Laboratorium</label>
                <select id="labPengajuanBooking" class="form-select" multiple>
                    <option value="Lab Komputer">Lab Komputer</option>
                    <option value="Lab Fisika">Lab Fisika</option>
                    <option value="Lab Mesin">Lab Mesin</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="keperluanPengajuanBooking">Keperluan</label>
                <textarea id="keperluanPengajuanBooking" class="form-control" placeholder="Keperluan Mengajar Mata Kuliah..." style="min-height: 100px; max-height:100px; resize:none;"></textarea>
            </div>

            <div class="mb-3">
                <label for="modeJam" class="form-label">Mode Pilih Jam</label>
                <select id="modeJam" class="form-select">
                    <option value="manual" selected>Manual</option>
                    <option value="otomatis">Otomatis</option>
                </select>
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

        </div>
    </div>
@endsection
