@extends('layouts.app')

@section('title', 'Jadwal')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
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

@endsection
