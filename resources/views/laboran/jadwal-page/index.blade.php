@extends('layouts.app')

@section('title', 'Jadwal Penggunaan')

@section('content')
    <div class="my-3 mx-2">
        <div class="d-flex justify-content-between align-items-center">
            <p class="fw-bold fs-3">Jadwal Penggunaan</p>
            <nav>
                <a href="#">Home</a> / <a href="#">Jadwal</a> / <a class="fw-bold"
                    style="color: rgba(111, 78, 55, 1) !important;" href="#">Penggunaan</a>
            </nav>
        </div>

        <!-- Alert -->
        <x-validation></x-validation>


        <table class="table">
            <thead>
                <tr>
                    <th class="bg-brown100">GEDUNG</th>
                    <th class="bg-brown100">ALAMAT GEDUNG</th>
                    <th class="bg-brown100 text-center">PILIHAN</th>
                </tr>
            </thead>

            <tr>
                <td>Pusat</td>
                <td>Jl. Bunderan</td>
                <td class="text-center">
                    <a href="" class="btn btn-secondary text-light">Lihat Jadwal</a>
                </td>
            </tr>

            <tr>
                <td>Viktor</td>
                <td>Jl. Raya Puspiptek</td>
                <td class="text-center">
                    <a href="#" class="btn btn-secondary text-light">Lihat Jadwal</a>
                </td>
            </tr>

            <tr>
                <td>Witana Harja</td>
                <td>Jl. Witana</td>
                <td class="text-center"><a href="#" class="btn btn-secondary text-light">Lihat Jadwal</a></td>
            </tr>
        </table>

    </div>

@endsection
