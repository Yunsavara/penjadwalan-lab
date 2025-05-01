@extends('layouts.app')

@section('title', 'Buat Pengajuan')

@section('content')
@vite(['resources/js/pengguna/pengguna'])

<div class="col-12 p-3 py-4">
    <h2>{{ $page_meta['page'] }}</h2>
    <span>{{ $page_meta['description'] }}</span>
    <hr>

    {{-- Error Section (tidak mengisi kembali input) --}}
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

    <form id="formPengajuanBookingStore" class="mb-5" method="POST" action="{{ route($page_meta['route_name']) }}">
        @csrf
        @method($page_meta['method'])

        {{-- Pilih Lokasi --}}
        <div class="mb-3">
            <label for="lokasiSelect" class="form-label">Pilih Lokasi</label>
            <select id="lokasiSelect" name="lokasi" class="form-select">
                <option value=""></option>
                @foreach ($lokasi as $lok)
                    <option value="{{ $lok->id }}">{{ $lok->nama_lokasi }}</option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Lab --}}
        <div class="mb-3">
            <label for="labSelect" class="form-label">Pilih Laboratorium Utama</label>
            <select id="labSelect" class="form-select" name="laboratorium[]" multiple></select>
        </div>

        {{-- Tanggal Range --}}
        <div class="mb-3">
            <label for="tanggalRange" class="form-label">Pilih Tanggal (Range)</label>
            <input type="hidden" name="tanggalRange[0]" id="tanggalMulai">
            <input type="hidden" name="tanggalRange[1]" id="tanggalSelesai">
            <input type="text" id="tanggalRange" class="form-control" placeholder="Pilih tanggal">
        </div>

        {{-- Keperluan --}}
        <div class="mb-3">
            <label for="keperluanPengajuanBooking">Keperluan</label>
            <textarea name="keperluan_pengajuan_booking" class="form-control" id="keperluanPengajuanBooking"></textarea>
        </div>

        {{-- Hari Operasional --}}
        <div class="mb-3" id="hariOperasionalWrapper">
            <div id="hariOperasional"></div>
        </div>

        {{-- Jam Operasional & Tanggal --}}
        <div id="jamOperasionalContainer" class="mb-3"></div>
        <div id="daftarTanggal"></div>

        <button type="submit" class="btn btn-primary col-12 pt-3">Kirim</button>
    </form>
</div>

<script>
  window.oldFormData = @json(old());
</script>

@endsection
