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
        {{-- Tambah Button --}}
        <div class="col-12 col-md-auto mb-3">
            <a href="#"><button class="btn btn-primary col-12 col-md-auto">Tambah</button></a>
        </div>
        {{-- /Tambah Button --}}

        {{-- Searchbox --}}
        <div class="col-12 col-md-auto mb-3">
            <input type="search" id="jenisLabSearch" class="form-control col-12 col-md-auto" placeholder="Pencarian...">
        </div>
        {{-- /Searchbox --}}


        <div class="table-responsive position-relative col-12" style="padding-bottom:27rem">
            <table id="jenislab-table" class="table table-striped display text-truncate position-absolute top-0 rounded" style="width:100%">
                <thead class="table-dark"></thead>
            </table>
       </div>

        <div id="infoJenisLab">
        </div>
        <div id="paginationJenisLab"></div>
    </div>
    {{-- /Table Content --}}
    @push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
@endsection
