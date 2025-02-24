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

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="container-fluid px-2 d-flex flex-wrap justify-content-between bg-white shadow-sm rounded border">

        <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
            <div id="searchPengajuan" class="col-12 col-md-auto mb-2"></div>
            <div id="sortingPengajuan" class="col-12 col-md-auto mb-2"></div>
        </div>

        {{-- Ini Diganti jadwal booked nanti --}}
        <div class="col-12 table-responsive position-relative" style="padding-bottom: 10rem">
            <table id="pengajuanTable" class="table table-striped display text-truncate position-absolute top-0">
                <thead class="table-dark">
                    <th>No</th>
                    <th>Kode Pengajuan</th>
                    <th>Ruangan</th>
                    {{-- <th>Lokasi</th> --}}
                    {{-- <th>Kapasitas</th> --}}
                    <th>Status</th>
                    <th>Aksi</th>
                </thead>
            </table>
        </div>

        <div class="col-12 py-2 d-flex flex-wrap align-items-center text-center justify-content-between">
            <div id="infoPengajuan" class="col-12 col-md-auto mb-3 mb-md-0"></div>
            <div id="pagingPengajuan" class="col-12 col-md-auto mb-3 mb-md-0 d-flex justify-content-center justify-content-md-auto"></div>
        </div>
    </div>

    <div class="col-12 py-2 d-flex flex-wrap justify-content-end mt-2">
        <button class="btn btn-primary col-12 col-md-auto" id="btnPengajuan">Buat Pengajuan</button>
    </div>

    <div id="formPengajuanContainer">
        @include('all-role.form-pengajuan-store')
    </div>

    <div id="formPengajuanUpdateContainer">
        @include('all-role.form-pengajuan-update')
    </div>


    {{-- Modal Detail Pengajuan --}}
    <div class="modal fade" id="detailPengajuanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5 fw-bold" id="detailPengajuanLabel"></h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
    </div>

@endsection
