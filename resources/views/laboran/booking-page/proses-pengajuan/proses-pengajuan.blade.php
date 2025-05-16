@extends('layouts.app')

@section('title', 'Proses Pengajuan')

@section('content')
@vite(['resources/js/laboran/booking-page/proses-pengajuan/proses-pengajuan'])

@include('laboran.booking-page.proses-pengajuan.modal-proses-pengajuan')

    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>
        <div id="table-container">
            @include('laboran.booking-page.proses-pengajuan.datatables-proses-pengajuan')
        </div>
    </div>
@endsection 
