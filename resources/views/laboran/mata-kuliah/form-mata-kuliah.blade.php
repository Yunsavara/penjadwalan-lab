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
                <label for="namaMataKuliah">Nama Matakuliah</label>
                <input type="text" name="name" id="namaMataKuliah" class="form-control @error('name') is-invalid @enderror" autocomplete="off" value="{{ old('name', $MataKuliah->name) }}">
                @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="namaDosen">Nama Dosen</label>
                <select name="dosen[]" id="namaDosen" class="form-select @error('dosen') is-invalid @enderror" multiple>
                    @foreach($Dosen as $user)
                        <option value="{{ $user->id }}"
                            {{ in_array($user->id, old('dosen', $MataKuliah->users->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('dosen')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-12 d-flex justify-content-end">
                <button type="reset" class="btn btn-danger me-2">Reset</button>
                <button type="submit" class="btn btn-primary">{{ $page_meta['button_text'] }}</button>
            </div>
        </form>
    </div>

@endsection
