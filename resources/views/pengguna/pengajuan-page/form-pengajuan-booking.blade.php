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
                  <option value="Viktor">Viktor</option>
                  <option value="Serang">Serang</option>
                </select>
              </div>
        
              <div class="mb-3">
                <label for="laboratorium" class="form-label">Laboratorium</label>
                <select id="laboratorium" class="form-select" multiple required>
                  <option value="1">Lab Komputer A</option>
                  <option value="2">Lab Komputer B</option>
                  <option value="3">Lab Multimedia</option>
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
                <label for="hari_aktif" class="form-label">Hari Aktif</label>
                <select id="hari_aktif" class="form-select" multiple required>
                  <option value="1">Senin</option>
                  <option value="2">Selasa</option>
                  <option value="3">Rabu</option>
                  <option value="4">Kamis</option>
                  <option value="5">Jumat</option>
                  <option value="6">Sabtu</option>
                  <option value="7">Minggu</option>
                </select>
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
            <form id="generatedForm">
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
