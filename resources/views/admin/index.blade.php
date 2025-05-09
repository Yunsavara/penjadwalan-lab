@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="my-3 mx-2">
        <p class="fw-bold fs-3">Selamat Datang, Admin</p>
        <p class="fs-6 mb-4">Ringkasan aktifitas penggunaan laboratorium komputer Universitas Pamolang</p>

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

        {{-- Usage History Chart --}}
        <div class="bg-white rounded-3 m-4 p-4" style="position: relative; width:75vw">
            <h4 class="fw-bold text-center p-3">Jumlah Jadwal 6 Periode Terakhir</h4>
            <canvas id="uHistoryChart"></canvas>
        </div>

        {{-- Building Chart --}}
        <div class="d-flex justify-content-evenly align-items-center bg-white rounded-3 m-4 p-4">
            <div class="col-4 text-center">
                <p class="fs-1 fw-bold">514</h6>
                <p>Jumlah Keseluruhan Jadwal</p>
            </div>

            <div class="d-flex col-8 justify-content-around">
                <div>
                    <div style="position: relative; width:100px">
                        <canvas id="buildChart1"></canvas>
                        <p class="position-absolute fw-bold" style="top: 44px; left: 38px">218</p>
                    </div>
                    <p>Gedung Pusat</p>
                </div>


                <div>
                    <div style="position: relative; width:100px">
                        <canvas id="buildChart2"></canvas>
                        <p class="position-absolute fw-bold" style="top: 44px; left: 38px">252</p>
                    </div>
                    <p>Gedung Viktor</p>
                </div>

                <div>
                    <div style="position: relative; width:100px">
                        <canvas id="buildChart3"></canvas>
                        <p class="position-absolute fw-bold" style="top: 44px; left: 38px">44</p>
                    </div>
                    <p>Gedung Witana</p>
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

            <div class="col bg-white rounded ms-2 p-5">
                <div class="text-center">
                    <p class="fs-1 fw-bold">43</p>
                    <p class="fw-bold fs-5">Jumlah Ruangan</p>
                    <hr class="my-5">
                </div>

                <div class="d-flex align-items-center justify-content-around">
                    <div class="text-center">
                        <p class="fw-bold fs-3">37</p>
                        <p>Ruangan<br>Tersedia</p>
                    </div>

                    <div class="mx-3">
                        <div class="text-center">
                            <p class="fw-bold fs-3">4</p>
                            <p>Ruangan<br>Diperbaiki</p>
                        </div>
                    </div>

                    <div>
                        <div class="text-center">
                            <p class="fw-bold fs-3">2</p>
                            <p>Ruangan<br>Rusak</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submission Table --}}
        <div class="bg-white rounded-3 m-4 p-4">
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>

    <script>
        // Usage History Chart
        const ctxBar = document.getElementById('uHistoryChart');

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['2023-1', '2023-2', '2024-1', '2024-2', '2025-1', '2025-2'],
                datasets: [{
                    label: 'Jumlah Jadwal',
                    data: [500, 476, 498, 572, 490, 514],
                    backgroundColor: [
                        'rgba(255, 234, 197, 0.8)',
                    ],
                    borderColor: [
                        'rgb(255, 159, 64)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Building Chart Config
        const buildName = ['Gedung Pusat', 'Gedung Viktor', 'Gedung Witana'];
        const temp = [218, 252, 44];

        for (let i = 0; i < buildName.length; i++) {
            const ctxBuild = document.getElementById('buildChart' + (i + 1));

            const data = {
                datasets: [{
                    label: buildName[i],
                    data: [temp[i], (514 - temp[i])],
                    backgroundColor: [
                        'rgb(96, 63, 38)',
                        'rgb(217, 217, 217)'
                    ],
                    hoverOffset: 4
                }],
            };

            const config = {
                type: 'doughnut',
                data: data,
                options: {
                    events: []
                }
            };


            new Chart(ctxBuild, config);
        }

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
                    'rgb(174, 198, 207)',
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
