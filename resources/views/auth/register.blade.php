<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Page</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('AdminLte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('AdminLte/dist/css/adminlte.min.css') }}">
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.css">
    <link rel="stylesheet" href="{{ asset('css/mystyle.css') }}">

</head>

<body class="hold-transition login-page">
    <div class="container-fluid bg-fullscreen">
        <!-- Navbar -->
        <nav class="navbar bg-body-transparent">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="images/unpam-logo.png" alt="Logo" width="50" height="50"
                        class="d-inline-block align-text-top">
                    <b class="mysite-title mytext-white mx-3 fs-4"><i>Penjadwalan Lab</i></b>
                </a>
            </div>
        </nav>

        <div class="myspacer-10"></div>

        <div class="d-flex justify-content-center">
            <div class="mycard-md bg-dark30 p-4 border boder-primary">
                <form action="{{ $page_meta['url'] }}" method="post">
                    @method($page_meta['method'])
                    @csrf
                    <h3>DAFTAR</h3>
                    <hr width="100px" align="left" color="white">
                    <span class="mb-5">Silahkan masukkan beberapa data diri anda</span>
                    <div class="m-5"></div>

                    <!-- User Name Input -->
                    <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                    <div>
                        <input class="myinput @error('nama_lengkap') is-invalid @enderror" type="text" name="nama_lengkap"
                            id="namaLengkap" placeholder="Masukkan nama anda" value="{{ old('nama_lengkap', $register->nama_lengkap) }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                    </div>

                    <!-- User Email Input -->
                    <label for="userEmail" class="form-label mt-3">Email</label>
                    <div>
                        <input class="myinput @error('user_email') is-invalid @enderror" type="email" name="user_email"
                            id="userEmail" placeholder="Masukkan email anda" value="{{ old('user_email', $register->user_email) }}" required>
                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <label for="userPassword" class="form-label mt-3">Kata sandi</label>
                    <div>
                        <input class="myinput @error('password') is-invalid @enderror" type="password" name="password"
                            id="userPassword" placeholder="Kata Sandi" required>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Re-Password Input -->
                    <label for="userPasswordConfirm" class="form-label mt-3">Konfrimasi Kata sandi</label>
                    <div>
                        <input class="myinput @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation"
                            id="userPasswordConfirm" placeholder="Masukkan kembali kata sandi anda" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Lupa Password -->
                    {{-- <div class="text-right m-2"><a class="text-light" href="#"><b>Lupa Password</b></a></div> --}}

                    <!-- Spacer -->
                    <div style="height: 50px;"></div>

                    <button class="mybutton text-light" type="submit" value="Login">Daftar</button>

                </form>
                <p class="text-center mt-3">Sudah punya akun? <a class="text-light text-decoration-none" href="/login"><b>Login</b></a>
                    disini</p>
            </div>
        </div>

        <div class="myspacer-10"></div>

    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('AdminLte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLte/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
