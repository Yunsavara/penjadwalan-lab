@extends('layouts.app')

@section('title', 'Jadwal Penggunaan')

@section('content')
    <div class="my-3 mx-2">
        <div class="d-flex justify-content-between align-items-center">
            <p class="fw-bold fs-3">Gedung Viktor</p>
            <nav>
                <a href="#">Home</a> / <a href="#">Jadwal</a> / <a href="#">Penggunaan</a> / <a class="fw-bold" style="color: rgba(111, 78, 55, 1) !important;" href="#">Gedung Viktor</a>
            </nav>
        </div>
        <hr>
        <p class="fs-6 mb-4">Jl. Raya Puspitek, Pamulang, Tangerang Selatan</p>

        <!-- Alert -->
        <x-validation></x-validation>


        <div class="col-12 pb-3">
            @include('laboran.jadwal-page.generate-jadwal.datatables-generate-jadwal')
        </div>

        {{-- <div class="col-12 d-flex flex-wrap justify-content-between align-items-center">
        <div class="col-12 col-md-5">
            @include('laboran.jadwal-page.pengajuan.datatables-pengajuan')
        </div>
        <div class="col-12 col-md-6">
            @include('laboran.jadwal-page.booking-log.datatables-booking-log')
        </div>
     </div> --}}

        @include('laboran.jadwal-page.pengajuan.datatables-pengajuan-terima-modal')
        @include('laboran.jadwal-page.pengajuan.datatables-pengajuan-tolak-modal')
    </div>

@endsection
