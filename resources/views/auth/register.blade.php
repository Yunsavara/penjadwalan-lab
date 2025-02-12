<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/unpam-logo.png') }}" type="image">
    <title>Registrasi</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>

    <nav class="navbar navbar-expand-lg d-flex justify-content-between border-bottom shadow">
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

    <div class="container-fluid d-flex align-items-center" style="min-height: 88vh">
        <div class="container d-flex justify-content-between border mx-auto p-0 rounded-3 shadow-sm">
            <div class="register-image col-6">
                <img src="{{ asset('images/cbt.jpg') }}" alt="foto register page" class="rounded-start img-fluid h-100">
            </div>
            <div class="register-page col-6 py-3 px-5">
                <div class="register-form px-3">
                    <h3 class="text-center fw-bold">Daftar Akun</h3>
                    <hr>
                    <form action="{{ $page_meta['url'] }}" method="POST">
                        @method($page_meta['method'])
                        @csrf
                        <div class="col-12 mb-3">
                            <label for="namaLengkap">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="namaLengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" autocomplete="off" value="{{ old('nama_lengkap', $register->nama_lengkap) }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="userEmail">Email</label>
                            <input type="email" name="user_email" id="userEmail" class="form-control @error ('user_email') is-invalid @enderror" autocomplete="off" value="{{ old('user_email', $register->user_email) }}" required>
                            @error('user_email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="userPassword">Password</label>
                            <input type="password" name="password" id="userPassword" class="form-control @error ('password') is-invalid @enderror" autocomplete="off" required>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="userPasswordConfirm">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="userPasswordConfirm" class="form-control @error ('password_confirmation') is-invalid @enderror" autocomplete="off" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-1 text-end">
                            <a href="{{ route('login') }}" class="text-decoration-none">Sudah memiliki akun?</a>
                        </div>
                        <div class="col-12 mb-3">
                            <button type="submit" class="btn btn-primary col-12">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Boostrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
</html>
