@extends('layouts.app')

@section('title', 'Jadwal')

@section('content')
{{-- @vite(['resources/js/admin/pengguna-page/pengguna']) --}}

    <div class="col-12 p-3 py-4">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>
        {{-- <div id="table-container">
            @include('admin.pengguna-page.navigasi-pengguna')
        </div> --}}
    </div>
@endsection
