@extends('layouts.app')

@section('title', 'Jenis Lab')

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
            <a href="{{ route('admin.matakuliah.create') }}" class="col-12 col-md-auto"><button class="btn btn-primary col-12">Tambah Data</button></a>
        </div>

        <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
            <div id="searchMataKuliah" class="col-12 col-md-auto mb-2"></div>
            <div id="sortingMataKuliah" class="col-12 col-md-auto mb-2"></div>
        </div>

        <div class="col-12 table-responsive position-relative" style="padding-bottom: 19rem">
            <table id="mataKuliahTable" class="table table-striped display text-truncate position-absolute top-0">
                <thead class="table-dark">
                    <th>No</th>
                    <th>Matakuliah</th>
                    <th>Dosen</th>
                    {{-- <th>Semester</th> --}}
                    <th>Aksi</th>
                </thead>
            </table>
        </div>

        <div class="col-12 py-2 d-flex flex-wrap align-items-center text-center justify-content-between">
            <div id="infoMataKuliah" class="col-12 col-md-auto mb-3"></div>
            <div id="pagingMataKuliah" class="col-12 col-md-auto mb-3 d-flex justify-content-center justify-content-md-auto"></div>
        </div>
    </div>

@endsection
