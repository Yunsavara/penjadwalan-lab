@extends('layouts.app')

@section('title', 'Tambah Jenis Lab')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    {{-- Alert Error Input Data --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid px-3">
        <form action="{{ $page_meta['url'] }}" method="POST">
            @method($page_meta['method'])
            @csrf

            <div class="col-12 mb-3">
                <label for="namaLaboratorium">Nama Laboratorium</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="namaLaboratorium" autocomplete="off" value="{{ old('name', $Laboratorium->name) }}">
                @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="jenisLab">Jenis Lab</label>
                <select name="jenislab_id" id="jenisLab" class="form-select @error('jenislab_id') is-invalid @enderror">
                    <option value="" selected></option>
                    @foreach ($Jenislab as $lab)
                        <option value="{{ $lab->id }}" {{ old('jenislab_id') == $lab->id ? 'selected' : '' }}>
                            {{ $lab->name }}
                        </option>
                    @endforeach
                </select>
                @error('jenislab_id')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="lokasiLaboratorium">Lokasi Laboratorium</label>
                <select name="lokasi" id="lokasiLaboratorium" class="form-select @error('lokasi') is-invalid @enderror">
                    <option value="" selected></option>
                    <option value="pusat" {{ old('lokasi', $Laboratorium->lokasi)  === 'pusat' ? 'selected' : '' }}>Pusat</option>
                    <option value="viktor" {{ old('lokasi', $Laboratorium->lokasi) === 'viktor' ? 'selected' : '' }}>Viktor</option>
                    <option value="serang" {{ old('lokasi', $Laboratorium->lokasi) === 'serang' ? 'selected' : '' }}>Serang</option>
                </select>
                @error('lokasi')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="kapasitasLaboratorium">Kapasitas Laboratorium</label>
                <input type="text" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" id="kapasitasLaboratorium" autocomplete="off" value="{{ old('kapasitas', $Laboratorium->kapasitas) }}">
                @error('kapasitas')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="statusLaboratorium">Status Laboratorium</label>
                <select name="status" id="statusLaboratorium" class="form-select @error('status') is-invalid @enderror">
                    <option value="tersedia" {{ old('status', $Laboratorium->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="tidak tersedia" {{ old('status', $Laboratorium->status) === 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
                @error('status')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="reset" class="btn btn-danger me-2">Reset</button>
                <button type="submit" class="btn btn-primary">{{ $page_meta['button_text'] }}</button>
            </div>
        </form>
    </div>

@endsection
