@extends('layouts.app')

@section('title', 'Jadwal')

@section('content')
    <div class="mx-2 my-3">
        <h1 class="fw-bold fs-3">Jadwal Penggunaan</h1>
    <hr>

    <!-- Alert -->
     <x-validation></x-validation>

    @include('all-role.jadwal-page.navigasi')

    @include('all-role.jadwal-page.jadwal.datatables-jadwal-detail-modal')
    @include('all-role.jadwal-page.jadwal.datatables-jadwal-batalkan-modal')


    @include('all-role.jadwal-page.pengajuan.datatables-pengajuan-detail-modal')
    @include('all-role.jadwal-page.pengajuan.datatables-pengajuan-batalkan-modal')

    @include('all-role.jadwal-page.pengajuan.form-pengajuan-store')
    @include('all-role.jadwal-page.pengajuan.form-pengajuan-update')
    </div>

@endsection
