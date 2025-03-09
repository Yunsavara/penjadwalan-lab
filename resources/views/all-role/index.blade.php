@extends('layouts.app')

@section('title', 'Jadwal')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    <!-- Alert -->
     <x-validation></x-validation>

    @include('all-role.navigasi')

    @include('all-role.jadwal.datatables-jadwal-detail-modal')
    @include('all-role.jadwal.datatables-jadwal-batalkan-modal')


    @include('all-role.pengajuan.datatables-pengajuan-detail-modal')
    @include('all-role.pengajuan.datatables-pengajuan-batalkan-modal')

    @include('all-role.pengajuan.form-pengajuan-store')
    @include('all-role.pengajuan.form-pengajuan-update')

@endsection
