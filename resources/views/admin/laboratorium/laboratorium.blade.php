@extends('layouts.app')

@section('title', 'Laboratorium')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    {{-- Cards --}}
    <div class="container-fluid col-12 d-flex justify-content-start gap-md-5 flex-wrap p-0">
        <x-card.counter background="white" color="black" counter="5" title="Tersedia"></x-card.counter>
        <x-card.counter background="white" color="black" counter="2" title="Tidak"></x-card.counter>
    </div>

     {{-- Alert Sukses --}}
     @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Table --}}
    <div class="container-fluid py-3 px-2 d-flex flex-wrap justify-content-between bg-white shadow-sm rounded border">

        <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
            <a href="{{ route('admin.laboratorium.create') }}" class="col-12 col-md-auto"><button class="btn btn-primary col-12">Tambah Data</button></a>
        </div>

    </div>
@endsection
