@extends('layouts.app')

@section('title', 'Jenis Lab')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    {{-- Alert --}}
    <x-validation></x-validation>

   {{-- Table Content --}}
    <div class="container-fluid py-3 px-2 d-flex flex-wrap justify-content-between bg-white shadow-sm rounded border">

        <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
            <a href="{{ route('laboran.jenis-lab.create') }}" class="col-12 col-md-auto"><button class="btn btn-primary col-12">Tambah Data</button></a>
        </div>

        <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
            <div id="searchJenisLab" class="col-12 col-md-auto mb-2"></div>
            <div id="sortingJenisLab" class="col-12 col-md-auto mb-2"></div>
        </div>

        <div class="col-12 table-responsive position-relative" style="padding-bottom: 19rem">
            <table id="jenisLabTable" class="table table-striped display text-truncate position-absolute top-0">
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
