@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="my-3 mx-2">
        <p class="fw-bold fs-3">Selamat Datang, Admin</p>
        <p class="fs-6 mb-4">Ringkasan aktifitas penggunaan laboratorium komputer Universitas Pamulang</p>

        {{-- Summary Cards --}}
        <div class="row row-cols-2 row-cols-lg-4 g-2 g-lg-3 mt-4">
            {{-- Card One --}}
            <div class="col">
                <div class="bg-white rounded m-2 px-4 py-2">
                    <p class="fw-medium mt-2">Jumlah Komputer</p>
                    <p class="fs-2 fw-bold">512</p>
                </div>
            </div>
            {{-- Card Two --}}
            <div class="col">
                <div class="bg-white rounded m-2 px-4 py-2">
                    <p class="fw-medium mt-2">Jumlah Ruangan</p>
                    <p class="fs-2 fw-bold">43</p>
                </div>
            </div>
            {{-- Card Three --}}
            <div class="col">
                <div class="bg-white rounded m-2 px-4 py-2">
                    <p class="fw-medium mt-2">Jumlah Jadwal</p>
                    <p class="fs-2 fw-bold">972</p>
                </div>
            </div>
            {{-- Card Four --}}
            <div class="col">
                <div class="bg-white rounded m-2 px-4 py-2">
                    <p class="fw-medium mt-2">Jumlah Pengguna</p>
                    <p class="fs-2 fw-bold">24</p>
                </div>
            </div>
        </div>

        {{-- Usage Chart --}}
        <div class="bg-white rounded-3 m-4 p-4">
            <h6 class="fs-2 fw-medium mb-4">Grafik Penggunaan Laboratorium</h6>

            <dl>
                <dd class="percentage percentage-50"><span class="text">Teknik Informatika (50%)</span></dd>
                <dd class="percentage percentage-16"><span class="text">Sistem Informasi (16%)</span></dd>
                <dd class="percentage percentage-5"><span class="text">Teknik Eletro (5%)</span></dd>
                <dd class="percentage percentage-2"><span class="text">Lembaga Bahasa (2%)</span></dd>
                <dd class="percentage percentage-2"><span class="text">Lembaga Sertifikasi Profesi (2%)</span></dd>
            </dl>
        </div>

        {{-- Submission Table --}}
        <div class="bg-white rounded m-4 p-4">
            <h6 class="fs-2 fw-medium mb-4">Tabel Pengajuan</h6>

            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td>John</td>
                    <td>Doe</td>
                    <td>@social</td>
                  </tr>
                </tbody>
              </table>
        </div>
    </div>
@endsection
