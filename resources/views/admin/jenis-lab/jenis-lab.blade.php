@extends('layouts.app')

@section('title', 'Jenis Lab')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    {{-- Cards --}}
    <div class="container-fluid col-12 d-flex justify-content-start gap-md-5 flex-wrap p-0">
        <x-card.counter background="white" color="black" counter="5" title="Pusat"></x-card.counter>
        <x-card.counter background="white" color="black" counter="4" title="Viktor"></x-card.counter>
        <x-card.counter background="white" color="black" counter="2" title="Serang"></x-card.counter>
    </div>

    {{-- Alert Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

   {{-- Table Content --}}
    <div class="container-fluid py-3 px-2 d-flex flex-wrap justify-content-between bg-white shadow-sm rounded border">

        <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
            <a href="{{ route('admin.jenis-lab.create') }}" class="col-12 col-md-auto"><button class="btn btn-primary col-12">Tambah Data</button></a>
        </div>

        <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
            <div id="searchJenisLab" class="col-12 col-md-auto mb-2"></div>
            <div id="sortingJenisLab" class="col-12 col-md-auto mb-2"></div>
        </div>

        <div class="col-12 table-responsive position-relative" style="padding-bottom: 19rem">
            <table id="jenisLabTable" class="table table-striped display text-truncate rounded position-absolute top-0">
                <thead class="table-dark">
                    <th>No</th>
                    <th>Jenis Lab</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </thead>
            </table>
        </div>


        <div class="col-12 py-2 d-flex flex-wrap align-items-center text-center justify-content-between">
            <div id="infoJenisLab" class="col-12 col-md-auto mb-3"></div>
            <div id="pagingJenisLab" class="col-12 col-md-auto mb-3 d-flex justify-content-center justify-content-md-auto"></div>
        </div>

    </div>

@endsection
