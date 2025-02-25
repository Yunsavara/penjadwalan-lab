@extends('layouts.app')

@section('title', 'Pengajuan')

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


    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Jadwal</button>
          <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Pengajuan</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active py-2" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
    </div>
    <div class="tab-pane fade py-2" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
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

    {{-- Modal Batalkan --}}
    <div class="modal fade" id="modalKonfirmasiBatalkan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Batalkan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="konfirmasiTextBatalkan">Apakah Anda yakin ingin mengubah status?</p>
                </div>
                <div class="modal-footer">
                    <form id="formUbahStatusBatalkan" action="{{ route('pengajuan.batalkan') }}" method="POST">
                        @csrf
                        <input type="text" id="kodePengajuanInputBatalkan" name="kode_pengajuan">
                        <input type="hidden" id="statusPengajuanInputBatalkan" name="status">
                        <button type="submit" class="btn btn-primary">Ya, Ubah</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

@endsection
