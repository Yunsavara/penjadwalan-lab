@extends('layouts.app')

@section('title', 'Generate Jadwal Prodi')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    {{-- Alert Sukses dan Gagal --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid py-3 px-2 d-flex flex-wrap justify-content-between bg-white shadow-sm rounded border">

        <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
            <a href="{{ route('laboran.penjadwalan-prodi.create') }}" class="col-12 col-md-auto"><button class="btn btn-primary col-12">Tambah Generate</button></a>
        </div>

        <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
            <div id="searchSemester" class="col-12 col-md-auto mb-2"></div>
            <div id="sortingSemester" class="col-12 col-md-auto mb-2"></div>
        </div>

        <div class="col-12 py-2 d-flex flex-wrap align-items-center text-center justify-content-between">
            <div id="infoSemester" class="col-12 col-md-auto mb-3"></div>
            <div id="pagingSemester" class="col-12 col-md-auto mb-3 d-flex justify-content-center justify-content-md-auto"></div>
        </div>
    </div>
@endsection
