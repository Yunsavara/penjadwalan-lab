@extends('layouts.app')

@section('title', 'Booking')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>


    {{-- Alert Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="col-12 py-2 d-flex flex-wrap justify-content-end">
        <a href="{{ route('laboran.booking.create') }}"><button class="btn btn-primary col-12 col-md-auto">Buat Jadwal</button></a>
    </div>

@endsection
