@extends('layouts.app')

@section('title', 'Pengajuan')
@vite([
    'resources/js/pengguna/booking/form-pengajuan-booking-create.js',
])


@section('content')
    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>

        {{-- Tombol Modal Form pengajuan booking
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPengajuanBooking">
            <i data-feather="plus"></i>
        </button>

        @livewire('pengguna.booking.form-pengajuan-booking-store') --}}
        @livewire('pengguna.booking.form-pengajuan-booking-create')
        <livewire:pengguna.booking.pengajuan-booking-table/>
    </div>
@endsection 
