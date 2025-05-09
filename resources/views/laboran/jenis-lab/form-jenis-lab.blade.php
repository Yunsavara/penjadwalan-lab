@extends('layouts.app')

@section('title', 'Tambah Jenis Lab')

@section('content')
    <div class="mx-2 my-3">
        <h1 class="fw-bold fs-3">Tambah Jenis Laboratorium</h1>
    <hr>

    {{-- Alert --}}
    <x-validation></x-validation>

    <div class="container-fluid px-3">
        <form action="{{ $page_meta['url'] }}" method="POST">
            @method($page_meta['method'])
            @csrf

            <div class="col-12 mb-3">
                <label for="namaJenisLab">Nama</label>
                <input type="text" name="name" id="namaJenisLab" class="form-control @error('name') is-invalid @enderror" autocomplete="off" value="{{ old('name', $Jenislab->name) }}">
                @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="descriptionJenisLab">Deskripsi</label>
                <textarea name="description" id="descriptionJenisLab" class="form-control @error('description') is-invalid @enderror" autocomplete="off" style="min-height:100px; max-height:100px;">{{ old('description', $Jenislab->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="reset" class="btn me-2">Kosongkan</button>
                <button type="submit" class="btn mybg-brown text-light">Simpan</button>
            </div>

        </form>
    </div>
    </div>
@endsection
