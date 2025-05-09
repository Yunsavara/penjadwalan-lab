@extends('layouts.app')

@section('title', 'Pengajuan Jadwal')

@section('content')
   <div class="mx-2 my-3">
    <h1 class="fw-bold fs-3">Pengajuan Jadwal</h1>
    <hr>

    <!-- Alert -->
     <x-validation></x-validation>

     <div class="mx-2 my-3">
        <form action="">
            <div class="form-group my-3">
                <label for="building" class="col-4">Nama Gedung</label>
                <select name="" id="" class="form-control">
                    <option value="test1">Test 1</option>
                    <option value="test2">Test 2</option>
                </select>
            </div>

            <div class="form-group my-3">
                <label for="time" class="col-4">Tanggal Penggunaan</label>
                <input type="date" class="form-control">
            </div>

            <div class="form-group my-3">
                <label for="" class="col-4">Ruang Laboratorium yang Digunakan</label>
                <select class="form-control">
                    <option value="test1">Test 1</option>
                    <option value="test2">Test 2</option>
                </select>
            </div>

            <div class="form-group my-3">
                <label for="" class="col-4">Detail Penggunaan</label>
                <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <button type="reset" class="btn mx-3">Kosongkan</button>
                <button type="submit" class="btn text-light mybg-brown">Pengajuan</button>
            </div>
        </form>
     </div>
   </div>

@endsection
