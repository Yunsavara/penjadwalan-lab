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
                <label for="namaSemester">Nama Semester</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="namaSemester" autocomplete="off" value="{{ old('name', $Semester->name) }}">
                @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="tanggalMulai">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" autocomplete="off" id="tanggalMulai" value="{{ old('tanggal_mulai', $Semester->tanggal_mulai) }}">
                @error('tanggal_mulai')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="tanggalAkhir">Tanggal Selesai</label>
                <input type="date" name="tanggal_akhir" class="form-control @error('tanggal_akhir') is-invalid @enderror" autocomplete="off" id="tanggalSelesai" value="{{ old('tanggal_akhir', $Semester->tanggal_akhir) }}">
                @error('tanggal_akhir')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="statusSemester">Status Semester</label>
                <select name="status" id="statusSemester" class="form-select @error('status') is-invalid @enderror">
                    <option value="tidak aktif" {{old('status', $Semester->status) === 'tidak aktif' ? 'selected' : ''}}>Tidak Aktif</option>
                    <option value="aktif" {{old('status', $Semester->status) === 'aktif' ? 'selected' : ''}}>Aktif</option>
                    <option value="selesai" {{old('status', $Semester->status) === 'selesai' ? 'selected' : ''}}>Selesai</option>
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
