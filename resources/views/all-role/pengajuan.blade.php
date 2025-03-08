@extends('layouts.app')

@section('title', 'Pengajuan')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    <!-- Alert -->
     <x-validation></x-validation>

    @include('all-role.navigasi')

    @include('all-role.form-pengajuan-store')

@endsection
