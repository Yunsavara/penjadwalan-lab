@extends('layouts.app')

@section('title', 'Buat Booking Lab')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    {{-- Alert Error Input Data --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid px-3">

        <div class="col-12 mb-3">
            <label for="pilihRuangan">Ruangan</label>
            <select name="lab_id" id="pilihRuangan" class="form-select">

            </select>
        </div>

        <div class="col-12 mb-3">
            <label for="tanggalBooking">Tanggal Booking</label>
            <input type="date" name="tanggal_booking[]" id="tanggalBooking" class="form-control">
        </div>

        <div class="col-12 mb-3 d-flex flex-wrap justify-content-md-between align-items-center" id="jamContainer">
            {{-- disini jam mulai dan jam selesai --}}
        </div>
        {{-- Pagination untuk jam --}}
        <div id="paginationControls"></div>


        <div class="col-12 mb-3">
            <label for="keperluanBooking">Keperluan</label>
            <textarea name="keperluan" id="keperluanBooking" class="form-control @error('keperluan') is-invalid @enderror" autocomplete="off" style="min-height:80px; max-height:80px;">{{ old('keperluan', $Jadwal->keperluan) }}</textarea>
            @error('keperluan')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

@endsection
