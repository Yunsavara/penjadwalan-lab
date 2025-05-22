@extends('layouts.app')

@section('title', 'Proses Pengajuan')

@section('content')
{{-- @vite(['resources/js/laboran/']) --}}

    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>

        @livewire('laboran.prosespengajuanbooking.proses-pengajuan-booking-table')
        @livewire('laboran.prosespengajuanbooking.detail-proses-pengajuan-booking')
        @livewire('laboran.prosespengajuanbooking.terima-proses-pengajuan-booking')
        @livewire('laboran.prosespengajuanbooking.tolak-proses-pengajuan-booking')
    </div>
@endsection 
