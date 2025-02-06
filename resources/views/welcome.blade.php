<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/unpam-logo.png') }}" type="image" sizes="16x16">
    <title>Penjadwalan Lab Unpam</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.css">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg d-flex justify-content-between">
        <div class="container-fluid px-5">
            <div class="navbar-brand">
                <img src="{{ asset('images/unpam-logo.png') }}" width="50px" alt="" srcset="">
                <b class="mysite-title mx-3 fs-4"><i>Penjadwalan Lab</i></b>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="container-fluid"></div>

            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    @if (Route::has('login'))
                        @auth
                            @if (auth()->user()->role == 'admin')
                                <a href="{{ url('/admin/dashboard') }}" class="nav-link">
                                    Admin Dashboard
                                </a>
                            @endif
                            @if (auth()->user()->role == 'laboran')
                                <a href="{{ url('/laboran/dashboard') }}" class="nav-link">
                                    Laboran Dashboard
                                </a>
                            @endif
                            @if (auth()->user()->role == 'user')
                                <a href="{{ url('/user/dashboard') }}" class="nav-link">
                                    User Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="nav-link">
                                Login
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="nav-link">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif

                </div>
            </div>
        </div>

    </nav>

    {{-- Carousel --}}
    <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/lab-mobile.jpg') }}" class="d-block w-100 myimage-darken" alt="...">
                <div class="carousel-caption d-none d-md-block text-start">
                    <h5 class="fs-1 mytext-shadowed">Laboratorium Mobile Programming</h5>
                    <p class="mytext-shadowed">Laboratorium dengan spesifikasi tinggi untuk mendukung praktikum dengan
                        proses yang kompleks khususnya untuk mata kuliah Mobile Programming</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/lab-networking.jpg') }}" class="d-block w-100 myimage-darken" alt="...">
                <div class="carousel-caption d-none d-md-block text-start">
                    <h5 class="fs-1 mytext-shadowed">Laboratorium Jaringan Komputer</h5>
                    <p class="mytext-shadowed">Laboratorium yang dirancang untuk kegiatan praktikum, pelatihan, dan
                        penelitian dalam bidang jaringan komputer. Fasilitas ini dilengkapi dengan peralatan dan
                        perangkat
                        keras untuk mendukung pembelajaran tentang desain, instalasi, konfigurasi, pengujian, serta
                        pengelolaan jaringan komputer.
                    </p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/cbt.jpg') }}" class="d-block w-100 myimage-darken" alt="...">
                <div class="carousel-caption d-none d-md-block text-start">
                    <h5 class="fs-1 mytext-shadowed">CBT (Computer Based Test)</h5>
                    <p class="mytext-shadowed">Laboratorium yang diprioritaskan untuk mendukung proses pengujian
                        akademik untuk mahasiswa/i Universitas Pamulang</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    {{-- Collaborate With --}}
    <div class="container text-center py-5 my-5">
        <h4 class="fs-1 pb-5 mb-5">Bekerja Sama Dengan</h4>
        <div class="container text-center">
            <div class="row row-cols-1 row-cols-md-4 g-4 mb-5">
                <div class="col">
                    <div class="text-center">
                        <img src="{{ asset('images/lb-logo.png') }}" alt="">
                        <p class="fs-5 fw-semibold pt-3">Lembaga Bahasa</p>
                    </div>
                </div>
                <div class="col">
                    <div class="text-center">
                        <img src="{{ asset('images/unpam-logo.png') }}" alt="">
                        <p class="fs-5 fw-semibold pt-3">Lembaga Sertifikasi Profesi</p>
                    </div>
                </div>
                <div class="col">
                    <div class="text-center">
                        <img src="{{ asset('images/unpam-logo.png') }}" alt="">
                        <p class="fs-5 fw-semibold pt-3">Laboran Teknik Informatika</p>
                    </div>
                </div>
                <div class="col">
                    <div class="text-center">
                        <img src="{{ asset('images/faculty-logo.png') }}" alt="">
                        <p class="fs-5 fw-semibold pt-3">Lebih dari 3 Fakultas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Collaborate With --}}
    <div class="mycontainer-service text-center py-5 mb-5">
        <h4 class="fs-3 pb-5">Fitur Aplikasi</h4>
        <div class="container">
            <div class="row row-cols-1 row-cols-md-3 g-4  mb-5">
                <div class="col">
                    <div class="card border-0">
                        <div class="card-body p-5">
                            <img class="myicons-feature" src="{{ asset('images/icons/time.png') }}" alt="">
                            <center>
                                <hr class="mydivider-brown">
                            </center>
                            <h5 class="card-title nytext-brown">Penjadwalan</h5>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0">
                        <div class="card-body mycard-brown p-5">
                            <img class="myicons-feature" src="{{ asset('images/icons/settings.png') }}" alt="">
                            <center>
                                <hr class="mydivider-white">
                            </center>
                            <h5 class="card-title text-light">Pengelolaan</h5>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0">
                        <div class="card-body p-5">
                            <img class="myicons-feature" src="{{ asset('images/icons/submission.png') }}" alt="">
                            <center>
                                <hr class="mydivider-brown my-3">
                            </center>
                            <h5 class="card-title mytext-brown">Pengajuan</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Facility --}}
    <div class="container-fluid p-5">
        <div class="row align-items-center mb-5 g-3 text-center">
            {{-- Left Section --}}
            <div class="col-md-6 px-5">
                <div class="row justify-content-end">
                    <div class="col-md-8 mycard m-3 p-2">
                        <div class="row">
                            <div class="col">
                                <img class="facility-icons" src="{{ asset('images/icons/sketch.png') }}" alt="">
                            </div>
                            <div class="col text-start">
                                <p class="fs-1 fw-bold m-0">80</p>
                                <p class="m-0">Jumlah Lab</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-start">
                    <div class="col-md-8 mycard m-3 py-2">
                        <div class="row">
                            <div class="col">
                                <img class="facility-icons" src="{{ asset('images/icons/computer.png') }}" alt="">
                            </div>
                            <div class="col text-start">
                                <p class="fs-1 fw-bold m-0">518</p>
                                <p class="m-0">Jumlah Komputer</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-8 mycard m-3 py-2">
                        <div class="row">
                            <div class="col">
                                <img class="facility-icons" src="{{ asset('images/icons/schedule-note.png') }}" alt="">
                            </div>
                            <div class="col text-start">
                                <p class="fs-1 fw-bold m-0">67</p>
                                <p class="m-0">Penjadwalan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-start">
                    <div class="col-md-8 mycard m-3 py-2">
                        <div class="row">
                            <div class="col">
                                <img class="facility-icons" src="{{ asset('images/icons/faculty.png') }}" alt="">
                            </div>
                            <div class="col text-start">
                                <p class="fs-1 fw-bold m-0">4</p>
                                <p class="m-0">Fakultas</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Section --}}
            <div class="col-md-6 px-5 text-start">
                <h4 class="fs-1 fw-bold mytext-brown py-5">Mengelola Jadwal Penggunaan Secara Efektif</h4>
                <p>
                    Mengelola jadwal penggunaan laboratorium komputer secara efektif untuk memaksimalkan produktivitas
                    dan
                    efisiensi. Dengan perencanaan yang terstruktur, setiap ruang laboratorium komputer dapat diatur
                    sesuai prioritas dan waktu yang tersedia.
                    Dengan data yang ada,
                    pengelolaan jadwal tidak hanya membantu menghindari konflik, tetapi juga menciptakan ruang untuk
                    fleksibilitas dan respons terhadap kebutuhan mendesak.
                </p>
            </div>
        </div>
    </div>

    {{-- Contact --}}
    <div class="mycontainer-contact">
        <div class="row text-center p-5">
            <div class="col">
                <h4 class="fw-semibold text-light">Jangan Sungkan Untuk Menghubungi Kami</h3>
            </div>
            <div class="col-md-4">
                <a type="button" class="btn btn-outline-light b-3" href="#">Hubungi Kami</a>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="mycard-brown text-light text-center py-3">
        Penjadwalan Lab &copy; 2024. Hak Cipta Sepenuhnya milik Universitas Pamulang.
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.js"></script>
</body>

</html>
