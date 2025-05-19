@extends('layouts.app')

@section('content')
@vite(['resources/js/pengguna/booking-page/booking'])
<div class="container">
    <h3>Buat Pengajuan Booking</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            @if (session('pesan'))
                <span>{{ session('pesan') }}</span>
            @endif
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('pengajuan.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="lokasiPengajuanBooking">Lokasi</label>
            <select name="lokasi_pengajuan_booking" id="lokasiPengajuanBooking" class="form-select">
                <option value=""></option>
                @foreach ($lokasi as $lok)
                    <option value="{{ $lok->id }}">{{ $lok->nama_lokasi }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" for="laboratoriumPengajuanBooking">Laboratorium</label>
            <select name="laboratorium_pengajuan_booking[]" id="laboratoriumPengajuanBooking" class="form-select" multiple>
                <!-- Options akan diisi via JS -->
            </select>
        </div>

        <!-- Mode Pilih Tanggal -->
        <div class="mb-3">
            <label class="form-label">Mode Tanggal</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mode_tanggal" id="modeMulti" value="multi" checked>
                    <label class="form-check-label" for="modeMulti">Manual</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mode_tanggal" id="modeRange" value="range">
                    <label class="form-check-label" for="modeRange">Rentang</label>
                </div>
            </div>
        </div>

        <!-- Input Multi Date -->
        <div class="mb-3" id="multiDateContainer">
            <label class="form-label" for="tanggalMulti">Tanggal (Manual)</label>
            <input type="text" name="tanggal_multi" id="tanggalMulti" class="form-control tanggal-flatpickr">
        </div>

        <!-- Input Range -->
        <div class="mb-3 d-none" id="rangeDateContainer">
            <label class="form-label" for="tanggalRange">Tanggal (Rentang)</label>
            <input type="text" name="tanggal_range" id="tanggalRange" class="form-control tanggal-flatpickr">
        </div>
  
        <!-- Container Checkbox Hari (hanya untuk mode range) -->
        <div class="mb-3 d-none" id="hariOperasionalContainer">
            <div id="checkboxHariOperasional" class="row"></div>
        </div>

        <!-- Container sesi jam per hari -->
        <div class="mb-3" id="jamOperasionalContainer"></div>

        <!-- Container sesi jam per tanggal (mode multi) -->
        <div class="mb-3" id="jamPerTanggalContainer"></div>

        <div class="mb-3">
            <label class="form-label" for="keperluanPengajuanBooking">Keperluan</label>
            <textarea name="keperluan_pengajuan_booking" id="keperluanPengajuanBooking" class="form-control" style="max-height:100px; min-height:100px; resize:none;"></textarea>
        </div>

        <button type="submit" class="btn btn-primary col-12">Buat Pengajuan</button>
    </form>
</div>

<script>
    window.oldData = @json(old());
</script>
  
@endsection

