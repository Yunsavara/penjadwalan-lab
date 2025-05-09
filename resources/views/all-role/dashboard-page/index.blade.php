@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="my-3 mx-2">
        <p class="fw-bold fs-3">Selamat Datang, [Nama Pengguna]</p>
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
                    <p class="fw-medium mt-2">Jadwal Tersedia</p>
                    <p class="fs-2 fw-bold">154</p>
                </div>
            </div>
        </div>

        {{-- Most Use & Room Status --}}
        <div class="d-flex m-4">
            <div class="col d-flex flex-column justify-content-center align-items-center bg-white rounded me-2 p-4">
                <p class="fw-bold fs-5 text-center">Grafik peringkat<br>penggunaan terbanyak</p>
                <div class="position-relative" style="width: 150px">
                    <canvas id="userRankChart"></canvas>
                    <p class="position-absolute fw-bold fs-3 text-center" style="top: 58px; left: 52px">514</p>
                </div>

                <div class="mt-4">
                    <ol>
                        <li>Teknik Informatika</li>
                        <li>Teknik Elektro</li>
                        <li>Lembaga Bahasa</li>
                        <li>Lembaga Sertifikasi profesi</li>
                        <li>Lain-lain</li>
                    </ol>
                </div>
            </div>

            <div class="col bg-white rounded ms-2">
                <div class="d-flex justify-content-between mybg-brown100 rounded p-3">
                    <h4 class="fs-5 fw-bold">Status Pengajuan</h4>
                    <a class="text-light fw-bold" href="#">+ Tambah Pengajuan</a>
                </div>

                <div class="m-2">
                    <table class="table text-center">
                        <thead>
                            <tr class="fw-bold">
                                <td>No. Pengajuan</td>
                                <td>Ruangan</td>
                                <td>Hari</td>
                                <td>Status</td>
                            </tr>
                        </thead>

                        <tr class="align-middle">
                            <td>DSANFEW</td>
                            <td>CBT1, CBT2</td>
                            <td>Sabtu</td>
                            <td><div class="rounded p-1 fw-bold text-light mybg-orange myfs-1">Menunggu</div></td>
                        </tr>

                        <tr class="align-middle">
                            <td>JA4992NS</td>
                            <td>773</td>
                            <td>Sabtu</td>
                            <td><div class="rounded p-1 fw-bold text-light mybg-green myfs-1">Diterima</div></td>
                        </tr>

                        <tr class="align-middle">
                            <td>NJADKS8DS</td>
                            <td>612</td>
                            <td>Minggu</td>
                            <td><div class="rounded p-1 fw-bold text-light mybg-red myfs-1">Ditolak</div></td>
                        </tr>

                        <tr class="align-middle">
                            <td>21JDKS</td>
                            <td>782</td>
                            <td>Sabtu</td>
                            <td><div class="rounded p-1 fw-bold text-light mybg-blue myfs-1">Bersyarat</div></td>
                        </tr>
                    </table>

                    <div class="p-3 mt-3 text-center">
                        <a class="text-secondary fw-bold" href="#">Klik disini untuk selengkapnya</a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Submission Table --}}
        <div class="bg-white rounded m-4 p-4">
            <h6 class="fs-2 fw-medium mb-4">Jadwal Penggunaan Gedung Pusat</h6>

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

            <div class="mt-3 text-end">
                <a class="fw-bold mytext-brown" href="#"><i>Lebih Lengkap >></i></a>
            </div>
        </div>

        {{-- Submission Table --}}
        <div class="bg-white rounded m-4 p-4">
            <h6 class="fs-2 fw-medium mb-4">Jadwal Penggunaan Gedung Viktor</h6>

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

            <div class="mt-3 text-end">
                <a class="fw-bold mytext-brown" href="#"><i>Lebih Lengkap >></i></a>
            </div>
        </div>

        {{-- Submission Table --}}
        <div class="bg-white rounded m-4 px-4 pt-4 pb-1">
            <h6 class="fs-2 fw-medium mb-4">Jadwal Penggunaan Gedung Witana</h6>

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

            <div class="mt-3 text-end">
                <a class="fw-bold mytext-brown" href="#"><i>Lebih Lengkap >></i></a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
    <script>
        // User Rank Chart
        const ctxURChart = document.getElementById('userRankChart');

        const dataUR = {
            labels: [
                'Teknik Informatika',
                'Teknik Elektro',
                'Lembaga Bahasa',
                'Lembaga Sertifikasi Profesi',
                'Lain-lain'
            ],
            datasets: [{
                label: 'Penggunaan',
                data: [500, 300, 250, 140, 100],
                backgroundColor: [
                    'rgb(128, 206, 225)',
                    'rgb(217, 217, 217)',
                    'rgb(162, 132, 94)',
                    'rgb(255, 179, 71)',
                    'rgb(255, 145, 155)'
                ],
                hoverOffset: 4
            }],
        };

        const configUR = {
            type: 'doughnut',
            data: dataUR,
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
            }
        };

        new Chart(ctxURChart, configUR);
    </script>
@endsection
