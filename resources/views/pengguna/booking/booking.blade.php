@extends('layouts.app')

@section('title', 'Pengajuan')
@vite(['resources/js/pengguna/booking/form-pengajuan-booking.js'])

@section('content')
    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>

        @livewire('pengguna.booking.form-pengajuan-booking-create')
        @livewire('pengguna.booking.pengajuan-booking-table')
        @livewire('pengguna.booking.form-pengajuan-booking-edit')
        @livewire('pengguna.booking.detail-pengajuan-booking')
        @livewire('pengguna.booking.batalkan-pengajuan-booking')
    </div>
@endsection 
