@extends('layouts.app')

@section('title', 'Pengajuan')

@section('content')
@vite(['resources/js/pengguna/booking-page/booking'])

    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>

        <div id="tools-pengajuan-booking">
            @include('pengguna.booking-page.pengajuan.tools-pengajuan-booking')
        </div>

        <div id="table-container">
            @include('pengguna.booking-page.navigasi-pengajuan-booking')
        </div>
    </div>
@endsection 
