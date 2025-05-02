@extends('layouts.app')

@section('title', 'Laboratorium')

@section('content')
    <div class="mx-2 my-3">
        <div class="d-flex justify-content-between">
            <h1 class="fw-bold fs-3">Laboratorium</h1>
            <a href="{{ route('laboran.laboratorium.create') }}" class="col-12 col-md-auto"><button class="btn bg-brown text-light">Tambah Data</button></a>
        </div>
    <hr>


     {{-- Alert --}}
     <x-validation></x-validation>

    {{-- Table --}}
    <div class="container-fluid px-2 d-flex flex-wrap justify-content-between bg-white shadow-sm rounded border">

        <div class="col-12 pt-3 d-flex flex-wrap align-items-center justify-content-between">
            <div id="searchLaboratorium" class="col-12 col-md-auto"></div>
            <div id="sortingLaboratorium" class="col-12 col-md-auto"></div>
        </div>

        <div class="col-12 table-responsive position-relative" style="padding-bottom: 19rem">
            <table id="laboratoriumTable" class="table table-striped display text-truncate top-0">
                <thead class="table-dark">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Lokasi</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </thead>
            </table>
        </div>

        <div class="col-12 mt-3 d-flex flex-wrap align-items-center text-center justify-content-between">
            <div id="infoLaboratorium" class="col-12 col-md-auto mb-3"></div>
            <div id="pagingLaboratorium" class="col-12 col-md-auto mb-3 d-flex justify-content-center justify-content-md-auto"></div>
        </div>
    </div>
    </div>
@endsection
