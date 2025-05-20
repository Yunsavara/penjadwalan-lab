@extends('layouts.app')

@section('title', 'Pengajuan')
@vite([
    'resources/js/pengguna/booking/form-booking-store.js',
    'resources/js/pengguna/booking/datatables-pengajuan-booking.js',
])


@section('content')
    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>

        {{-- Tombol Modal Form pengajuan booking --}}
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPengajuanBooking">
            <i data-feather="plus"></i>
        </button>

        @livewire('pengguna.booking.form-booking-store')

        <div class="table-container pt-2">
            @include('pengguna.booking.navigasi-booking')
        </div>
    </div>
@endsection 
