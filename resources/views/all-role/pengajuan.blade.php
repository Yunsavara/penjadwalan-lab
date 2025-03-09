@extends('layouts.app')

@section('title', 'Pengajuan')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    <!-- Alert -->
     <x-validation></x-validation>

    @include('all-role.navigasi')

    @include('all-role.datatables-pengajuan-detail-modal')
    @include('all-role.datatables-pengajuan-batalkan-modal')

    @include('all-role.form-pengajuan-store')
    @include('all-role.form-pengajuan-update')

@endsection
