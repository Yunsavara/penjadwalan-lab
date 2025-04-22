@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
@vite(['resources/js/admin/pengguna-page/pengguna'])

<<<<<<< Updated upstream
=======
{{-- Pengguna --}}
@include('admin.pengguna-page.pengguna.form-pengguna-store')
@include('admin.pengguna-page.pengguna.form-pengguna-update')
@include('admin.pengguna-page.pengguna.form-pengguna-soft-delete')

>>>>>>> Stashed changes
{{-- Peran --}}
@include('admin.pengguna-page.peran.form-peran-store')
@include('admin.pengguna-page.peran.form-peran-update')
@include('admin.pengguna-page.peran.form-peran-soft-delete')

{{-- Lokasi --}}
@include('admin.pengguna-page.lokasi.form-lokasi-store')
@include('admin.pengguna-page.lokasi.form-lokasi-update')
@include('admin.pengguna-page.lokasi.form-lokasi-soft-delete')

    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>
        <div id="table-container">
            @include('admin.pengguna-page.navigasi-pengguna')
        </div>
    </div>
@endsection
