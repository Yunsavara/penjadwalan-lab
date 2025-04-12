@extends('layouts.app')

@section('title', 'Laboratorium')

@section('content')
    @vite(['resources/js/laboran/laboratorium-page/laboratorium'])

    {{-- Laboratorium --}}
    @include('laboran.laboratorium-page.laboratorium.form-laboratorium-store')
    @include('laboran.laboratorium-page.laboratorium.form-laboratorium-update')
    @include('laboran.laboratorium-page.laboratorium.form-laboratorium-soft-delete')

    {{-- Jenis Laboratorium --}}
    @include('laboran.laboratorium-page.jenis-lab.form-jenis-lab-store')
    @include('laboran.laboratorium-page.jenis-lab.form-jenis-lab-update')

    <div class="col-12 p-3">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>

        <div id="alertNotification">
            <x-validation></x-validation>
        </div>

        <div id="table-container">
            @include('laboran.laboratorium-page.navigasi-laboratorium')
        </div>
    </div>
@endsection
