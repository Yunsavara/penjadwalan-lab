@extends('layouts.app')

@section('title', 'Barang')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    {{-- Cards --}}
    <div class="container-fluid col-12 d-flex justify-content-start gap-md-5 flex-wrap p-0">
        <x-card.counter background="white" color="black" counter="5" title="Rusak"></x-card.counter>
        <x-card.counter background="white" color="black" counter="2" title="Perbaikan"></x-card.counter>
        <x-card.counter background="white" color="black" counter="125" title="Tersedia"></x-card.counter>
    </div>

    {{-- Table --}}
    <x-table>
        <x-table.tools>
            <x-slot name="tambahBtn">
                <a href="{{ route('admin.barang.create') }}"><button class="btn btn-primary col-12 col-md-auto">Tambah</button></a>
            </x-slot>
            <x-slot name="tools">
                <span>Item 1</span>
            </x-slot>
            <x-slot name="search">
                <input type="search" class="col-12 col-md-auto" placeholder="Pencarian...">
            </x-slot>
        </x-table.tools>


    </x-table>
@endsection
