@extends('layouts.app')

@section('title', 'Edit Pengajuan')

@section('content')
@vite(['resources/js/pengguna/booking-page/booking'])

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

    <form id="formPengajuanBookingStore" class="mb-5" method="POST" action="{{ route('pengajuan.update', ['id' => request()->route('id')]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="lokasiPengajuanBooking" class="form-label">Pilih Lokasi</label>
            <select id="lokasiPengajuanBooking" name="lokasi_pengajuan_booking" class="form-select">
                <option value=""></option>
                @foreach ($lokasi as $lok)
                    <option value="{{ $lok->id }}">{{ $lok->nama_lokasi }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="labPengajuanBooking" class="form-label">Pilih Laboratorium</label>
            <select name="laboratorium_pengajuan_booking[]" id="labPengajuanBooking" class="form-select" multiple>
                <option value=""></option>
            </select>            
        </div>

        <div class="row d-flex flex-wrap justify-content-between">
            <div class="mb-3 col-md-6">
                <label for="tanggalMulaiPengajuanBooking" class="form-label">Tanggal Mulai</label>
                <input type="text" name="tanggal_mulai_pengajuan_booking" id="tanggalMulaiPengajuanBooking" class="form-control" placeholder="23 Juni 2025">
            </div>
    
            <div class="mb-3 col-md-6">
                <label for="tanggalSelesaiPengajuanBooking" class="form-label">Tanggal Selesai</label>
                <input type="text" name="tanggal_selesai_pengajuan_booking" id="tanggalSelesaiPengajuanBooking" class="form-control" placeholder="23 Januari 2026">
            </div>
        </div>

        <div class="mb-3" id="hariOperasionalWrapper">
            <label class="form-label d-block">Hari Operasional</label>
            <div id="hariOperasionalCheckboxes" class="d-flex flex-wrap gap-3">
              <!-- Checkbox akan masuk di sini -->
            </div>
        </div>

        <div class="mb-3" id="jamOperasionalWrapper">
            <!-- Select jam operasional akan masuk di sini -->
        </div>
          
        <div class="mb-3">
            <label for="keperluanPengajuanBooking" class="form-label">Keperluan Pengajuan</label>
            <textarea name="keperluan_pengajuan_booking" id="keperluanPengajuanBooking" class="form-control" rows="3" placeholder="Jelaskan keperluan Anda...">{{ old('keperluan_pengajuan_booking', $pengajuan->keperluan_pengajuan_booking) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary col-12">Perbarui</button>
    </form>
</div>

<script>
    window.oldForm = @json($oldForm);
</script>
@endsection
