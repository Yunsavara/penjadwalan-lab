@extends('layouts.app')

@section('title', 'Buat Pengajuan')

@section('content')
@vite(['resources/js/pengguna/pengguna'])

    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>

        <form id="settinganForm" class="mb-5">
            <div class="row g-3">
              <div class="mb-3">
                <label for="lokasi" class="form-label">Lokasi</label>
                <select id="lokasi" class="form-select" required>
                  <option value="">-- Pilih Lokasi --</option>
                  @foreach ($Lokasi as $lok)
                    <option value="{{ $lok->id }}">{{ $lok->nama_lokasi }}</option>
                  @endforeach
                </select>
              </div>
        
              <div class="mb-3">
                <label for="laboratorium" class="form-label">Laboratorium</label>
                <select id="laboratorium" class="form-select" multiple required>
                  <option value="">-- Pilih Laboratorium --</option>
                </select>
              </div>
        
              <div class="col-md-6">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" class="form-control" required>
              </div>
        
              <div class="col-md-6">
                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" class="form-control" required>
              </div>
        
              <div class="mb-3">
                <label class="form-label">Hari Booking</label>
                <div class="row">
                  <div class="col-6 col-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="hari_aktif[]" value="1" id="senin">
                      <label class="form-check-label" for="senin">Senin</label>
                    </div>
                  </div>
                  <div class="col-6 col-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="hari_aktif[]" value="2" id="selasa">
                      <label class="form-check-label" for="selasa">Selasa</label>
                    </div>
                  </div>
                  <div class="col-6 col-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="hari_aktif[]" value="3" id="rabu">
                      <label class="form-check-label" for="rabu">Rabu</label>
                    </div>
                  </div>
                  <div class="col-6 col-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="hari_aktif[]" value="4" id="kamis">
                      <label class="form-check-label" for="kamis">Kamis</label>
                    </div>
                  </div>
                  <div class="col-6 col-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="hari_aktif[]" value="5" id="jumat">
                      <label class="form-check-label" for="jumat">Jumat</label>
                    </div>
                  </div>
                  <div class="col-6 col-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="hari_aktif[]" value="6" id="sabtu">
                      <label class="form-check-label" for="sabtu">Sabtu</label>
                    </div>
                  </div>
                  <div class="col-6 col-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="hari_aktif[]" value="7" id="minggu">
                      <label class="form-check-label" for="minggu">Minggu</label>
                    </div>
                  </div>
                </div>
              </div>              
        
              <div class="mb-3">
                <label for="jam" class="form-label">Jam Booking</label>
                <select id="jam" class="form-select" multiple required>
                  <option value="08:00-09:00">08:00-09:00</option>
                  <option value="09:00-10:00">09:00-10:00</option>
                  <option value="10:00-11:00">10:00-11:00</option>
                </select>
              </div>
        
              <div class="mb-3">
                <label for="alasan" class="form-label">Alasan Booking</label>
                <textarea id="alasan" class="form-control" style="min-height: 100px; max-height:100px; resize:none;" required>Praktikum Mata Kuliah</textarea>
              </div>
        
              <div class="col-12">
                <button type="button" class="btn btn-primary w-100" id="generateBtn">Generate Form</button>
              </div>
            </div>
        </form>

         <!-- Hasil Generate -->
        <div id="hasilGenerate" class="d-none">
            <h4 class="mb-3">Pilih Slot Booking</h4>
            <form id="generatedForm" action="{{ route($page_meta['route_name']) }}" method="POST">
              @csrf
              @method($page_meta['method'])

                <div class="accordion" id="accordionGenerated">
                    <!-- Isi Accordion di-generate pakai JS -->
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success">Submit Booking</button>
                </div>
            </form>
        </div>
        
    </div>
@endsection


{{-- Perlu setting berdasarkan lokasi, nanti tampil lab"nya --}}