@extends('layouts.app')

@section('title', 'Edit Pengajuan')

@section('content')
@vite(['resources/js/pengguna/booking-page/pengajuan/form-pengajuan-booking-update'])

<div class="col-12 p-3 py-4">
    <h2>{{ $page_meta['page'] }}</h2>
    <span>{{ $page_meta['description'] }}</span>
    <hr>

    @if ($errors->any())
      <div class="alert alert-danger mt-3">
          <strong>Terjadi kesalahan:</strong>
          <ul class="mb-0">
              @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
              @endforeach
          </ul>
      </div> 
    @endif

    <form id="formPengajuanBookingUpdate" class="mb-5" method="POST" action="{{ route('pengajuan.update', ['id' => request()->route('id')]) }}">
        @csrf
        @method('PUT')

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

        <button type="submit" class="btn btn-primary col-12">Perbarui</button>
    </form>
</div>

<script>
    window.oldData = {
        mode_tanggal: "{{ old('mode_tanggal', $pengajuan->mode_tanggal) }}",
        lokasi_pengajuan_booking: "{{ old('lokasi_pengajuan_booking', $pengajuan->lokasi_id) }}",
        laboratorium_pengajuan_booking: @json(old('laboratorium_pengajuan_booking', $pengajuan->laboratorium->pluck('id'))),
        tanggal_multi: "{{ old('tanggal_multi', $pengajuan->tanggal_multi_string) }}",
        tanggal_range: "{{ old('tanggal_range', $pengajuan->tanggal_range_string) }}",
        hari_operasional: @json(old('hari_operasional', $pengajuan->hari_operasional_array)),
        jam: @json(old('jam', $pengajuan->jam_per_tanggal_array)),
        keperluan_pengajuan_booking: @json(old('keperluan_pengajuan_booking', $pengajuan->keperluan)),
    };
</script>

@endsection
