@extends('layouts.app')

@section('title', 'Jadwal')

@section('content')
    <h1 class="fw-bold text-uppercase">{{ $page_meta['page'] }}</h1>
    <hr>

    <!-- Alert -->
     <x-validation></x-validation>


     <div class="col-12 pb-3">
        @include('laboran.jadwal-page.generate-jadwal.datatables-generate-jadwal')
     </div>

     <div class="col-12 d-flex flex-wrap justify-content-between align-items-center">
        <div class="col-12 col-md-5">
            @include('laboran.jadwal-page.pengajuan.datatables-pengajuan')
        </div>
        <div class="col-12 col-md-6">
            @include('laboran.jadwal-page.booking-log.datatables-booking-log')
        </div>
     </div>

     @include('laboran.jadwal-page.pengajuan.datatables-pengajuan-terima-modal')
     @include('laboran.jadwal-page.pengajuan.datatables-pengajuan-tolak-modal')

@endsection
